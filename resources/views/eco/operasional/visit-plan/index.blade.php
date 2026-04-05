<x-admin-layout>
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Plan Kunjungan Toko</h2>
            <p class="text-sm text-slate-500">Kelola rencana kunjungan dan stok awal toko di cabang {{ Auth::user()->company_name ?? 'Pusat' }}.</p>
        </div>
        
        {{-- Sembunyikan tombol PDF untuk Admin Kantor (Sesuai BRD sebelumnya) --}}
        @if(Auth::user()->role !== 'admin_kantor_eco')
        <div>
            <a href="{{ route('eco.visit-plans.export') }}" target="_blank" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl font-bold text-sm shadow-md transition-all flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> Unduh PDF
            </a>
        </div>
        @endif
    </div>

    {{-- Alert Sukses --}}
    @if(session('success'))
        <script>
            Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });
        </script>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- KOLOM KIRI: Form Input (1/3 Lebar) --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sticky top-24">
                <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Tambah Plan Kunjungan</h3>
                
                <form action="{{ route('eco.visit-plans.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    {{-- INFO OTOMATIS TANGGAL --}}
                    <div class="bg-blue-50 border border-blue-100 p-3 rounded-lg flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-200 text-blue-700 flex items-center justify-center shrink-0">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-blue-600 font-bold uppercase">Tanggal Plan</p>
                            <p class="text-xs font-bold text-blue-900">{{ \Carbon\Carbon::now()->format('d M Y') }}</p>
                            <p class="text-[9px] text-blue-500 italic">Disimpan otomatis oleh sistem</p>
                        </div>
                    </div>

                    {{-- INPUT MANUAL NAMA TOKO --}}
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
                            <input type="number" name="stok_awal" min="0" value="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Harga (Rp)</label>
                            <input type="number" name="harga" min="0" value="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Laku</label>
                            <input type="number" name="laku_pack" min="0" value="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-emerald-600 font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Sisa</label>
                            <input type="number" name="sisa_pack" min="0" value="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-orange-600 font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tambah</label>
                            <input type="number" name="tambah_pack" min="0" value="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-blue-600 font-bold">
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
                                <th class="px-4 py-3 min-w-[200px]">Toko & Alamat</th>
                                {{-- TAMBAHAN HEADER TANGGAL PLAN --}}
                                <th class="px-4 py-3">Tgl Plan</th>
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
                                <td class="px-4 py-3 align-top">
                                    <div class="font-bold text-slate-800">{{ $plan->nama_toko }}</div>
                                    <div class="text-[10px] text-slate-400 truncate max-w-[150px]" title="{{ $plan->alamat }}">{{ $plan->alamat }}</div>
                                </td>
                                {{-- MENAMPILKAN TANGGAL OTOMATIS DARI CREATED_AT --}}
                                <td class="px-4 py-3 align-top font-medium text-slate-700 whitespace-nowrap">
                                    {{ $plan->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 text-center font-bold align-top">{{ $plan->stok_awal }}</td>
                                <td class="px-4 py-3 text-right align-top">Rp {{ number_format($plan->harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center text-emerald-600 font-bold align-top">{{ $plan->laku_pack }}</td>
                                <td class="px-4 py-3 text-center text-orange-500 font-bold align-top">{{ $plan->sisa_pack }}</td>
                                <td class="px-4 py-3 text-center text-blue-600 font-bold align-top">{{ $plan->tambah_pack }}</td>
                                <td class="px-4 py-3 text-center align-top">
                                    <form action="{{ route('eco.visit-plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded transition-colors" title="Hapus Data">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-8 text-slate-400">Belum ada data plan kunjungan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>