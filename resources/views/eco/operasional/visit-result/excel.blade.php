<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <table>
        <tr>
            <td colspan="11" style="text-align: center; font-size: 16px; font-weight: bold;">HASIL KUNJUNGAN TOKO</td>
        </tr>
        <tr>
            <td colspan="11" style="text-align: center; font-size: 12px;">Divisi Operasional & Distribusi - SYAFA GROUP</td>
        </tr>
        <tr>
            <td colspan="11" style="text-align: center; font-size: 10px;">Diekspor pada: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</td>
        </tr>
        <tr><td colspan="11"></td></tr>

        <tr>
            <th style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold;">NO</th>
            <th style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold;">HARI</th>
            <th style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold;">TANGGAL</th>
            <th style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold;">NAMA TOKO</th>
            <th style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold;">ALAMAT</th>
            <th style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold;">TITIP / SISA AWAL</th>
            <th style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold;">HARGA (Rp)</th>
            <th style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold;">LAKU (Pack)</th>
            <th style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold;">SISA (Pack)</th>
            <th style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold;">TAMBAH (Pack)</th>
            <th style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold;">TOTAL (Pack)</th>
            <th style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold;">KETERANGAN / BAYAR</th>
        </tr>
        
        @foreach($results as $index => $item)
        <tr>
            <td style="border: 1px solid #000;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000;">{{ $item->hari }}</td>
            <td style="border: 1px solid #000;">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
            <td style="border: 1px solid #000;">{{ $item->nama_toko }}</td>
            <td style="border: 1px solid #000;">{{ $item->alamat }}</td>
            <td style="border: 1px solid #000;">{{ $item->titip_sisa_awal_pack }}</td>
            <td style="border: 1px solid #000;">{{ $item->harga_rp }}</td>
            <td style="border: 1px solid #000;">{{ $item->laku_pack }}</td>
            <td style="border: 1px solid #000;">{{ $item->sisa_pack }}</td>
            <td style="border: 1px solid #000;">{{ $item->tambah_pack }}</td>
            <td style="border: 1px solid #000; font-weight: bold;">{{ $item->total_pack }}</td>
            <td style="border: 1px solid #000;">{{ $item->keterangan_bayar }}</td>
        </tr>
        @endforeach

        <tr><td colspan="11"></td></tr>
        <tr><td colspan="11"></td></tr>

        {{-- KOLOM TANDA TANGAN DI EXCEL --}}
        <tr>
            <td colspan="2"></td>
            <td colspan="3" style="text-align: center;">Mengetahui,</td>
            <td colspan="3"></td>
            <td colspan="3" style="text-align: center;">Mengetahui,</td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="3" style="text-align: center;"><b>Kepala Kantor</b></td>
            <td colspan="3"></td>
            <td colspan="3" style="text-align: center;"><b>Manager Unit</b></td>
        </tr>
        <tr><td colspan="11"></td></tr>
        <tr><td colspan="11"></td></tr>
        <tr><td colspan="11"></td></tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="3" style="text-align: center; text-decoration: underline;">_______________________</td>
            <td colspan="3"></td>
            <td colspan="3" style="text-align: center; text-decoration: underline;">_______________________</td>
        </tr>
    </table>
</body>
</html>