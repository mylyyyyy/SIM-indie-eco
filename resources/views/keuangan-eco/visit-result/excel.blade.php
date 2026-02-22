<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    {{-- JUDUL LAPORAN --}}
    <table>
        <tr>
            <td colspan="12" style="text-align: center; font-size: 16px; font-weight: bold; height: 30px;">
                LAPORAN HASIL KUNJUNGAN TOKO
            </td>
        </tr>
        <tr>
            <td colspan="12" style="text-align: center; font-size: 12px;">
                Divisi Operasional & Distribusi - SYAFA GROUP
            </td>
        </tr>
        <tr>
            <td colspan="12" style="text-align: center; font-size: 10px; font-style: italic;">
                Periode Data: 
                {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d M Y') : 'Awal' }} 
                s/d 
                {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d M Y') : 'Sekarang' }}
            </td>
        </tr>
        <tr><td colspan="12"></td></tr> {{-- Spasi Kosong --}}
    </table>

    {{-- TABEL DATA --}}
    <table border="1">
        <thead>
            <tr>
                <th style="background-color: #eeeeee; font-weight: bold; text-align: center; width: 50px;">No</th>
                <th style="background-color: #eeeeee; font-weight: bold; text-align: center; width: 80px;">Hari</th>
                <th style="background-color: #eeeeee; font-weight: bold; text-align: center; width: 100px;">Tanggal</th>
                <th style="background-color: #eeeeee; font-weight: bold; text-align: center; width: 200px;">Nama Toko</th>
                <th style="background-color: #eeeeee; font-weight: bold; text-align: center; width: 250px;">Alamat</th>
                <th style="background-color: #eeeeee; font-weight: bold; text-align: center;">Sisa Awal</th>
                <th style="background-color: #eeeeee; font-weight: bold; text-align: center; width: 120px;">Setoran (Rp)</th>
                <th style="background-color: #eeeeee; font-weight: bold; text-align: center;">Laku</th>
                <th style="background-color: #eeeeee; font-weight: bold; text-align: center;">Sisa</th>
                <th style="background-color: #eeeeee; font-weight: bold; text-align: center;">Tambah</th>
                <th style="background-color: #eeeeee; font-weight: bold; text-align: center;">Total</th>
                <th style="background-color: #eeeeee; font-weight: bold; text-align: center; width: 200px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="text-align: center;">{{ $item->hari }}</td>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $item->nama_toko }}</td>
                <td>{{ $item->alamat }}</td>
                <td style="text-align: center;">{{ $item->titip_sisa_awal_pack }}</td>
                <td style="text-align: right;">{{ $item->harga_rp }}</td>
                <td style="text-align: center;">{{ $item->laku_pack }}</td>
                <td style="text-align: center;">{{ $item->sisa_pack }}</td>
                <td style="text-align: center;">{{ $item->tambah_pack }}</td>
                <td style="text-align: center; font-weight: bold;">{{ $item->total_pack }}</td>
                <td>{{ $item->keterangan_bayar }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- AREA TANDA TANGAN --}}
    <table>
        <tr><td colspan="12" height="30"></td></tr> {{-- Spasi --}}
        
        <tr>
            {{-- KIRI: KEPALA KANTOR --}}
            <td colspan="4" style="text-align: center;">
                Mengetahui,
            </td>
            
            <td colspan="4"></td> {{-- Spasi Tengah --}}

            {{-- KANAN: MANAGER UNIT --}}
            <td colspan="4" style="text-align: center;">
                Menyetujui & Memeriksa,
            </td>
        </tr>

        <tr>
            <td colspan="4" style="text-align: center; font-weight: bold;">
                Kepala Kantor
            </td>
            
            <td colspan="4"></td>

            <td colspan="4" style="text-align: center; font-weight: bold;">
                Manager Unit
            </td>
        </tr>

        {{-- Spasi untuk Tanda Tangan & Stempel --}}
        <tr>
            <td colspan="4" height="80"></td>
            <td colspan="4"></td>
            <td colspan="4" height="80" style="text-align: center; vertical-align: middle; color: #999999;">
                [TEMPAT STEMPEL]
            </td>
        </tr>

        <tr>
            <td colspan="4" style="text-align: center; font-weight: bold; text-decoration: underline;">
                ( ..................................... )
            </td>
            
            <td colspan="4"></td>

            <td colspan="4" style="text-align: center; font-weight: bold; text-decoration: underline;">
                ( ..................................... )
            </td>
        </tr>
    </table>
</body>
</html>