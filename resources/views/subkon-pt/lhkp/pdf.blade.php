<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap LHKP Proyek</title>
    <style>
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 11px; 
            color: #333; 
            margin: 0;
            padding: 10px;
        }
        .header { 
            position: relative; 
            text-align: center; 
            margin-bottom: 25px; 
            border-bottom: 3px double #1e40af; 
            padding-bottom: 15px; 
            min-height: 60px; 
        }
        .header-logo { 
            position: absolute; 
            left: 0; 
            top: 0; 
            height: 50px; 
            width: auto; 
        }
        .header h2 { 
            margin: 0; 
            font-size: 16px; 
            text-transform: uppercase; 
            color: #1e40af; 
            letter-spacing: 1px; 
            font-weight: bold;
        }
        .header p { 
            margin: 5px 0 0 0; 
            font-size: 10px; 
            color: #64748b; 
            font-weight: bold;
        }
        
        .styled-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0; 
        }
        .styled-table thead tr { 
            background-color: #f1f5f9; 
            color: #1e293b; 
            text-align: left; 
        }
        .styled-table th, .styled-table td { 
            padding: 10px 8px; 
            border: 1px solid #cbd5e1; 
            vertical-align: top;
        }
        .styled-table th {
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        .styled-table tbody tr:nth-of-type(even) { 
            background-color: #f8fafc; 
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        
        /* CSS UNTUK TANDA TANGAN */
        .signature-table {
            width: 100%;
            margin-top: 30px;
            border: none;
        }
        .signature-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }
        .signature-box {
            text-align: center;
            width: 250px;
            float: right;
        }
        .stamp-image {
            height: 80px; 
            margin-top: 5px;
            margin-bottom: -15px; /* Mengurangi jarak agar seolah menimpa nama */
            opacity: 0.85;
        }

        .footer-note {
            margin-top: 40px;
            text-align: right;
            font-size: 10px;
            color: #64748b;
            font-style: italic;
        }
    </style>
</head>
<body>

    @php
        // Load Logo Syafa
        $pathLogo = public_path('img/logo.png');
        $base64Logo = '';
        if(file_exists($pathLogo)) {
            $base64Logo = 'data:image/png;base64,' . base64_encode(file_get_contents($pathLogo));
        }

        // Load Stempel Indie
        $pathStempel = public_path('img/stempel_indie.png');
        $base64Stempel = '';
        if(file_exists($pathStempel)) {
            $base64Stempel = 'data:image/png;base64,' . base64_encode(file_get_contents($pathStempel));
        }
    @endphp

    <div class="header">
        @if($base64Logo)
            <img src="{{ $base64Logo }}" class="header-logo" alt="Logo">
        @endif
        <h2>REKAPITULASI LAPORAN HARIAN KINERJA PROYEK (LHKP)</h2>
        <p>SYAFA GROUP - Divisi Konstruksi & Infrastruktur (Indie)</p>
    </div>

    <table class="styled-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="12%">Tanggal</th>
                <th width="15%">Tempat / Lokasi</th>
                <th width="18%">Pelaksana / Pegawai</th>
                <th width="12%">Divisi / NIP</th>
                <th width="38%">Progres Pekerjaan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lhkps as $index => $lhkp)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center"><strong>{{ \Carbon\Carbon::parse($lhkp->tanggal)->format('d/m/Y') }}</strong></td>
                <td>{{ $lhkp->tempat }}</td>
                <td>
                    <strong>{{ strtoupper($lhkp->nama_pegawai) }}</strong>
                </td>
                <td>
                    {{ $lhkp->divisi }}<br>
                    <span style="font-size: 9px; color: #64748b;">NIP: {{ $lhkp->nip }}</span>
                </td>
                <td class="text-left" style="line-height: 1.4;">
                    {!! nl2br(e($lhkp->progres_pekerjaan)) !!}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada data laporan LHKP pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- BAGIAN TANDA TANGAN & STEMPEL --}}
    <table class="signature-table">
        <tr>
            <td style="width: 60%;"></td> {{-- Kolom kosong untuk mendorong ke kanan --}}
            <td style="width: 40%;">
                <div class="signature-box">
                    <p style="margin: 0;">Mengetahui,</p>
                    <p style="margin: 2px 0 0 0; font-weight: bold;">Manager Proyek</p>
                    
                    {{-- Area Stempel & TTD Kosong --}}
                    @if($base64Stempel)
                        <img src="{{ $base64Stempel }}" class="stamp-image" alt="Stempel Indie">
                    @else
                        <br><br><br><br><br><br>
                    @endif
                    
                    {{-- Nama User Yang Sedang Mencetak --}}
                    <p style="margin: 2px; font-weight: bold; text-decoration: underline; text-transform: uppercase;">
                        {{ Auth::user()->name ?? 'NAMA MANAGER' }}
                    </p>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini dicetak secara otomatis dari Sistem Internal Syafa Group pada {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y H:i') }} WIB.
    </div>

</body>
</html>