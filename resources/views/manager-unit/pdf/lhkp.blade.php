<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LHKP - {{ $lhkp->nama_pegawai }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { position: relative; text-align: center; margin-bottom: 30px; border-bottom: 3px double #047857; padding-bottom: 15px; min-height: 70px; }
        .header-logo { position: absolute; left: 0; top: 0; height: 60px; width: auto; }
        .header h2 { margin: 0; font-size: 18px; text-transform: uppercase; color: #047857; letter-spacing: 1px; font-weight: bold;}
        .header p { margin: 5px 0 0 0; font-size: 11px; color: #64748b; }
        
        .info-table { width: 100%; margin-bottom: 25px; border-collapse: collapse; }
        .info-table td { padding: 8px 5px; border-bottom: 1px dashed #e2e8f0; }
        .info-table td.label { font-weight: bold; width: 150px; color: #475569; }

        .content-box { border: 1px solid #cbd5e1; padding: 15px; background-color: #f8fafc; min-height: 100px; line-height: 1.6; text-align: justify; }
        
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
        <h2>LAPORAN HARIAN KINERJA PEGAWAI (LHKP)</h2>
        <p>SYAFA GROUP - Divisi Operasional & Distribusi</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Pegawai</td>
            <td>: <strong>{{ strtoupper($lhkp->nama_pegawai) }}</strong></td>
        </tr>
        <tr>
            <td class="label">NIP</td>
            <td>: {{ $lhkp->nip }}</td>
        </tr>
        <tr>
            <td class="label">Divisi / Bagian</td>
            <td>: {{ $lhkp->divisi }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Laporan</td>
            <td>: {{ \Carbon\Carbon::parse($lhkp->tanggal)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Lokasi / Tempat</td>
            <td>: {{ $lhkp->tempat }}</td>
        </tr>
    </table>

    <div style="font-weight: bold; margin-bottom: 10px; font-size: 14px; color: #047857;">Progres & Rincian Pekerjaan:</div>
    
    <div class="content-box">
        {!! nl2br(e($lhkp->progres_pekerjaan)) !!}
    </div>

    <table class="signature-area">
        <tr>
            <td>
                <p style="margin-bottom: 5px;">Dilaporkan Oleh,</p>
                <p style="margin-top: 0; font-size: 11px;">Pegawai Terkait</p>
                <div class="sign-name">{{ $lhkp->nama_pegawai }}</div>
            </td>
            <td>
                <p style="margin-bottom: 5px;">Mengetahui,</p>
                <p style="margin-top: 0; font-size: 11px;">Manager Unit</p>
                <div class="sign-name">( ................................... )</div>
            </td>
        </tr>
    </table>

</body>
</html>