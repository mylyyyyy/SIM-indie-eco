<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Laporan Proyek - Syafa Group</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11pt; color: #333; -webkit-print-color-adjust: exact; }
        
        /* Header Laporan */
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; position: relative; }
        .logo { width: 80px; height: auto; position: absolute; left: 0; top: 0; }
        .title { font-size: 18pt; font-weight: bold; text-transform: uppercase; margin: 0; color: #1e3a8a; }
        .subtitle { font-size: 10pt; margin-top: 5px; }

        /* Container Proyek */
        .project-section { margin-bottom: 30px; page-break-inside: avoid; }
        
        /* Info Proyek Header */
        .project-header { 
            background-color: #f1f5f9; 
            padding: 10px; 
            border: 1px solid #cbd5e1; 
            border-bottom: none;
            display: flex; 
            justify-content: space-between;
            align-items: center;
        }
        .project-title { font-weight: bold; font-size: 12pt; color: #0f172a; }
        .project-meta { font-size: 9pt; color: #64748b; }

        /* Tabel Laporan */
        table { width: 100%; border-collapse: collapse; font-size: 10pt; border: 1px solid #cbd5e1; }
        th { background-color: #e2e8f0; padding: 8px; text-align: left; font-weight: bold; border: 1px solid #cbd5e1; }
        td { padding: 8px; border: 1px solid #cbd5e1; vertical-align: top; }
        
        /* Badge Status */
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 8pt; font-weight: bold; text-transform: uppercase; }
        .badge-approved { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-rejected { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .badge-pending { background-color: #ffedd5; color: #9a3412; border: 1px solid #fed7aa; }

        /* Footer & Tanda Tangan */
        .footer { margin-top: 50px; text-align: right; font-size: 10pt; page-break-inside: avoid; }
        
        /* === PERBAIKAN CSS TANDA TANGAN DI SINI === */
        .signature-box { 
            display: inline-block; 
            text-align: center; 
            margin-top: 20px; 
            width: 250px;
            position: relative;
        }

        /* Area Kosong untuk Tanda Tangan Basah & Stempel */
        .sign-spacer {
            height: 100px; /* Tinggi area kosong diperbesar agar stempel muat */
            position: relative; /* Agar stempel bisa diposisikan absolute di dalamnya */
            width: 100%;
        }

        /* Posisi Stempel: Di tengah-tengah Area Kosong */
        .stempel-img {
            position: absolute;
            top: 50%; 
            left: 50%;
            transform: translate(-50%, -50%) rotate(-10deg); /* Tepat di tengah spacer */
            width: 100px;
            opacity: 0.7; 
            z-index: 1; /* Di atas kertas, di bawah tanda tangan basah nanti */
            mix-blend-mode: multiply;
        }

        /* Garis & Nama: Dipastikan di bawah spacer */
        .sign-name-area {
            position: relative;
            z-index: 2; /* Agar teks nama selalu di atas stempel jika tersenggol */
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
        <button onclick="window.history.back()" style="cursor:pointer; padding: 5px 15px; font-weight:bold;">&larr; Kembali ke Dashboard</button>
        <button onclick="window.print()" style="cursor:pointer; padding: 5px 15px; font-weight:bold; margin-left: 10px;">Cetak Dokumen</button>
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

        // 2. Stempel (Sesuaikan nama file: stempel.jpeg atau stempel.png)
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
        <h1 class="title">SYAFA GROUP</h1>
        <p class="subtitle">Rekapitulasi Laporan Harian Pekerjaan Proyek</p>
        <p style="font-size: 9pt;">Tanggal Cetak: {{ date('d F Y, H:i') }}</p>
    </div>

    @forelse($projects as $project)
        <div class="project-section">
            <div class="project-header">
                <div>
                    <div class="project-title">{{ $project->project_name }}</div>
                    <div class="project-meta">Lokasi: {{ $project->location }}</div>
                </div>
                <div class="project-meta">
                    Status Proyek: <strong>{{ ucfirst($project->status) }}</strong>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th width="15%">Tanggal</th>
                        <th width="20%">Vendor Pelaksana</th>
                        <th width="40%">Deskripsi Pekerjaan</th>
                        <th width="10%" style="text-align: center;">Prog.</th>
                        <th width="15%" style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($project->reports as $report)
                        <tr>
                            <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <strong>{{ $report->user->name }}</strong><br>
                                <span style="font-size: 8pt; color: #666;">{{ $report->user->company_name ?? '-' }}</span>
                            </td>
                            <td>
                                {{ $report->work_description }}
                                @if($report->admin_note)
                                    <br><i style="font-size: 8pt; color: #666;">Catatan Admin: "{{ $report->admin_note }}"</i>
                                @endif
                            </td>
                            <td style="text-align: center; font-weight: bold;">{{ $report->progress_percentage }}%</td>
                            <td style="text-align: center;">
                                @if($report->status == 'approved')
                                    <span class="badge badge-approved">Disetujui</span>
                                    @if($report->rating)<br><span style="font-size:8pt; color:#166534;">Nilai: {{ $report->rating }}</span>@endif
                                @elseif($report->status == 'rejected')
                                    <span class="badge badge-rejected">Ditolak</span>
                                @else
                                    <span class="badge badge-pending">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 15px; color: #999; font-style: italic;">
                                Belum ada laporan masuk untuk proyek ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @empty
        <div style="text-align: center; padding: 50px; border: 2px dashed #ccc; color: #999;">
            <h3>Tidak ada data proyek.</h3>
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
                <span class="sign-line">Admin Syafa Group</span>
                <div style="font-size: 10pt; color: #555;">Internal Management</div>
            </div>
        </div>
    </div>

</body>
</html>