<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Harian - Kepala Kantor</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { position: relative; text-align: center; margin-bottom: 30px; border-bottom: 3px double #1e3a8a; padding-bottom: 15px; min-height: 70px; }
        .header-logo { position: absolute; left: 0; top: 0; height: 60px; width: auto; }
        .header h2 { margin: 0; font-size: 18px; text-transform: uppercase; color: #1e3a8a; letter-spacing: 1px; font-weight: bold;}
        .header p { margin: 5px 0 0 0; font-size: 11px; color: #64748b; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px 0; font-size: 12px; }
        .info-table td.label { font-weight: bold; width: 150px; }

        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data-table th, table.data-table td { border: 1px solid #cbd5e1; padding: 10px; text-align: left; vertical-align: top;}
        table.data-table th { background-color: #f1f5f9; color: #1e293b; font-weight: bold; width: 50px; text-align: center;}
        
        .signature-area { width: 100%; margin-top: 50px; border: none; }
        .signature-area td { border: none; text-align: center; width: 50%; padding: 10px; vertical-align: bottom;}
        .sign-name { font-weight: bold; text-decoration: underline; margin-top: 70px; }
    </style>
</head>
<body>

    @php
        $pathLogo = public_path('img/logo.png');
        $base64Logo = '';
        if(file_exists($pathLogo)) {
            $base64Logo = 'data:image/png;base64,' . base64_encode(file_get_contents($pathLogo));
        }
    @endphp

    <div class="header">
        @if($base64Logo)
            <img src="{{ $base64Logo }}" class="header-logo" alt="Logo">
        @endif
        <h2>LAPORAN HARIAN (LH) KEPALA KANTOR</h2>
        <p>SYAFA GROUP - Manajemen Operasional Internal</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Tanggal Laporan</td>
            <td>: {{ \Carbon\Carbon::parse($lh->tanggal)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Dicetak Oleh</td>
            <td>: Manager Unit ({{ \Carbon\Carbon::now()->format('d/m/Y H:i') }})</td>
        </tr>
    </table>

    <div style="font-weight: bold; margin-bottom: 10px; font-size: 14px;">Rincian Kegiatan:</div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th style="text-align: left;">Deskripsi Kegiatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lh->kegiatan_list as $index => $kegiatan)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $kegiatan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" style="text-align: center; color: #64748b;">Tidak ada rincian kegiatan tercatat.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table class="signature-area">
        <tr>
            <td></td>
            <td>
                <p style="margin-bottom: 5px;">Mengetahui / Melaporkan,</p>
                <p style="margin-top: 0; font-size: 11px;">Kepala Kantor</p>
                <div class="sign-name">( ................................... )</div>
            </td>
        </tr>
    </table>

</body>
</html>