<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- HEADER PAGE --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Kelola Data Cabang</h2>
            <p class="text-slate-500 font-medium">Daftar lokasi gudang, pabrik, dan toko mitra.</p>
        </div>
        {{-- Tombol Tambah Tetap Pakai Modal --}}
        <button x-data @click="$dispatch('open-modal', 'add-location-modal')" 
            class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-plus-circle"></i> <span>Tambah Cabang</span>
        </button>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
            });
        </script>
    @endif

    {{-- TABLE CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Nama Cabang</th>
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Stok Saat Ini</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($locations as $item)
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800 text-base">{{ $item->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->type == 'mill') 
                                <span class="inline-flex items-center gap-1.5 bg-purple-100 text-purple-700 px-2.5 py-1 rounded-lg text-xs font-bold border border-purple-200"><i class="fas fa-industry"></i> Pabrik</span>
                            @elseif($item->type == 'warehouse') 
                                <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-bold border border-blue-200"><i class="fas fa-warehouse"></i> Gudang</span>
                            @else 
                                <span class="inline-flex items-center gap-1.5 bg-orange-100 text-orange-700 px-2.5 py-1 rounded-lg text-xs font-bold border border-orange-200"><i class="fas fa-store"></i> Toko</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-slate-700">{{ number_format($item->current_stock) }} <span class="text-slate-400 text-xs font-sans">kg</span></td>
                        <td class="px-6 py-4">
                            @if($item->status == 'active')
                                <span class="inline-flex items-center gap-1.5 text-emerald-600 font-bold text-xs bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Aktif</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-slate-500 font-bold text-xs bg-slate-100 px-2.5 py-1 rounded-full border border-slate-200"><i class="fas fa-ban text-[10px]"></i> Non-Aktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                {{-- BUTTON EDIT (LINK KE HALAMAN BARU) --}}
                                <a href="{{ route('admin.locations.edit', $item->id) }}" 
                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all shadow-sm flex items-center justify-center">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                
                                <form action="{{ route('admin.locations.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus cabang ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                        class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all shadow-sm flex items-center justify-center">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    {{-- HAPUS MODAL EDIT DARI SINI --}}
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium">Belum ada data cabang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH (Tetap ada di sini) --}}
    <x-modal name="add-location-modal" focusable>
        <form action="{{ route('admin.locations.store') }}" method="POST" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
            @csrf
            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-blue-200"></i> Tambah Cabang Baru
                </h3>
                <button type="button" x-on:click="$dispatch('close')" class="text-blue-200 hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 overflow-y-auto custom-scrollbar">
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Cabang <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500 transition-all placeholder:text-slate-300" placeholder="Contoh: Toko Berkah Jaya Pusat" required>
                    </div>
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tipe</label>
                            <div class="relative">
                                <select name="type" class="w-full border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500 appearance-none">
                                    <option value="shop">Toko (Shop)</option>
                                    <option value="warehouse">Gudang</option>
                                    <option value="mill">Pabrik (Mill)</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-3 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Stok Awal (Kg)</label>
                            <input type="number" name="current_stock" value="0" class="w-full border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Status</label>
                        <div class="relative">
                            <select name="status" class="w-full border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500 appearance-none">
                                <option value="active">Aktif (Beroperasi)</option>
                                <option value="inactive">Non-Aktif (Tutup)</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-3 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3 rounded-b-2xl shrink-0">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">Simpan Data</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>