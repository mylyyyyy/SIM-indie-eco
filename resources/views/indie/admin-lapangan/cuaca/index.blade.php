<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Laporan Cuaca Harian</h2>
            <p class="text-slate-500 font-medium">Catat kondisi cuaca lapangan sebagai justifikasi waktu kerja proyek.</p>
        </div>
        
        <button x-data @click="$dispatch('open-modal', 'add-cuaca-modal')" 
            class="bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-sky-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-cloud-sun"></i> <span>Input Kondisi Cuaca</span>
        </button>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false, toast: true, position: 'top-end' });
            });
        </script>
    @endif
    @if($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 border border-red-100 font-medium text-sm">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
        </div>
    @endif

    {{-- Helper Function untuk Ikon Cuaca di View --}}
    @php
        function getWeatherIcon($kondisi) {
            $k = strtolower($kondisi);
            if (str_contains($k, 'cerah')) return '<i class="fas fa-sun text-amber-500"></i>';
            if (str_contains($k, 'berawan') || str_contains($k, 'mendung')) return '<i class="fas fa-cloud text-slate-400"></i>';
            if (str_contains($k, 'gerimis')) return '<i class="fas fa-cloud-rain text-sky-400"></i>';
            if (str_contains($k, 'hujan deras') || str_contains($k, 'badai')) return '<i class="fas fa-poo-storm text-indigo-500"></i>';
            return '<i class="fas fa-cloud-showers-heavy text-blue-500"></i>';
        }
    @endphp

    {{-- TABLE CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Tgl & Kode</th>
                        <th class="px-6 py-4">Pola Cuaca (P/S/S/M)</th>
                        <th class="px-6 py-4">Dampak ke Pekerjaan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Foto Bukti</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($cuacas as $item)
                        @php
                            $pagi = $item->data_spesifik['pagi'] ?? '-';
                            $siang = $item->data_spesifik['siang'] ?? '-';
                            $sore = $item->data_spesifik['sore'] ?? '-';
                            $malam = $item->data_spesifik['malam'] ?? '-';
                            $jam_hujan = $item->data_spesifik['jam_hujan'] ?? 0;
                            $dampak = $item->data_spesifik['dampak_pekerjaan'] ?? '-';
                        @endphp
                    <tr class="hover:bg-sky-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->tanggal_periode)->format('d M Y') }}</div>
                            <div class="text-[10px] text-sky-500 font-mono mt-1">{{ $item->kode_referensi }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2 text-xs">
                                <div class="bg-slate-50 px-2 py-1 rounded border border-slate-200 text-center min-w-[55px]">
                                    <span class="block text-[9px] text-slate-400 uppercase mb-0.5">Pagi</span>
                                    <span class="font-bold text-slate-700">{!! getWeatherIcon($pagi) !!} <span class="block text-[9px] mt-0.5">{{ $pagi }}</span></span>
                                </div>
                                <div class="bg-slate-50 px-2 py-1 rounded border border-slate-200 text-center min-w-[55px]">
                                    <span class="block text-[9px] text-slate-400 uppercase mb-0.5">Siang</span>
                                    <span class="font-bold text-slate-700">{!! getWeatherIcon($siang) !!} <span class="block text-[9px] mt-0.5">{{ $siang }}</span></span>
                                </div>
                                <div class="bg-slate-50 px-2 py-1 rounded border border-slate-200 text-center min-w-[55px]">
                                    <span class="block text-[9px] text-slate-400 uppercase mb-0.5">Sore</span>
                                    <span class="font-bold text-slate-700">{!! getWeatherIcon($sore) !!} <span class="block text-[9px] mt-0.5">{{ $sore }}</span></span>
                                </div>
                                <div class="bg-slate-50 px-2 py-1 rounded border border-slate-200 text-center min-w-[55px]">
                                    <span class="block text-[9px] text-slate-400 uppercase mb-0.5">Malam</span>
                                    <span class="font-bold text-slate-700">{!! getWeatherIcon($malam) !!} <span class="block text-[9px] mt-0.5">{{ $malam }}</span></span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($jam_hujan > 0)
                                <span class="bg-red-50 text-red-600 px-2 py-0.5 rounded text-[10px] font-bold border border-red-100 inline-block mb-1"><i class="fas fa-stopwatch mr-1"></i> Terhenti: {{ $jam_hujan }} Jam</span>
                            @else
                                <span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded text-[10px] font-bold border border-emerald-100 inline-block mb-1"><i class="fas fa-check mr-1"></i> Full Time (0 Jam Hujan)</span>
                            @endif
                            <p class="text-xs font-bold text-slate-700 line-clamp-2 mt-1">{{ $dampak }}</p>
                            @if($item->keterangan_umum)
                                <p class="text-[10px] text-slate-400 mt-1 italic">Ket: {{ $item->keterangan_umum }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status == 'Approved')
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-md uppercase">Approved</span>
                            @elseif($item->status == 'Revisi')
                                <span class="px-2.5 py-1 bg-red-100 text-red-700 text-[10px] font-bold rounded-md uppercase" title="{{ $item->catatan_evaluator }}">Revisi <i class="fas fa-info-circle"></i></span>
                            @else
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-md uppercase">Submit</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->dokumen_lampiran)
                                <a href="{{ $item->dokumen_lampiran }}" download="Cuaca_{{ $item->kode_referensi }}" class="w-8 h-8 mx-auto rounded-lg bg-sky-50 border border-sky-100 text-sky-600 hover:bg-sky-600 hover:text-white transition-all shadow-sm flex items-center justify-center group" title="Unduh Foto Lapangan">
                                    <i class="fas fa-image group-hover:scale-110 transition-transform"></i>
                                </a>
                            @else
                                <span class="text-xs text-slate-400 italic">Kosong</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('indie.admin-lapangan.laporan-cuaca.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus laporan cuaca ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white transition-all shadow-sm mx-auto flex items-center justify-center" {{ $item->status == 'Approved' ? 'disabled opacity-50 cursor-not-allowed' : '' }}>
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium"><i class="fas fa-cloud-sun-rain text-4xl mb-3 block"></i> Belum ada data log cuaca lapangan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH CUACA --}}
    <x-modal name="add-cuaca-modal" focusable>
        <form action="{{ route('indie.admin-lapangan.laporan-cuaca.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
            @csrf
            <div class="bg-gradient-to-r from-sky-500 to-blue-600 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                <h3 class="text-white font-bold text-lg">Input Log Cuaca Harian</h3>
                <button type="button" x-on:click="$dispatch('close')" class="text-sky-100 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-5">
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal Laporan</label>
                    <input type="date" name="tanggal_laporan" value="{{ date('Y-m-d') }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500" required>
                </div>

                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-3"><i class="fas fa-cloud-sun mr-1"></i> Pola Cuaca Hari Ini</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @php $opts = ['Cerah', 'Berawan / Mendung', 'Gerimis', 'Hujan Sedang', 'Hujan Deras / Badai']; @endphp
                        <div>
                            <span class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Pagi (08-12)</span>
                            <select name="kondisi_pagi" class="w-full border-slate-200 rounded-lg text-xs" required>
                                @foreach($opts as $opt) <option value="{{ $opt }}">{{ $opt }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <span class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Siang (12-15)</span>
                            <select name="kondisi_siang" class="w-full border-slate-200 rounded-lg text-xs" required>
                                @foreach($opts as $opt) <option value="{{ $opt }}">{{ $opt }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <span class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Sore (15-18)</span>
                            <select name="kondisi_sore" class="w-full border-slate-200 rounded-lg text-xs" required>
                                @foreach($opts as $opt) <option value="{{ $opt }}">{{ $opt }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <span class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Malam (Lembur)</span>
                            <select name="kondisi_malam" class="w-full border-slate-200 rounded-lg text-xs" required>
                                <option value="Tidak Lembur">Tidak Lembur</option>
                                @foreach($opts as $opt) <option value="{{ $opt }}">{{ $opt }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-red-500 uppercase mb-2"><i class="fas fa-stopwatch mr-1"></i> Waktu Terhenti Akibat Hujan (Jam)</label>
                    <input type="number" step="0.5" name="jam_hujan" value="0" min="0" max="24" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500 font-bold" required>
                    <span class="text-[10px] text-slate-400 mt-1 block">*Isi 0 jika pekerjaan berjalan full (tidak ada gangguan cuaca).</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Dampak Terhadap Pekerjaan</label>
                    <textarea name="dampak_pekerjaan" rows="2" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500" required placeholder="Contoh: Pekerjaan atap dihentikan paksa selama 3 jam, target harian meleset..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Upload Foto Kondisi Lapangan (Opsional)</label>
                    <input type="file" name="dokumen_lampiran" class="w-full border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100" accept=".pdf,.jpg,.jpeg,.png">
                </div>

            </div>
            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl border-t border-slate-100">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-sky-600 text-white rounded-xl text-sm font-bold hover:bg-sky-700">Simpan Log Cuaca</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>