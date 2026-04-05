<x-admin-layout>
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Laporan Pemasukan Kantor</h2>
            <p class="text-sm text-slate-500">Input data pendapatan penjualan plastik cabang {{ Auth::user()->company_name ?? 'Pusat' }}.</p>
        </div>
    </div>

    @if(session('success'))
        <script>Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });</script>
    @endif
    
    @if($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 border border-red-100 text-sm"><i class="fas fa-exclamation-triangle mr-2"></i> {{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- KOLOM KIRI: Form Input --}}
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-fit sticky top-24">
            <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Input Pemasukan Baru</h3>
            
            <form action="{{ route('eco.incomes.store') }}" method="POST" class="space-y-3">
                @csrf
                
                {{-- NAMA CABANG OTOMATIS --}}
                <div class="bg-blue-50 border border-blue-100 p-3 rounded-lg mb-3">
                    <label class="block text-[10px] font-bold text-blue-600 uppercase mb-1">Cabang (Otomatis)</label>
                    <div class="font-black text-blue-900">{{ Auth::user()->company_name ?? 'Pusat' }}</div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Hari</label>
                        <select name="hari" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white" required>
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                                <option value="{{ $h }}">{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Toko</label>
                    <input type="text" name="nama_toko" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" placeholder="Ketik nama toko..." required>
                </div>

                <div class="p-3 border border-slate-200 rounded-lg bg-slate-50">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Jumlah Plastik Terjual</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Ukuran 2.5 Kg</label>
                            <input type="number" min="0" name="jumlah_plastik_2_5kg" id="qty_25" class="w-full px-3 py-2 border border-slate-300 rounded text-sm" value="0" oninput="calculateTotal()" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Ukuran 5 Kg</label>
                            <input type="number" min="0" name="jumlah_plastik_5kg" id="qty_50" class="w-full px-3 py-2 border border-slate-300 rounded text-sm" value="0" oninput="calculateTotal()" required>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Harga Jual per Plastik (Rp)</label>
                    <input type="number" min="0" name="harga_jual_per_plastik" id="harga_satuan" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" value="0" oninput="calculateTotal()" required>
                </div>

                {{-- TOTAL OTOMATIS --}}
                <div class="bg-amber-50 p-3 rounded-lg border border-amber-100">
                    <label class="block text-xs font-bold text-amber-700 uppercase mb-1">Total Harga Jual</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 font-bold text-amber-700">Rp</span>
                        <input type="text" id="total_tampil" class="w-full pl-9 pr-3 py-2 border border-amber-200 rounded-lg text-sm bg-amber-100/50 font-black text-amber-800 cursor-not-allowed" value="0" readonly>
                    </div>
                    <p class="text-[10px] text-amber-600 mt-1 italic">*Otomatis (Total Qty × Harga)</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" placeholder="Contoh: Laku 50, sisa 10..."></textarea>
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg shadow mt-2">Simpan Pemasukan</button>
            </form>
            
            <script>
                function calculateTotal() {
                    let qty25 = parseInt(document.getElementById('qty_25').value) || 0;
                    let qty50 = parseInt(document.getElementById('qty_50').value) || 0;
                    let harga = parseInt(document.getElementById('harga_satuan').value) || 0;
                    let totalHarga = (qty25 + qty50) * harga;
                    document.getElementById('total_tampil').value = new Intl.NumberFormat('id-ID').format(totalHarga);
                }
            </script>
        </div>

        {{-- KOLOM KANAN: Tabel Data --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold">
                        <tr>
                            <th class="px-4 py-3 text-center">No</th>
                            <th class="px-4 py-3">Tgl & Cabang</th>
                            <th class="px-4 py-3">Toko</th>
                            <th class="px-4 py-3 text-center">Qty (2.5 / 5)</th>
                            <th class="px-4 py-3 text-right">Pendapatan</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($incomes as $index => $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 text-center font-bold">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 align-top">
                                <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</div>
                                <div class="text-[10px] text-blue-600 font-bold uppercase">{{ $item->nama_cabang }}</div>
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="font-bold text-slate-700">{{ $item->nama_toko }}</div>
                                <div class="text-[10px] text-slate-400 truncate max-w-[120px]">{{ $item->keterangan ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 text-center align-top">
                                <span class="text-xs bg-slate-100 px-2 py-1 rounded">{{ $item->jumlah_plastik_2_5kg }}</span> / 
                                <span class="text-xs bg-slate-100 px-2 py-1 rounded">{{ $item->jumlah_plastik_5kg }}</span>
                            </td>
                            <td class="px-4 py-3 text-right align-top">
                                <div class="font-black text-emerald-600">Rp {{ number_format($item->total_harga_jual, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-4 py-3 text-center align-top">
                                <form action="{{ route('eco.incomes.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:bg-red-50 p-1.5 rounded transition-colors"><i class="fas fa-trash text-xs"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-8 text-slate-400">Belum ada data pemasukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>