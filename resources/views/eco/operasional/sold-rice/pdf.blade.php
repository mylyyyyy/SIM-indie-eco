<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Beras Terjual</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; }
        .header { position: relative; text-align: center; margin-bottom: 25px; border-bottom: 3px double #1e3a8a; padding-bottom: 15px; min-height: 70px; }
        .header-logo { position: absolute; left: 0; top: 0; height: 60px; width: auto; }
        .header h2 { margin: 0; font-size: 18px; text-transform: uppercase; color: #1e3a8a; letter-spacing: 1px; font-weight: bold;}
        .header p { margin: 5px 0 0 0; font-size: 10px; color: #64748b; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data-table th, table.data-table td { border: 1px solid #cbd5e1; padding: 8px 6px; text-align: center; }
        table.data-table th { background-color: #1e3a8a; color: #ffffff; font-weight: bold; text-transform: uppercase; font-size: 10px; border: 1px solid #1e3a8a;}
        table.data-table tr:nth-child(even) { background-color: #f8fafc; }
        table.data-table td.text-left { text-align: left; }

        table.signature-area { width: 100%; margin-top: 50px; border: none; }
        table.signature-area td { border: none; text-align: center; width: 50%; padding: 10px; vertical-align: bottom;}
        .stempel-wrapper { position: relative; height: 80px; width: 100%; }
        .stempel-img { position: absolute; left: 50%; top: 0; margin-left: -45px; width: 90px; opacity: 0.85;}
        .sign-name { font-weight: bold; text-decoration: underline; margin-top: 10px; }
    </style>
</head>
<body>

    @php
        $pathLogo = public_path('img/logo.png');
        $base64Logo = '';
        if(file_exists($pathLogo)) {
            $base64Logo = 'data:image/png;base64,' . base64_encode(file_get_contents($pathLogo));
        }

        $pathStempel = public_path('img/stempel.png');
        $base64Stempel = '';
        if(file_exists($pathStempel)) {
            $base64Stempel = 'data:image/png;base64,' . base64_encode(file_get_contents($pathStempel));
        }
    @endphp

    <div class="header">
        @if($base64Logo)
            <img src="{{ $base64Logo }}" class="header-logo" alt="Logo">
        @endif
        
        <h2>DAFTAR BERAS TERJUAL</h2>
        <p>Divisi Operasional & Distribusi - SYAFA GROUP</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Tanggal</th>
                <th width="20%">Tempat</th>
                <th width="35%">Nama Toko</th>
                <th width="10%">Kunjungan</th>
                <th width="10%">Ukuran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($soldRices as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td class="text-left">{{ $item->tempat }}</td>
                <td class="text-left font-bold">{{ $item->nama_toko }}</td>
                <td>Ke-{{ $item->kunjungan_ke }}</td>
                <td style="font-weight: bold; color: #059669;">{{ $item->ukuran }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 20px; color: #64748b; font-style: italic;">Belum ada data beras terjual.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table class="signature-area">
        <tr>
            <td>
                <p style="margin-bottom: 5px;">Mengetahui,</p>
                <p style="margin-top: 0; color: #64748b; font-size: 10px;">Admin Kantor</p>
                <div class="stempel-wrapper"></div>
                <div class="sign-name">_______________________</div>
            </td>
            <td>
                <p style="margin-bottom: 5px;">Mengetahui,</p>
                <p style="margin-top: 0; color: #64748b; font-size: 10px;">Manager Unit</p>
                <div class="stempel-wrapper">
                    @if($base64Stempel)
                        <img src="{{ $base64Stempel }}" class="stempel-img" alt="Stempel">
                    @endif
                </div>
                <div class="sign-name">_______________________</div>
            </td>
        </tr>
    </table>

</body>
</html>