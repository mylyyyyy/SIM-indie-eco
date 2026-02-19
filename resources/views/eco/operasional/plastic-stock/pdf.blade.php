<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Jumlah Plastik</title>
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
        
        <h2>LAPORAN STOK PLASTIK / PACKING</h2>
        <p>Divisi Operasional & Distribusi - SYAFA GROUP</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Tempat</th>
                <th width="15%">Tanggal</th>
                <th width="25%">Jenis Plastik / Packing</th>
                <th width="11%">Stok Awal</th>
                <th width="12%">Stok Sisa</th>
                <th width="12%">Terpakai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stocks as $index => $stock)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left font-bold">{{ $stock->tempat }}</td>
                <td>{{ \Carbon\Carbon::parse($stock->tanggal)->format('d/m/Y') }}</td>
                <td class="text-left">{{ $stock->jenis_plastik }}</td>
                <td>{{ number_format($stock->stok_awal) }}</td>
                <td>{{ number_format($stock->stok_sisa) }}</td>
                <td style="font-weight: bold; color: #059669;">{{ number_format($stock->stok_awal - $stock->stok_sisa) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding: 20px; color: #64748b; font-style: italic;">Belum ada data stok plastik.</td>
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
                <p style="margin-top: 0; color: #64748b; font-size: 10px;">Kepala Kantor</p>
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