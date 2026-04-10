<x-admin-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-800">Laporan Inventaris Material</h2>
        <p class="text-sm text-slate-500">
            @if(Auth::user()->role == 'subkon_pt')
                Catat keluar masuk bahan bangunan dan sisa stok di lapangan.
            @else
                Pantau penggunaan material proyek dari Subkon PT.
            @endif
        </p>
    </div>

    @if(session('success'))
        <script>Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });</script>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        @if(Auth::user()->role == 'subkon_pt')
        {{-- KOLOM KIRI: FORM INPUT --}}
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-fit sticky top-24">
            <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Catat Material Baru</h3>
            
            <form action="{{ route('subkon-pt.materials.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Proyek</label>
                    <input type="text" name="project_name" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-blue-500" required>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Satuan</label>
                        <input type="text" name="satuan" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-blue-500" placeholder="Sak, M3, Btg..." required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Material</label>
                    <input type="text" name="nama_material" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-blue-500" placeholder="Contoh: Semen Gresik, Besi 12mm..." required>
                </div>

                <div class="p-3 border border-slate-200 rounded-lg bg-slate-50">
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Awal</label>
                            <input type="number" step="0.01" min="0" name="stok_awal" class="w-full px-2 py-2 border border-slate-300 rounded text-sm text-center" value="0" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-blue-600 uppercase mb-1">Masuk</label>
                            <input type="number" step="0.01" min="0" name="material_masuk" class="w-full px-2 py-2 border border-blue-300 rounded text-sm text-center font-bold" value="0" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-orange-600 uppercase mb-1">Dipakai</label>
                            <input type="number" step="0.01" min="0" name="material_terpakai" class="w-full px-2 py-2 border border-orange-300 rounded text-sm text-center font-bold" value="0" required>
                        </div>
                    </div>
                    <p class="text-[9px] text-slate-400 mt-2 text-center italic">*Sisa stok akan dihitung otomatis.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" placeholder="Catatan tambahan..."></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow mt-2">Simpan Stok</button>
            </form>
        </div>
        @endif

        {{-- KOLOM KANAN: TABEL DATA --}}
        <div class="{{ Auth::user()->role == 'subkon_pt' ? 'lg:col-span-2' : 'lg:col-span-3' }} bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold">
                        <tr>
                            <th class="px-4 py-3">Tgl & Proyek</th>
                            <th class="px-4 py-3">Material</th>
                            <th class="px-4 py-3 text-center">Mutasi (Masuk/Keluar)</th>
                            <th class="px-4 py-3 text-center">Sisa Stok</th>
                            @if(Auth::user()->role == 'subkon_pt')
                                <th class="px-4 py-3 text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($materials as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 align-top">
                                <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</div>
                                <div class="text-[10px] text-blue-600 font-bold mt-0.5">{{ $item->project_name }}</div>
                                @if(Auth::user()->role != 'subkon_pt')
                                    <div class="text-[10px] text-slate-400 mt-1"><i class="fas fa-user mr-1"></i>{{ $item->user->name ?? '-' }}</div>
                                @endif
                            </td>
                            
                            <td class="px-4 py-3 align-top">
                                <div class="font-bold text-slate-700">{{ $item->nama_material }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">{{ $item->keterangan }}</div>
                            </td>
                            
                            <td class="px-4 py-3 text-center align-top">
                                <div class="flex justify-center gap-2 text-xs">
                                    <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded font-bold border border-blue-100" title="Masuk">
                                        <i class="fas fa-arrow-down mr-1"></i>{{ $item->material_masuk }} {{ $item->satuan }}
                                    </span>
                                    <span class="bg-orange-50 text-orange-700 px-2 py-1 rounded font-bold border border-orange-100" title="Terpakai">
                                        <i class="fas fa-arrow-up mr-1"></i>{{ $item->material_terpakai }} {{ $item->satuan }}
                                    </span>
                                </div>
                                <div class="text-[9px] text-slate-400 mt-1">Stok Awal: {{ $item->stok_awal }}</div>
                            </td>
                            
                            <td class="px-4 py-3 text-center align-top">
                                <span class="text-sm font-black {{ $item->sisa_stok <= 0 ? 'text-red-500' : 'text-emerald-600' }}">
                                    {{ $item->sisa_stok }} <span class="text-xs">{{ $item->satuan }}</span>
                                </span>
                            </td>
                            
                            @if(Auth::user()->role == 'subkon_pt')
                            <td class="px-4 py-3 text-center align-top">
                                <form action="{{ route('subkon-pt.materials.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data material ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded transition-colors"><i class="fas fa-trash text-xs"></i></button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr><td colspan="{{ Auth::user()->role == 'subkon_pt' ? '5' : '4' }}" class="text-center py-8 text-slate-400">Belum ada data material.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>