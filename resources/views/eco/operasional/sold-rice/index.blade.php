<x-admin-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Daftar Beras Terjual</h2>
            <p class="text-sm text-slate-500">Pencatatan penjualan beras berdasarkan kunjungan toko di cabang {{ Auth::user()->company_name ?? 'Pusat' }}.</p>
        </div>
        
        @if(Auth::user()->role !== 'admin_kantor_eco')
        <div>
            <a href="{{ route('eco.sold-rices.export') }}" target="_blank" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl font-bold text-sm shadow-md transition-all flex items-center gap-2">
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

    {{-- Menampilkan Error Validasi jika ada yang terlewat --}}
    @if($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 border border-red-100 font-medium text-sm">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ $errors->first() }}
        </div>
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
                        <input type="text" name="tempat" value="{{ Auth::user()->company_name ?? '' }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500" required>
                    </div>

                    <div x-data="{ isManual: false }">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Toko</label>
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

                        <div x-show="isManual" x-transition class="mt-2">
                            <input type="text" name="nama_toko_manual" 
                                   class="w-full px-3 py-2 border border-blue-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-blue-50" 
                                   placeholder="Ketik nama toko baru...">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kunjungan</label>
                            <select name="kunjungan_ke" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white" required>
                                @for($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}">Ke - {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ukuran</label>
                            <select name="ukuran" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white" required>
                                <option value="2.5kg">2,5 Kg</option>
                                <option value="5kg">5 Kg</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Jumlah Pack Terjual</label>
                        <div class="relative">
                            <input type="number" min="1" name="jumlah_pack" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500 font-bold" placeholder="0" required>
                            <span class="absolute right-3 top-2 text-slate-400 text-xs font-bold">Pack</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg shadow transition-all mt-2">
                        Simpan Penjualan
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
                                <th class="px-4 py-3 text-center">Ukuran</th>
                                <th class="px-4 py-3 text-center">Terjual</th>
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
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-700">{{ $item->nama_toko }}</div>
                                    <div class="text-[10px] text-emerald-600 font-bold">Kunjungan Ke-{{ $item->kunjungan_ke }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="bg-emerald-50 text-emerald-600 px-2 py-1 rounded font-bold text-xs border border-emerald-100">{{ $item->ukuran }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="font-black text-slate-700 text-lg">{{ $item->jumlah_pack ?? 0 }}</span> <span class="text-xs text-slate-400">Pack</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        {{-- Tombol Modal Edit --}}
                                        <button x-data @click="$dispatch('open-modal', 'edit-sold-rice-{{ $item->id }}')" class="text-amber-500 hover:text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-100 p-1.5 rounded transition-colors" title="Edit Data">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>

                                        {{-- Tombol Delete --}}
                                        <form action="{{ route('eco.sold-rices.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data penjualan ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 border border-red-100 p-1.5 rounded transition-colors" title="Hapus Data">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- MODAL EDIT DATA PENJUALAN --}}
                            <x-modal name="edit-sold-rice-{{ $item->id }}" focusable>
                                <form action="{{ route('eco.sold-rices.update', $item->id) }}" method="POST" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
                                    @csrf @method('PUT')
                                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                                        <h3 class="text-white font-bold text-lg">Edit Data Penjualan</h3>
                                        <button type="button" x-on:click="$dispatch('close')" class="text-emerald-100 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
                                    </div>
                                    <div class="p-6 overflow-y-auto custom-scrollbar space-y-4 text-left">
                                        
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                                                <input type="date" name="tanggal" value="{{ $item->tanggal }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tempat / Cabang</label>
                                                <input type="text" name="tempat" value="{{ $item->tempat }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" required>
                                            </div>
                                        </div>

                                        {{-- LOGIKA DETEKSI TOKO (MANUAL ATAU SELECT) UNTUK FORM EDIT --}}
                                        @php
                                            $storeNames = $stores->pluck('nama_toko')->toArray();
                                            $isManualData = !in_array($item->nama_toko, $storeNames);
                                        @endphp
                                        <div x-data="{ isManualEdit: {{ $isManualData ? 'true' : 'false' }} }">
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Toko</label>
                                            <select name="nama_toko_select" 
                                                    class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 bg-white mb-2" 
                                                    @change="isManualEdit = $event.target.value === 'Lainnya'" required>
                                                <option value="" disabled>-- Pilih Toko Mitra --</option>
                                                @foreach($stores as $store)
                                                    <option value="{{ $store->nama_toko }}" {{ (!$isManualData && $item->nama_toko == $store->nama_toko) ? 'selected' : '' }}>
                                                        {{ $store->nama_toko }} ({{ $store->kantor_cabang }})
                                                    </option>
                                                @endforeach
                                                <option value="Lainnya" class="font-bold text-blue-600 bg-slate-100" {{ $isManualData ? 'selected' : '' }}>+ Lainnya / Input Manual</option>
                                            </select>

                                            <div x-show="isManualEdit" x-transition class="mt-2">
                                                <input type="text" name="nama_toko_manual" value="{{ $isManualData ? $item->nama_toko : '' }}"
                                                       class="w-full px-3 py-2 border border-blue-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-blue-50" 
                                                       placeholder="Ketik nama toko...">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kunjungan</label>
                                                <select name="kunjungan_ke" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 bg-white" required>
                                                    @for($i = 1; $i <= 6; $i++)
                                                        <option value="{{ $i }}" {{ $item->kunjungan_ke == $i ? 'selected' : '' }}>Ke - {{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ukuran</label>
                                                <select name="ukuran" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 bg-white" required>
                                                    <option value="2.5kg" {{ $item->ukuran == '2.5kg' ? 'selected' : '' }}>2,5 Kg</option>
                                                    <option value="5kg" {{ $item->ukuran == '5kg' ? 'selected' : '' }}>5 Kg</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Jumlah Pack Terjual</label>
                                            <div class="relative">
                                                <input type="number" min="1" name="jumlah_pack" value="{{ $item->jumlah_pack }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 font-bold" required>
                                                <span class="absolute right-3 top-2 text-slate-400 text-xs font-bold">Pack</span>
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