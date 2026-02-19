<x-admin-layout>
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Plan Kunjungan Toko</h2>
            <p class="text-sm text-slate-500">Kelola rencana kunjungan dan stok awal toko.</p>
        </div>
        <div>
            <a href="{{ route('eco.visit-plans.export') }}" target="_blank" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl font-bold text-sm shadow-md transition-all flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> Unduh PDF
            </a>
        </div>
    </div>

    {{-- Alert Sukses --}}
    @if(session('success'))
        <script>
            Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false });
        </script>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- KOLOM KIRI: Form Input (1/3 Lebar) --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sticky top-24">
                <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Tambah Plan Kunjungan</h3>
                
                <form action="{{ route('eco.visit-plans.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    {{-- INPUT MANUAL NAMA TOKO (PERUBAHAN DI SINI) --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Toko</label>
                        <input type="text" name="nama_toko" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all" placeholder="Ketik nama toko..." required>
                    </div>

                    {{-- ALAMAT --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Alamat (Sesuai Kunjungan)</label>
                        <textarea name="alamat" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="Tuliskan alamat spesifik kunjungan..." required></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Stok Awal (Pack)</label>
                            <input type="number" name="stok_awal" value="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Harga (Rp)</label>
                            <input type="number" name="harga" value="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Laku</label>
                            <input type="number" name="laku_pack" value="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Sisa</label>
                            <input type="number" name="sisa_pack" value="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tambah</label>
                            <input type="number" name="tambah_pack" value="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg shadow transition-all mt-2 transform hover:-translate-y-0.5">
                        Simpan Data
                    </button>
                </form>
            </div>
        </div>

        {{-- KOLOM KANAN: Tabel Data (2/3 Lebar) --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold">
                            <tr>
                                <th class="px-4 py-3">Toko & Alamat</th>
                                <th class="px-4 py-3 text-center">Stok Awal</th>
                                <th class="px-4 py-3 text-right">Harga</th>
                                <th class="px-4 py-3 text-center">Laku</th>
                                <th class="px-4 py-3 text-center">Sisa</th>
                                <th class="px-4 py-3 text-center">Tambah</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($plans as $plan)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-800">{{ $plan->nama_toko }}</div>
                                    <div class="text-[10px] text-slate-400 truncate max-w-[150px]" title="{{ $plan->alamat }}">{{ $plan->alamat }}</div>
                                </td>
                                <td class="px-4 py-3 text-center font-bold">{{ $plan->stok_awal }}</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($plan->harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center text-emerald-600 font-bold">{{ $plan->laku_pack }}</td>
                                <td class="px-4 py-3 text-center text-orange-500 font-bold">{{ $plan->sisa_pack }}</td>
                                <td class="px-4 py-3 text-center text-blue-600 font-bold">{{ $plan->tambah_pack }}</td>
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('eco.visit-plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-slate-400">Belum ada data plan kunjungan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>