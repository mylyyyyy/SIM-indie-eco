<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Selep</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; }
        .header { position: relative; text-align: center; margin-bottom: 25px; border-bottom: 3px double #1e3a8a; padding-bottom: 15px; min-height: 70px; }
        .header-logo { position: absolute; left: 0; top: 0; height: 60px; width: auto; }
        .header h2 { margin: 0; font-size: 18px; text-transform: uppercase; color: #1e3a8a; letter-spacing: 1px; font-weight: bold;}
        .header p { margin: 5px 0 0 0; font-size: 10px; color: #64748b; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data-table th, table.data-table td { border: 1px solid #cbd5e1; padding: 8px 6px; text-align: center; vertical-align: middle;}
        table.data-table th { background-color: #1e3a8a; color: #ffffff; font-weight: bold; text-transform: uppercase; font-size: 10px; border: 1px solid #1e3a8a;}
        table.data-table tr:nth-child(even) { background-color: #f8fafc; }
        
        /* Area Tanda Tangan dibagi 2 */
        table.signature-area { width: 100%; margin-top: 50px; border: none; }
        table.signature-area td { border: none; text-align: center; width: 50%; padding: 10px; vertical-align: bottom;}
        .stempel-wrapper { position: relative; height: 80px; width: 100%; }
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
    @endphp

    <div class="header">
        @if($base64Logo)
            <img src="{{ $base64Logo }}" class="header-logo" alt="Logo">
        @endif
        
        <h2>LAPORAN SELEP BERAS</h2>
        <p>Divisi Operasional & Distribusi - SYAFA GROUP</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="25%">Tanggal</th>
                <th width="25%">Bulan Laporan</th>
                <th width="20%">Ambil Beras (Kg)</th>
                <th width="20%">Jumlah (Pack)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $total_kg = 0; 
                $total_pack = 0; 
            @endphp
            @forelse($reports as $index => $report)
                @php
                    $total_kg += $report->ambil_beras_kg;
                    $total_pack += $report->jumlah_pack;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($report->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ strtoupper($report->bulan) }}</td>
                    <td style="font-weight: bold;">{{ number_format($report->ambil_beras_kg, 2, ',', '.') }}</td>
                    <td style="font-weight: bold; color:#1e3a8a;">{{ number_format($report->jumlah_pack, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 20px; color: #64748b; font-style: italic;">Belum ada data laporan selep.</td>
                </tr>
            @endforelse
            {{-- Tambahan baris total agar laporan lebih informatif --}}
            @if($reports->count() > 0)
                <tr style="background-color: #e2e8f0; font-weight: bold;">
                    <td colspan="3" style="text-align: right; padding-right: 15px;">TOTAL KESELURUHAN</td>
                    <td>{{ number_format($total_kg, 2, ',', '.') }} Kg</td>
                    <td style="color:#1e3a8a;">{{ number_format($total_pack, 0, ',', '.') }} Pack</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- DUA TANDA TANGAN (Kiri: Admin Lapangan, Kanan: Kepala Kantor) --}}
    <table class="signature-area">
        <tr>
            <td>
                <p style="margin-bottom: 5px;">Dibuat Oleh,</p>
                <p style="margin-top: 0; color: #64748b; font-size: 10px;">Admin Lapangan</p>
                <div class="stempel-wrapper"></div>
                <div class="sign-name">_______________________</div>
            </td>
            <td>
                <p style="margin-bottom: 5px;">Mengetahui,</p>
                <p style="margin-top: 0; color: #64748b; font-size: 10px;">Kepala Kantor</p>
                <div class="stempel-wrapper"></div>
                <div class="sign-name">_______________________</div>
            </td>
        </tr>
    </table>

</body>
</html>