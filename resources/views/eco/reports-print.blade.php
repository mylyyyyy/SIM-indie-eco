<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Stok - Syafa Eco</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11pt; color: #333; -webkit-print-color-adjust: exact; }
        
        /* Header Laporan */
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; position: relative; }
        .logo { width: 80px; height: auto; position: absolute; left: 0; top: 0; }
        .title { font-size: 18pt; font-weight: bold; text-transform: uppercase; margin: 0; color: #059669; }
        .subtitle { font-size: 10pt; margin-top: 5px; }

        /* Container Lokasi */
        .project-section { margin-bottom: 30px; page-break-inside: avoid; }
        
        /* Info Lokasi Header */
        .project-header { 
            background-color: #f1f5f9; 
            padding: 10px; 
            border: 1px solid #cbd5e1; 
            border-bottom: none;
            display: flex; 
            justify-content: space-between;
            align-items: center;
        }
        .project-title { font-weight: bold; font-size: 12pt; color: #0f172a; text-transform: uppercase;}
        .project-meta { font-size: 10pt; color: #059669; font-weight: bold; }

        /* Tabel Laporan */
        table { width: 100%; border-collapse: collapse; font-size: 10pt; border: 1px solid #cbd5e1; }
        th { background-color: #e2e8f0; padding: 8px; text-align: left; font-weight: bold; border: 1px solid #cbd5e1; }
        td { padding: 8px; border: 1px solid #cbd5e1; vertical-align: top; }
        
        /* Teks Status */
        .text-in { color: #166534; font-weight: bold; } /* Hijau Tua */
        .text-out { color: #991b1b; font-weight: bold; } /* Merah Tua */

        /* Footer & Tanda Tangan */
        .footer { margin-top: 50px; text-align: right; font-size: 10pt; page-break-inside: avoid; }
        
        .signature-box { 
            display: inline-block; 
            text-align: center; 
            margin-top: 20px; 
            width: 250px;
            position: relative;
        }

        .sign-spacer {
            height: 100px;
            position: relative; 
            width: 100%;
        }

        .stempel-img {
            position: absolute;
            top: 50%; 
            left: 50%;
            transform: translate(-50%, -50%) rotate(-10deg); 
            width: 100px;
            opacity: 0.7; 
            z-index: 1; 
            mix-blend-mode: multiply;
        }

        .sign-name-area {
            position: relative;
            z-index: 2; 
        }
        
        .sign-line { 
            border-top: 1px solid #000; 
            font-weight: bold; 
            padding-top: 5px; 
            display: block;
        }

        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 20px; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; padding: 10px; background: #eee; border-radius: 8px;">
        <button onclick="window.close()" style="cursor:pointer; padding: 5px 15px; font-weight:bold; color:red;">Tutup Tab</button>
        <button onclick="window.print()" style="cursor:pointer; padding: 5px 15px; font-weight:bold; margin-left: 10px; color:green;">🖨️ Cetak Dokumen</button>
    </div>

    @php
        // 1. Logo
        $pathLogo = public_path('img/logo.png');
        $base64Logo = '';
        if(file_exists($pathLogo)) {
            $type = pathinfo($pathLogo, PATHINFO_EXTENSION);
            $data = file_get_contents($pathLogo);
            $base64Logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // 2. Stempel
        $pathStempel = public_path('img/stempel.PNG'); 
        $base64Stempel = '';
        if(file_exists($pathStempel)) {
            $typeS = pathinfo($pathStempel, PATHINFO_EXTENSION);
            $dataS = file_get_contents($pathStempel);
            $base64Stempel = 'data:image/' . $typeS . ';base64,' . base64_encode($dataS);
        }
    @endphp

    <div class="header">
        @if($base64Logo)
            <img src="{{ $base64Logo }}" class="logo">
        @endif
        <h1 class="title">PT. ECO SYAFA HARVEST</h1>
        <p class="subtitle">Rekapitulasi Stok Gudang & Toko Cabang</p>
        <p style="font-size: 9pt;">Tanggal Cetak: {{ date('d F Y, H:i') }}</p>
    </div>

    @forelse($locations as $loc)
        <div class="project-section">
            <div class="project-header">
                <div>
                    <div class="project-title">{{ $loc->name }}</div>
                    <div style="font-size: 9pt; color: #64748b;">
                        Tipe: {{ $loc->type == 'mill' ? 'Selep' : ($loc->type == 'warehouse' ? 'Gudang' : 'Toko') }}
                    </div>
                </div>
                <div class="project-meta">
                    Sisa Stok: {{ number_format($loc->current_stock) }} Kg
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th width="5%" style="text-align: center;">No</th>
                        <th width="20%">Tanggal & Waktu</th>
                        <th width="45%">Deskripsi / Catatan</th>
                        <th width="15%" style="text-align: center;">Aktivitas</th>
                        <th width="15%" style="text-align: right;">Jumlah (Kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loc->stockLogs as $index => $log)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $log->description }}</td>
                            <td style="text-align: center;" class="{{ $log->type == 'in' ? 'text-in' : 'text-out' }}">
                                {{ $log->type == 'in' ? 'MASUK' : 'KELUAR' }}
                            </td>
                            <td style="text-align: right; font-family: monospace; font-size: 11pt;">
                                {{ number_format($log->amount) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 15px; color: #999; font-style: italic;">
                                Belum ada riwayat aktivitas di lokasi ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @empty
        <div style="text-align: center; padding: 50px; border: 2px dashed #ccc; color: #999;">
            <h3>Tidak ada data lokasi/cabang.</h3>
        </div>
    @endforelse

    <div class="footer">
        <div class="signature-box">
            <p style="margin-bottom: 5px;">Diketahui Oleh,</p>
            
            <div class="sign-spacer">
                @if($base64Stempel)
                    <img src="{{ $base64Stempel }}" class="stempel-img" alt="Stempel">
                @endif
            </div>

            <div class="sign-name-area">
                <span class="sign-line">Admin Eco Syafa</span>
                <div style="font-size: 10pt; color: #555;">Divisi Pangan & Distribusi</div>
            </div>
        </div>
    </div>

</body>
</html>