<x-admin-layout>
    {{-- HEADER DENGAN TOMBOL EXCEL --}}
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Hasil Kunjungan Toko</h2>
            <p class="text-sm text-slate-500">Input data setoran dan rincian kunjungan operasional.</p>
        </div>
        
        {{-- TOMBOL DOWNLOAD EXCEL --}}
        <div>
            <a href="{{ route('keuangan-eco.visit-results.export') }}" target="_blank" 
               class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-teal-500/30 transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-file-excel"></i> Download Laporan Excel
            </a>
        </div>
    </div>

    @if(session('success'))
        <script>Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false });</script>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- KOLOM KIRI: Form Input --}}
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-fit sticky top-24">
            <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Input Hasil Kunjungan</h3>
            
            <form action="{{ route('keuangan-eco.visit-results.store') }}" method="POST" class="space-y-3">
                @csrf
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Hari</label>
                        <select name="hari" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white" required>
                            <option value="Senin">Senin</option><option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option><option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option><option value="Sabtu">Sabtu</option><option value="Minggu">Minggu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Toko</label>
                    <input type="text" name="nama_toko" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="Ketik nama toko..." required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Alamat</label>
                    <textarea name="alamat" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="Alamat toko..." required></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Sisa Awal (Pack)</label>
                        <input type="number" min="0" name="titip_sisa_awal_pack" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" value="0" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Harga (Rp)</label>
                        <input type="number" min="0" name="harga_rp" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" value="0" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Laku (Pack)</label>
                        <input type="number" min="0" name="laku_pack" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" value="0" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Sisa (Pack)</label>
                        <input type="number" min="0" name="sisa_pack" id="sisa_pack" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" value="0" oninput="calculateTotal()" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tambah (Pack)</label>
                        <input type="number" min="0" name="tambah_pack" id="tambah_pack" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" value="0" oninput="calculateTotal()" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Total (Pack)</label>
                        <input type="number" min="0" name="total_pack" id="total_pack" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-slate-100" value="0" readonly required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ket / Pembayaran</label>
                    <input type="text" name="keterangan_bayar" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" placeholder="Contoh: Lunas / Cash / Transfer">
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg shadow mt-2 transform hover:-translate-y-0.5 transition-all">Simpan Data</button>
            </form>
            
            <script>
                function calculateTotal() {
                    let sisa = parseInt(document.getElementById('sisa_pack').value) || 0;
                    let tambah = parseInt(document.getElementById('tambah_pack').value) || 0;
                    document.getElementById('total_pack').value = sisa + tambah;
                }
            </script>
        </div>

        {{-- KOLOM KANAN: Tabel Data --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Toko & Alamat</th>
                            <th class="px-4 py-3 text-center">Harga</th>
                            <th class="px-4 py-3 text-center">Laku</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($results as $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</div>
                                <div class="text-[10px] text-slate-500 uppercase">{{ $item->hari }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-slate-700">{{ $item->nama_toko }}</div>
                                <div class="text-[10px] text-slate-400 truncate max-w-[150px]" title="{{ $item->alamat }}">{{ $item->alamat }}</div>
                            </td>
                            <td class="px-4 py-3 text-center font-medium">Rp {{ number_format($item->harga_rp, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center font-bold text-emerald-600">{{ $item->laku_pack }} Pack</td>
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('keuangan-eco.visit-results.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:bg-red-50 p-1.5 rounded transition-colors"><i class="fas fa-trash text-xs"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-8 text-slate-400">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>