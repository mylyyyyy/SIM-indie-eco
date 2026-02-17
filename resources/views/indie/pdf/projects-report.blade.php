<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Proyek Indie</title>
    <style>
        /* Gaya dasar untuk tampilan di layar */
        body {
            font-family: 'Times New Roman', Times, serif; /* Font serif agar lebih resmi */
            font-size: 12px;
            color: #333;
            background-color: #f1f5f9;
            padding: 20px;
        }

        /* Kertas A4 buatan untuk preview */
        .paper {
            background-color: white;
            width: 210mm;
            min-height: 297mm;
            margin: auto;
            padding: 20mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
        }

        /* === CSS HEADER DENGAN LOGO === */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px double #4f46e5; /* Garis ganda biar lebih resmi */
            padding-bottom: 15px;
            position: relative; /* Penting untuk posisi absolut logo */
            min-height: 70px; /* Tinggi minimal agar muat logo */
        }

        /* Posisi Logo di pojok kiri atas header */
        .header-logo {
            position: absolute;
            left: 0;
            top: 5px;
            height: 65px; /* Sesuaikan ukuran tinggi logo di sini */
            width: auto;
        }

        .header h2 { margin: 0; color: #1e1e2f; text-transform: uppercase; letter-spacing: 1px; font-size: 18px; font-weight: bold;}
        .header p { margin: 5px 0 0 0; color: #64748b; font-size: 11px;}
        .header .company-name { font-size: 14px; font-weight: bold; color: #4f46e5; margin-bottom: 5px; }
        
        /* Tabel */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; vertical-align: top; font-family: Arial, sans-serif;}
        th { background-color: #eef2ff; color: #333; font-weight: bold; font-size: 11px; text-transform: uppercase;}
        tr:nth-child(even) { background-color: #f8fafc; }
        
        .text-center { text-align: center; }
        .status-completed { color: #059669; font-weight: bold; }
        .status-active { color: #2563eb; font-weight: bold; }

        /* === CSS FOOTER & STEMPEL === */
        .footer { margin-top: 50px; text-align: right; page-break-inside: avoid; font-family: Arial, sans-serif;}
        .signature-box { display: inline-block; text-align: center; width: 200px; position: relative; }
        .sign-spacer { height: 80px; position: relative; width: 100%; }
        .stempel-img {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-5deg);
            width: 90px; opacity: 0.8; z-index: 1; mix-blend-mode: multiply;
        }
        .sign-name-area { position: relative; z-index: 2; }
        .sign-line { font-weight: bold; text-decoration: underline; margin: 0; }
        .sign-subtext { font-size: 10px; color: #666; margin: 5px 0 0 0; }

        /* Tombol print */
        .action-buttons { text-align: center; margin-bottom: 20px; font-family: Arial, sans-serif; }
        .btn-print { background-color: #4f46e5; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; font-weight: bold; }
        .btn-close { background-color: #ef4444; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; font-weight: bold; margin-left: 10px;}

        @media print {
            body { background-color: white; padding: 0; }
            .paper { box-shadow: none; width: auto; min-height: auto; padding: 0; margin: 0; }
            .action-buttons { display: none; }
            @page { size: A4 portrait; margin: 15mm; }
        }
    </style>
</head>
<body onload="window.print()">

    {{-- PROSES PHP UNTUK MENGUBAH GAMBAR KE BASE64 --}}
    @php
        // 1. Proses LOGO
        $pathLogo = public_path('img/logo.png');
        $base64Logo = '';
        if(file_exists($pathLogo)) {
            $typeL = pathinfo($pathLogo, PATHINFO_EXTENSION);
            $dataL = file_get_contents($pathLogo);
            $base64Logo = 'data:image/' . $typeL . ';base64,' . base64_encode($dataL);
        }

        // 2. Proses STEMPEL
        $pathStempel = public_path('img/stempel_indie.png');
        $base64Stempel = '';
        if(file_exists($pathStempel)) {
            $typeS = pathinfo($pathStempel, PATHINFO_EXTENSION);
            $dataS = file_get_contents($pathStempel);
            $base64Stempel = 'data:image/' . $typeS . ';base64,' . base64_encode($dataS);
        }
    @endphp

    <div class="action-buttons no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
        <button class="btn-close" onclick="window.close()">Tutup Halaman</button>
    </div>

    <div class="paper">
        <div class="header">
            {{-- Tampilkan Logo jika file ditemukan --}}
            @if($base64Logo)
                <img src="{{ $base64Logo }}" class="header-logo" alt="Logo Syafa Group">
            @endif

            <p class="company-name">SYAFA GROUP</p>
            <h2>Laporan Data Proyek</h2>
            <p>Divisi Syafa Indie (Infrastruktur, Gedung & Komersial)</p>
            <p style="margin-top: 10px; font-size: 10px;">Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th width="30%">Nama Proyek</th>
                    <th width="25%">Lokasi</th>
                    <th width="15%">Tanggal Dibuat</th>
                    <th width="15%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $index => $project)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $project->project_name }}</strong></td>
                    <td>{{ $project->location ?? '-' }}</td>
                    <td>{{ $project->created_at->format('d/m/Y') }}</td>
                    <td class="{{ $project->status == 'completed' ? 'status-completed' : 'status-active' }}">
                        {{ ucfirst($project->status ?? 'Aktif') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; color: #777; font-style: italic;">Tidak ada data proyek yang ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="footer">
            <div class="signature-box">
                <p style="margin-bottom: 0;">Mengetahui,</p>
                
                <div class="sign-spacer">
                    @if($base64Stempel)
                        <img src="{{ $base64Stempel }}" class="stempel-img" alt="Stempel Indie">
                    @endif
                </div>

                <div class="sign-name-area">
                    <p class="sign-line">Admin Indie</p>
                    <p class="sign-subtext">Syafa Group</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>