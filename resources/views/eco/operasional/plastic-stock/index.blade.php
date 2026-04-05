<x-admin-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Laporan Stok Plastik</h2>
            <p class="text-sm text-slate-500">Pencatatan penggunaan plastik dan packing harian Cabang {{ Auth::user()->company_name ?? 'Pusat' }}.</p>
        </div>
        
        @if(Auth::user()->role !== 'admin_kantor_eco')
        <div>
            <a href="{{ route('eco.plastic-stocks.export') }}" target="_blank" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl font-bold text-sm shadow-md transition-all flex items-center gap-2">
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
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sticky top-24">
                <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Input Pemakaian</h3>
                
                <form action="{{ route('eco.plastic-stocks.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500" required>
                    </div>

                    {{-- NAMA CABANG OTOMATIS (READONLY) --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Cabang</label>
                        <input type="text" value="{{ Auth::user()->company_name ?? 'Pusat' }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-slate-50 text-slate-500 cursor-not-allowed font-medium" readonly>
                        <p class="text-[10px] text-slate-400 mt-1 italic">*Terisi otomatis oleh sistem</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Jenis Plastik / Packing</label>
                        <input type="text" name="jenis_plastik" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="Contoh: Karung 5kg / Plastik 2.5kg" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Stok Awal</label>
                            <input type="number" name="stok_awal" value="0" min="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500 font-bold" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Stok Sisa</label>
                            <input type="number" name="stok_sisa" value="0" min="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500 font-bold text-orange-600" required>
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
                                <th class="px-4 py-3">Tanggal & Cabang</th>
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
                                    <div class="text-[10px] text-slate-500 uppercase tracking-wider">{{ $stock->nama_cabang }}</div>
                                </td>
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $stock->jenis_plastik }}</td>
                                <td class="px-4 py-3 text-center">{{ number_format($stock->stok_awal) }}</td>
                                <td class="px-4 py-3 text-center font-bold text-orange-500">{{ number_format($stock->stok_sisa) }}</td>
                                <td class="px-4 py-3 text-center font-black text-emerald-600 bg-emerald-50/30">
                                    {{ number_format($stock->stok_awal - $stock->stok_sisa) }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        {{-- Tombol Edit --}}
                                        <button x-data @click="$dispatch('open-modal', 'edit-plastic-{{ $stock->id }}')" class="text-amber-500 hover:text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-100 p-1.5 rounded transition-colors" title="Edit Data">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('eco.plastic-stocks.destroy', $stock->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 border border-red-100 p-1.5 rounded transition-colors">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- MODAL EDIT DATA PLASTIK --}}
                            <x-modal name="edit-plastic-{{ $stock->id }}" focusable>
                                <form action="{{ route('eco.plastic-stocks.update', $stock->id) }}" method="POST" class="bg-white rounded-2xl flex flex-col">
                                    @csrf @method('PUT')
                                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                                        <h3 class="text-white font-bold text-lg">Edit Stok Plastik</h3>
                                        <button type="button" x-on:click="$dispatch('close')" class="text-emerald-100 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
                                    </div>
                                    <div class="p-6 space-y-4 text-left">
                                        
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                                            <input type="date" name="tanggal" value="{{ $stock->tanggal }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" required>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Cabang</label>
                                            <input type="text" value="{{ $stock->nama_cabang }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-slate-50 text-slate-500 cursor-not-allowed" readonly>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Jenis Plastik / Packing</label>
                                            <input type="text" name="jenis_plastik" value="{{ $stock->jenis_plastik }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" required>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Stok Awal</label>
                                                <input type="number" name="stok_awal" value="{{ $stock->stok_awal }}" min="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 font-bold" required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Stok Sisa</label>
                                                <input type="number" name="stok_sisa" value="{{ $stock->stok_sisa }}" min="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 font-bold text-orange-600" required>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl border-t border-slate-100">
                                        <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50">Batal</button>
                                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </x-modal>

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