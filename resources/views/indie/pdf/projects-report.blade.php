<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Proyek Indie</title>
    <style>
        /* Gaya dasar untuk tampilan di layar */
        body {
            font-family: Arial, sans-serif;
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
        }

        /* Header Laporan */
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #4f46e5; padding-bottom: 10px;}
        .header h2 { margin: 0; color: #1e1e2f; text-transform: uppercase; letter-spacing: 1px;}
        .header p { margin: 5px 0 0 0; color: #64748b; font-size: 10px;}
        
        /* Tabel */
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; vertical-align: top;}
        th { background-color: #4f46e5; color: white; font-weight: bold; font-size: 11px; text-transform: uppercase;}
        tr:nth-child(even) { background-color: #f8fafc; }
        
        .text-center { text-align: center; }
        .status-completed { color: #059669; font-weight: bold; }
        .status-active { color: #2563eb; font-weight: bold; }

        /* Tombol print manual */
        .action-buttons { text-align: center; margin-bottom: 20px; }
        .btn-print { background-color: #4f46e5; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; font-weight: bold; }
        .btn-close { background-color: #ef4444; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; font-weight: bold; margin-left: 10px;}

        /* PENTING: Pengaturan khusus saat dicetak (Print) */
        @media print {
            body { background-color: white; padding: 0; }
            .paper { box-shadow: none; width: auto; min-height: auto; padding: 0; margin: 0; }
            .action-buttons { display: none; } /* Sembunyikan tombol saat dicetak */
            @page { size: A4 portrait; margin: 15mm; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="action-buttons no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
        <button class="btn-close" onclick="window.close()">Tutup Halaman</button>
    </div>

    <div class="paper">
        <div class="header">
            <h2>Laporan Data Proyek</h2>
            <p>Divisi Syafa Indie (Infrastruktur, Gedung & Komersial)</p>
            <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y, H:i') }} WIB</p>
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
                    <td colspan="5" class="text-center">Tidak ada data proyek.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div style="margin-top: 50px; text-align: right;">
            <p>Mengetahui,</p>
            <br><br><br>
            <p style="font-weight: bold; text-decoration: underline;">Admin Indie</p>
            <p style="font-size: 10px; color: #666; margin-top:-10px;">Syafa Group</p>
        </div>
    </div>

</body>
</html>