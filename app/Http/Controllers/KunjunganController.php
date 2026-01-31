<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\KunjunganConfirmationMail;
use Illuminate\Support\Str;

class KunjunganController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $datesByDay = [
            'Senin' => [],
            'Selasa' => [],
            'Rabu' => [],
            'Kamis' => [],
        ];

        $date = \Carbon\Carbon::today();
        $dayMapping = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
        ];

        for ($i = 0; $i < 60; $i++) { // Search up to 60 days in the future
            $currentDate = $date->copy()->addDays($i);
            $dayOfWeek = $currentDate->dayOfWeek;

            if (array_key_exists($dayOfWeek, $dayMapping)) {
                $dayName = $dayMapping[$dayOfWeek];
                // Add up to 3 upcoming dates for each valid day
                if (count($datesByDay[$dayName]) < 3) {
                    $datesByDay[$dayName][] = [
                        'value' => $currentDate->format('Y-m-d'),
                        'label' => $currentDate->translatedFormat('d F Y'),
                    ];
                }
            }
        }

        return view('guest.kunjungan.create', ['datesByDay' => $datesByDay]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Dasar
        $validated = $request->validate([
            'nama_pengunjung'    => 'required|string|max:255',
            'nik_pengunjung'     => 'required|string|size:16',
            'no_wa_pengunjung'   => 'required|string|max:15',
            'email_pengunjung'   => 'required|email|max:255',
            'alamat_pengunjung'  => 'required|string',
            'nama_wbp'           => 'required|string|max:255',
            'hubungan'           => 'required|string|max:100',
            'tanggal_kunjungan'  => 'required|date|after_or_equal:today',
            'sesi'               => 'nullable|string|in:pagi,siang',
        ]);

        $tanggalKunjungan = \Carbon\Carbon::parse($validated['tanggal_kunjungan']);
        $sesi = $request->input('sesi');

        // 2. Validasi Hari & Sesi
        if ($tanggalKunjungan->isFriday() || $tanggalKunjungan->isSaturday() || $tanggalKunjungan->isSunday()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'tanggal_kunjungan' => 'Pendaftaran tidak bisa dilakukan pada hari Jumat, Sabtu, atau Minggu.',
            ]);
        }

        if ($tanggalKunjungan->isMonday() && !$sesi) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'sesi' => 'Untuk hari Senin, Anda wajib memilih sesi kunjungan.',
            ]);
        }

        // 3. Validasi Kuota
        $query = Kunjungan::where('tanggal_kunjungan', $tanggalKunjungan->format('Y-m-d'));
        
        if ($tanggalKunjungan->isMonday()) {
            $kuota = ($sesi == 'pagi') ? config('kunjungan.quota_senin_pagi') : config('kunjungan.quota_senin_siang');
            $jumlahPendaftar = (clone $query)->where('sesi', $sesi)->where('status', 'approved')->count();
            
            if ($jumlahPendaftar >= $kuota) {
                $namaSesi = ($sesi == 'pagi') ? 'Pagi' : 'Siang';
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'sesi' => "Maaf, kuota untuk Sesi {$namaSesi} pada tanggal tersebut sudah penuh.",
                ]);
            }
        } else {
            $kuota = config('kunjungan.quota_hari_biasa');
            $jumlahPendaftar = (clone $query)->where('status', 'approved')->count();

            if ($jumlahPendaftar >= $kuota) {
                 throw \Illuminate\Validation\ValidationException::withMessages([
                    'tanggal_kunjungan' => 'Maaf, kuota untuk tanggal tersebut sudah penuh.',
                ]);
            }
        }

        // 4. Proses Database
        $kunjunganBaru = null;
        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $tanggalKunjungan, $sesi, &$kunjunganBaru) {
            $nomorAntrian = Kunjungan::where('tanggal_kunjungan', $tanggalKunjungan->format('Y-m-d'))->count() + 1;
            
            $validated['nomor_antrian_harian'] = $nomorAntrian;
            $validated['status'] = 'pending';
            $validated['qr_token'] = Str::uuid(); // Generate unique QR token
            if ($sesi) {
                $validated['sesi'] = $sesi;
            }

            $kunjunganBaru = Kunjungan::create($validated);
        });

        // Send confirmation email
        Mail::to($kunjunganBaru->email_pengunjung)->queue(new KunjunganConfirmationMail($kunjunganBaru));

        // 5. Redirect dengan pesan sukses
        $pesanSukses = "Pendaftaran berhasil! Nomor antrian Anda: {$kunjunganBaru->nomor_antrian_harian}.";
        if ($kunjunganBaru->sesi) {
            $pesanSukses .= " Anda terdaftar untuk Sesi " . ucfirst($kunjunganBaru->sesi) . ".";
        }
        $pesanSukses .= " Mohon tunggu konfirmasi dari petugas.";

        return redirect()->route('kunjungan.status', $kunjunganBaru)->with('success', $pesanSukses);
    }

    /**
     * Display the specified resource.
     */
    public function status(Kunjungan $kunjungan)
    {
        return view('guest.kunjungan.status', compact('kunjungan'));
    }

    /**
     * Display a verification page for the specified resource.
     */
    public function verify(Kunjungan $kunjungan)
    {
        return view('guest.kunjungan.verify', compact('kunjungan'));
    }

        /**

         * Get the status of a Kunjungan for API calls.

         */

        public function getStatusApi(Kunjungan $kunjungan)

        {

            return response()->json(['status' => $kunjungan->status]);

        }

    

        /**

         * Get quota status for a given date and session for API calls.

         */

        public function getQuotaStatus(Request $request)

        {

            $validated = $request->validate([

                'tanggal_kunjungan' => 'required|date_format:Y-m-d',

                'sesi'              => 'nullable|string|in:pagi,siang',

            ]);

    

            $tanggalKunjungan = \Carbon\Carbon::parse($validated['tanggal_kunjungan']);

            $sesi = $validated['sesi'] ?? null;

    

            if ($tanggalKunjungan->isFriday() || $tanggalKunjungan->isSaturday() || $tanggalKunjungan->isSunday()) {

                return response()->json(['error' => 'Kunjungan tidak tersedia pada hari Jumat, Sabtu, atau Minggu.'], 422);

            }

    

            $query = Kunjungan::where('tanggal_kunjungan', $tanggalKunjungan->format('Y-m-d'));

            $jumlahPendaftar = 0;

            $totalKuota = 0;

    

            if ($tanggalKunjungan->isMonday()) {

                if (!$sesi) {

                    return response()->json(['error' => 'Sesi harus dipilih untuk hari Senin.'], 422);

                }

                $totalKuota = ($sesi == 'pagi') ? config('kunjungan.quota_senin_pagi') : config('kunjungan.quota_senin_siang');

                $jumlahPendaftar = (clone $query)->where('sesi', $sesi)->where('status', 'approved')->count();

            } else {

                $totalKuota = config('kunjungan.quota_hari_biasa');

                $jumlahPendaftar = (clone $query)->where('status', 'approved')->count();

            }

    

            $sisaKuota = $totalKuota - $jumlahPendaftar;

    

                    return response()->json([

    

                            'total_kuota' => $totalKuota,

    

                            'jumlah_pendaftar' => $jumlahPendaftar,

    

                            'sisa_kuota' => $sisaKuota,

    

                        ]);

    

                    }

    

            

    

                /**

    

                 * Display a printable version of the registration proof.

    

                 */

    

                public function printProof(Kunjungan $kunjungan)

    

                {

    

                    return view('guest.kunjungan.print', compact('kunjungan'));

    

                }

    

            }
