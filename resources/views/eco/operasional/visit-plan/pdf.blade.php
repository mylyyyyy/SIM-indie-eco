<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Plan Kunjungan Toko</title>
    <style>
        /* Gaya dasar teks dan halaman */
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; }
        
        /* Area Header */
        .header { 
            position: relative; 
            text-align: center; 
            margin-bottom: 25px; 
            border-bottom: 3px double #1e3a8a; 
            padding-bottom: 15px; 
            min-height: 70px; 
        }
        .header-logo { 
            position: absolute; 
            left: 0; 
            top: 0; 
            height: 60px; 
            width: auto; 
        }
        .header h2 { 
            margin: 0; 
            font-size: 18px; 
            text-transform: uppercase; 
            color: #1e3a8a; 
            letter-spacing: 1px;
            font-weight: bold;
        }
        .header p { 
            margin: 5px 0 0 0; 
            font-size: 10px; 
            color: #64748b; 
        }
        
        /* Area Tabel Data */
        table.data-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        table.data-table th, table.data-table td { 
            border: 1px solid #cbd5e1; 
            padding: 8px 6px; 
            text-align: center; 
        }
        table.data-table th { 
            background-color: #1e3a8a; 
            color: #ffffff;
            font-weight: bold; 
            text-transform: uppercase; 
            font-size: 10px;
            border: 1px solid #1e3a8a;
        }
        table.data-table tr:nth-child(even) { 
            background-color: #f8fafc; 
        }
        table.data-table td.text-left { text-align: left; }
        table.data-table td.text-right { text-align: right; }

        /* Area Tanda Tangan */
        table.signature-area { 
            width: 100%; 
            margin-top: 50px; 
            border: none; 
        }
        table.signature-area td { 
            border: none; 
            text-align: center; 
            width: 50%; 
            padding: 10px; 
            vertical-align: bottom;
        }
        
        /* Wrapper Stempel agar posisinya pas */
        .stempel-wrapper {
            position: relative;
            height: 80px;
            width: 100%;
        }
        .stempel-img {
            position: absolute;
            left: 50%;
            top: 0;
            margin-left: -45px; /* Setengah dari lebar stempel agar rata tengah */
            width: 90px;
            opacity: 0.85;
        }
        .sign-name { 
            font-weight: bold; 
            text-decoration: underline; 
            margin-top: 10px; 
        }
    </style>
</head>
<body>

    {{-- LOGIKA PHP UNTUK GAMBAR KE BASE64 --}}
    @php
        // 1. Proses Logo
        $pathLogo = public_path('img/logo.png');
        $base64Logo = '';
        if(file_exists($pathLogo)) {
            $typeL = pathinfo($pathLogo, PATHINFO_EXTENSION);
            $dataL = file_get_contents($pathLogo);
            $base64Logo = 'data:image/' . $typeL . ';base64,' . base64_encode($dataL);
        }

        // 2. Proses Stempel
        $pathStempel = public_path('img/stempel_indie.png');
        $base64Stempel = '';
        if(file_exists($pathStempel)) {
            $typeS = pathinfo($pathStempel, PATHINFO_EXTENSION);
            $dataS = file_get_contents($pathStempel);
            $base64Stempel = 'data:image/' . $typeS . ';base64,' . base64_encode($dataS);
        }
    @endphp

    <div class="header">
        @if($base64Logo)
            <img src="{{ $base64Logo }}" class="header-logo" alt="Logo">
        @endif
        
        <h2>RENCANA KUNJUNGAN TOKO</h2>
        <p>Divisi Operasional & Distribusi - SYAFA GROUP</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Toko</th>
                <th width="30%">Alamat</th>
                <th width="10%">Stok Awal<br>(Pack)</th>
                <th width="11%">Harga (Rp)</th>
                <th width="8%">Laku<br>(Pack)</th>
                <th width="8%">Sisa<br>(Pack)</th>
                <th width="8%">Tambah<br>(Pack)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($plans as $index => $plan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left font-bold">{{ $plan->nama_toko }}</td>
                <td class="text-left">{{ $plan->alamat }}</td>
                <td>{{ $plan->stok_awal }}</td>
                <td class="text-right">{{ number_format($plan->harga, 0, ',', '.') }}</td>
                <td>{{ $plan->laku_pack }}</td>
                <td>{{ $plan->sisa_pack }}</td>
                <td>{{ $plan->tambah_pack }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="padding: 20px; color: #64748b; font-style: italic;">Belum ada data rencana kunjungan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table class="signature-area">
        <tr>
            <td>
                <p style="margin-bottom: 5px;">Mengetahui,</p>
                <p style="margin-top: 0; color: #64748b; font-size: 10px;">Admin Kantor</p>
                
                <div class="stempel-wrapper">
                    {{-- Area kosong untuk tanda tangan Admin --}}
                </div>
                
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