<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Proyek #{{ $report->id }}</title>
    <style>
        /* Reset CSS agar sama di semua browser */
        * { box-sizing: border-box; margin: 0; padding: 0; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12pt;
            background: #ccc; /* Background abu-abu saat preview */
            padding: 20px;
        }

        /* Tampilan Kertas A4 */
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
        }

        /* Header Laporan */
        .header {
            border-bottom: 3px double #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .header img { height: 60px; width: auto; }
        .header-text { text-align: right; }
        .header-text h1 { font-size: 18pt; color: #1e3a8a; text-transform: uppercase; margin-bottom: 5px; }
        .header-text p { font-size: 10pt; color: #666; }

        /* Tabel Informasi */
        .info-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-table td { padding: 5px; vertical-align: top; }
        .label { font-weight: bold; width: 140px; color: #444; }

        /* Kotak Konten */
        .box {
            border: 1px solid #333;
            padding: 10px;
            margin-bottom: 15px;
        }
        .box-title {
            background: #eee;
            font-weight: bold;
            padding: 5px 10px;
            border-bottom: 1px solid #333;
            margin: -10px -10px 10px -10px;
            font-size: 10pt;
            text-transform: uppercase;
        }

        /* Foto Dokumentasi */
        .img-container { text-align: center; margin-top: 10px; }
        .evidence-img {
            max-width: 100%;
            max-height: 350px;
            border: 1px solid #ddd;
            padding: 5px;
        }

        /* Footer Tanda Tangan */
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .sign-box { width: 40%; text-align: center; }
        .sign-space { height: 80px; }
        .sign-name { font-weight: bold; text-decoration: underline; }

        /* Tombol Navigasi (Hilang saat diprint) */
        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn {
            background: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 14px; cursor: pointer; border: none;
        }
        .btn-back { background: #64748b; margin-right: 10px; }

        /* CSS KHUSUS SAAT CETAK (PRINT) */
        @media print {
            body { background: white; padding: 0; }
            .page { width: 100%; margin: 0; padding: 0; box-shadow: none; }
            .no-print { display: none; } /* Sembunyikan tombol saat print */
            
            /* Paksa page break jika perlu */
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.history.back()" class="btn btn-back">&larr; Kembali</button>
        <button onclick="window.print()" class="btn">🖨️ Cetak Dokumen</button>
    </div>

    <div class="page">
        <div class="header">
            <img src="{{ asset('img/logo.png') }}" alt="Logo">
            <div class="header-text">
                <h1>SYAFA GROUP</h1>
                <p>Laporan Harian Pekerjaan Proyek</p>
                <p style="font-size: 9pt;">Tanggal Cetak: {{ date('d F Y') }}</p>
            </div>
        </div>

        <table class="info-table">
            <tr>
                <td class="label">ID Laporan</td>
                <td>: #{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td class="label">Tanggal Lapor</td>
                <td>: {{ \Carbon\Carbon::parse($report->report_date)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Nama Proyek</td>
                <td>: {{ $report->project->project_name }}</td>
                <td class="label">Lokasi</td>
                <td>: {{ $report->project->location }}</td>
            </tr>
            <tr>
                <td class="label">Vendor/Subkon</td>
                <td>: {{ $report->user->name }}</td>
                <td class="label">Perusahaan</td>
                <td>: {{ $report->user->company_name ?? '-' }}</td>
            </tr>
        </table>

        <div class="box">
            <div class="box-title">Deskripsi Pekerjaan</div>
            <p style="text-align: justify; line-height: 1.5;">
                {{ $report->work_description }}
            </p>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
            <tr>
                <td width="50%" style="vertical-align: top; padding-right: 10px;">
                    <div class="box" style="height: 100%;">
                        <div class="box-title">Realisasi Progress</div>
                        <div style="font-size: 24pt; font-weight: bold; color: #2563eb; text-align: center;">
                            {{ $report->progress_percentage }}%
                        </div>
                    </div>
                </td>
                <td width="50%" style="vertical-align: top; padding-left: 10px;">
                    <div class="box" style="height: 100%; background-color: #f0fdf4; border-color: #166534;">
                        <div class="box-title" style="background-color: #dcfce7; border-color: #166534; color: #166534;">
                            Hasil Verifikasi Admin
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 24pt; font-weight: bold; color: #166534;">
                                {{ $report->rating ?? '-' }} <span style="font-size: 10pt; color: #666;">/100</span>
                            </span>
                            <br>
                            <i style="font-size: 10pt;">"{{ $report->admin_note ?? 'Diterima' }}"</i>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        @if($report->documentation_path)
        <div class="box">
            <div class="box-title">Dokumentasi Lapangan</div>
            <div class="img-container">
                {{-- Gambar Base64 langsung dirender --}}
                <img src="{{ $report->documentation_path }}" class="evidence-img">
            </div>
        </div>
        @endif

        <div class="footer">
            <div class="sign-box">
                <p>Dibuat Oleh,</p>
                <div class="sign-space"></div>
                <p class="sign-name">{{ $report->user->name }}</p>
                <p style="font-size: 9pt;">Vendor / Pelaksana</p>
            </div>
            <div class="sign-box">
                <p>Disetujui Oleh,</p>
                <div class="sign-space" style="position: relative; height: 100px; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ asset('img/stempel.png') }}" alt="Stempel Approved" 
                         style="position: absolute; width: 120px; opacity: 0.8; transform: rotate(-10deg);">
                </div>
                <p class="sign-name">Admin Syafa Group</p>
                <p style="font-size: 9pt;">Internal Management</p>
            </div>

    </div>

    <script>
        // Otomatis munculkan dialog print saat halaman dibuka
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>