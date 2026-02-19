<x-admin-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Daftar Beras Terjual</h2>
            <p class="text-sm text-slate-500">Pencatatan penjualan beras berdasarkan kunjungan toko.</p>
        </div>
        <div>
            <a href="{{ route('eco.sold-rices.export') }}" target="_blank" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl font-bold text-sm shadow-md transition-all flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> Unduh PDF Laporan
            </a>
        </div>
    </div>

    @if(session('success'))
        <script>
            Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false });
        </script>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Form Input --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Input Penjualan</h3>
                
                <form action="{{ route('eco.sold-rices.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tempat / Cabang</label>
                        <input type="text" name="tempat" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="Contoh: Cabang Lumajang" required>
                    </div>

                    {{-- LOGIKA PILIHAN GANDA / MANUAL --}}
                    <div x-data="{ isManual: false }">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Toko</label>
                        
                        {{-- 1. Dropdown Utama --}}
                        <select name="nama_toko_select" 
                                class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white mb-2" 
                                @change="isManual = $event.target.value === 'Lainnya'"
                                required>
                            <option value="" disabled selected>-- Pilih Toko Mitra --</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->nama_toko }}">{{ $store->nama_toko }} ({{ $store->kantor_cabang }})</option>
                            @endforeach
                            <option value="Lainnya" class="font-bold text-blue-600 bg-slate-100">+ Lainnya / Input Manual</option>
                        </select>

                        {{-- 2. Input Manual (Muncul jika pilih Lainnya) --}}
                        <div x-show="isManual" x-transition class="mt-2">
                            <input type="text" name="nama_toko_manual" 
                                   class="w-full px-3 py-2 border border-blue-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-blue-50" 
                                   placeholder="Ketik nama toko baru di sini...">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kunjungan</label>
                            <select name="kunjungan_ke" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white" required>
                                <option value="1">Ke - 1</option>
                                <option value="2">Ke - 2</option>
                                <option value="3">Ke - 3</option>
                                <option value="4">Ke - 4</option>
                                <option value="5">Ke - 5</option>
                                <option value="6">Ke - 6</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ukuran Pack</label>
                            <select name="ukuran" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white" required>
                                <option value="2.5kg">2,5 Kg</option>
                                <option value="5kg">5 Kg</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg shadow transition-all mt-2">
                        Simpan Data
                    </button>
                </form>
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold">
                            <tr>
                                <th class="px-4 py-3">Tanggal & Tempat</th>
                                <th class="px-4 py-3">Nama Toko</th>
                                <th class="px-4 py-3 text-center">Kunjungan</th>
                                <th class="px-4 py-3 text-center">Ukuran</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($soldRices as $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</div>
                                    <div class="text-[10px] text-slate-500 uppercase tracking-wider">{{ $item->tempat }}</div>
                                </td>
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $item->nama_toko }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded font-bold text-xs">Ke-{{ $item->kunjungan_ke }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="bg-emerald-50 text-emerald-600 px-2 py-1 rounded font-bold text-xs">{{ $item->ukuran }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('eco.sold-rices.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-slate-400">Belum ada data beras terjual.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>