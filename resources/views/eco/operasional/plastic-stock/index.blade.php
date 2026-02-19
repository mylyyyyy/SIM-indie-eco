<x-admin-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Laporan Stok Plastik</h2>
            <p class="text-sm text-slate-500">Pencatatan penggunaan plastik dan packing harian.</p>
        </div>
        <div>
            <a href="{{ route('eco.plastic-stocks.export') }}" target="_blank" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl font-bold text-sm shadow-md transition-all flex items-center gap-2">
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
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sticky top-24">
                <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Input Pemakaian</h3>
                
                {{-- ALPINE.JS: State untuk Dropdown Tempat --}}
                <form action="{{ route('eco.plastic-stocks.store') }}" method="POST" class="space-y-4" x-data="{ isManualTempat: false }">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500" required>
                    </div>

                    {{-- DROPDOWN TEMPAT (Dengan Opsi Lainnya) --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tempat / Toko</label>
                        <select name="tempat_select" 
                                class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white mb-2" 
                                @change="isManualTempat = $event.target.value === 'Lainnya'"
                                required>
                            <option value="" disabled selected>-- Pilih Tempat / Toko --</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->nama_toko }}">
                                    {{ $store->nama_toko }} ({{ $store->kantor_cabang }})
                                </option>
                            @endforeach
                            <option value="Lainnya" class="font-bold text-blue-600 bg-slate-100">+ Lainnya / Input Manual</option>
                        </select>

                        {{-- Input Manual (Muncul jika pilih Lainnya) --}}
                        <div x-show="isManualTempat" x-transition class="mt-2">
                            <input type="text" name="tempat_manual" 
                                   class="w-full px-3 py-2 border border-blue-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-blue-50" 
                                   placeholder="Ketik tempat baru di sini...">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Jenis Plastik / Packing</label>
                        <input type="text" name="jenis_plastik" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="Contoh: Karung 5kg / Plastik 2.5kg" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Stok Awal</label>
                            <input type="number" name="stok_awal" value="0" min="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Stok Sisa</label>
                            <input type="number" name="stok_sisa" value="0" min="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500" required>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg shadow transition-all mt-2 transform hover:-translate-y-0.5">
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
                                <th class="px-4 py-3">Tgl & Tempat</th>
                                <th class="px-4 py-3">Jenis Packing</th>
                                <th class="px-4 py-3 text-center">Stok Awal</th>
                                <th class="px-4 py-3 text-center">Stok Sisa</th>
                                <th class="px-4 py-3 text-center">Terpakai</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($stocks as $stock)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($stock->tanggal)->format('d/m/Y') }}</div>
                                    <div class="text-[10px] text-slate-500 uppercase tracking-wider">{{ $stock->tempat }}</div>
                                </td>
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $stock->jenis_plastik }}</td>
                                <td class="px-4 py-3 text-center">{{ number_format($stock->stok_awal) }}</td>
                                <td class="px-4 py-3 text-center font-bold text-orange-500">{{ number_format($stock->stok_sisa) }}</td>
                                <td class="px-4 py-3 text-center font-black text-emerald-600">
                                    {{ number_format($stock->stok_awal - $stock->stok_sisa) }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('eco.plastic-stocks.destroy', $stock->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-slate-400">Belum ada data stok plastik.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>