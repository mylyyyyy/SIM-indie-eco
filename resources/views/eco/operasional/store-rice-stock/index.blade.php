<x-admin-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Stok Beras di Toko</h2>
            <p class="text-sm text-slate-500">Pencatatan jumlah stok beras 2.5kg dan 5kg di setiap toko mitra cabang {{ Auth::user()->company_name ?? 'Pusat' }}.</p>
        </div>
        
        {{-- IMPLEMENTASI BRD: Hak akses download dipindah ke Manager/Keuangan --}}
        @if(Auth::user()->role !== 'admin_kantor_eco')
        <div>
            <a href="{{ route('eco.store-rice-stocks.export') }}" target="_blank" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl font-bold text-sm shadow-md transition-all flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> Unduh PDF Laporan
            </a>
        </div>
        @endif
    </div>

    @if(session('success'))
        <script>
            Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });
        </script>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Form Input --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Input Stok Toko</h3>
                
                <form action="{{ route('eco.store-rice-stocks.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Admin</label>
                        <input type="text" name="nama_admin" value="{{ Auth::user()->name }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 bg-slate-50" readonly required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Toko Mitra</label>
                        <select name="nama_toko" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 bg-white" required>
                            <option value="" disabled selected>-- Pilih Toko Mitra --</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->nama_toko }}">{{ $store->nama_toko }} ({{ $store->kantor_cabang }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Stok 2.5 Kg</label>
                            <div class="relative">
                                <input type="number" min="0" name="stok_2_5kg" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" placeholder="0" required>
                                <span class="absolute right-3 top-2 text-slate-400 text-xs font-bold">Pack</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Stok 5 Kg</label>
                            <div class="relative">
                                <input type="number" min="0" name="stok_5kg" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" placeholder="0" required>
                                <span class="absolute right-3 top-2 text-slate-400 text-xs font-bold">Pack</span>
                            </div>
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
                                <th class="px-4 py-3">Tanggal & Admin</th>
                                <th class="px-4 py-3">Nama Toko</th>
                                <th class="px-4 py-3 text-center">Stok 2.5 Kg</th>
                                <th class="px-4 py-3 text-center">Stok 5 Kg</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($stocks as $stock)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($stock->tanggal)->format('d/m/Y') }}</div>
                                    <div class="text-[10px] text-slate-500"><i class="fas fa-user mr-1"></i> {{ $stock->nama_admin }}</div>
                                </td>
                                <td class="px-4 py-3 font-medium text-slate-700">
                                    {{ $stock->nama_toko }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="bg-emerald-50 text-emerald-600 px-2 py-1 rounded font-bold text-xs border border-emerald-100">
                                        {{ number_format($stock->stok_2_5kg, 0, ',', '.') }} Pack
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded font-bold text-xs border border-blue-100">
                                        {{ number_format($stock->stok_5kg, 0, ',', '.') }} Pack
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        {{-- Tombol Buka Modal Edit --}}
                                        <button x-data @click="$dispatch('open-modal', 'edit-stock-{{ $stock->id }}')" class="text-amber-500 hover:text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-100 p-1.5 rounded transition-colors" title="Edit Data">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>

                                        <form action="{{ route('eco.store-rice-stocks.destroy', $stock->id) }}" method="POST" onsubmit="return confirm('Hapus data stok toko ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 border border-red-100 p-1.5 rounded transition-colors" title="Hapus Data">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- MODAL EDIT DATA --}}
                            <x-modal name="edit-stock-{{ $stock->id }}" focusable>
                                <form action="{{ route('eco.store-rice-stocks.update', $stock->id) }}" method="POST" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
                                    @csrf @method('PUT')
                                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                                        <h3 class="text-white font-bold text-lg">Edit Stok Toko: {{ $stock->nama_toko }}</h3>
                                        <button type="button" x-on:click="$dispatch('close')" class="text-emerald-100 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
                                    </div>
                                    <div class="p-6 overflow-y-auto custom-scrollbar space-y-4 text-left">
                                        
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                                            <input type="date" name="tanggal" value="{{ $stock->tanggal }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" required>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Toko Mitra</label>
                                            <select name="nama_toko" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 bg-white" required>
                                                @foreach($stores as $s)
                                                    <option value="{{ $s->nama_toko }}" {{ $s->nama_toko == $stock->nama_toko ? 'selected' : '' }}>{{ $s->nama_toko }} ({{ $s->kantor_cabang }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Stok 2.5 Kg</label>
                                                <div class="relative">
                                                    <input type="number" min="0" name="stok_2_5kg" value="{{ $stock->stok_2_5kg }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 font-bold" required>
                                                    <span class="absolute right-3 top-2 text-slate-400 text-xs font-bold">Pack</span>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Stok 5 Kg</label>
                                                <div class="relative">
                                                    <input type="number" min="0" name="stok_5kg" value="{{ $stock->stok_5kg }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 font-bold" required>
                                                    <span class="absolute right-3 top-2 text-slate-400 text-xs font-bold">Pack</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl border-t border-slate-100">
                                        <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold">Batal</button>
                                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </x-modal>

                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-slate-400">Belum ada data stok beras toko.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>