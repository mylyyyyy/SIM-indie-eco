<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- HEADER PAGE --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Evaluasi Pegawai (LHKP)</h2>
            <p class="text-slate-500 font-medium">Laporan Harian Kinerja Pegawai Divisi Indie.</p>
        </div>
        
        <div class="flex gap-3">
            <a href="{{ route('indie.manager-unit.lhkp.export-pdf') }}" class="bg-white border border-red-200 text-red-600 hover:bg-red-50 px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-sm transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-file-pdf"></i> <span class="hidden md:inline">Download PDF</span>
            </a>

            <button x-data @click="$dispatch('open-modal', 'add-lhkp-modal')" 
                class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-plus-circle"></i> <span>Tambah Evaluasi</span>
            </button>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false, toast: true, position: 'top-end' });
            });
        </script>
    @endif

    {{-- TABLE CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Tanggal & Tempat</th>
                        <th class="px-6 py-4">Pegawai</th>
                        <th class="px-6 py-4">Hasil Evaluasi / Progres</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lhkps as $item)
                    <tr class="hover:bg-indigo-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</div>
                            <div class="text-xs text-slate-500 mt-1"><i class="fas fa-map-marker-alt text-indigo-400 mr-1"></i> {{ $item->tempat }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-indigo-600">{{ $item->nama_pegawai }}</div>
                            <div class="text-xs text-slate-500">NIP: {{ $item->nip }} | {{ $item->divisi }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 line-clamp-2">
                            {{ $item->progres_pekerjaan }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button x-data @click="$dispatch('open-modal', 'edit-lhkp-modal-{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-pen text-xs"></i>
                                </button>
                                <form action="{{ route('indie.manager-unit.lhkp.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus LHKP ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- MODAL EDIT LHKP --}}
                    <x-modal name="edit-lhkp-modal-{{ $item->id }}" focusable>
                        <form action="{{ route('indie.manager-unit.lhkp.update', $item->id) }}" method="POST" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
                            @csrf @method('PUT')
                            <div class="bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                                <h3 class="text-white font-bold text-lg">Edit LHKP</h3>
                                <button type="button" x-on:click="$dispatch('close')" class="text-indigo-200 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
                            </div>
                            <div class="p-6 overflow-y-auto custom-scrollbar space-y-5">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal</label>
                                        <input type="date" name="tanggal" value="{{ $item->tanggal }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tempat / Area</label>
                                        <input type="text" name="tempat" value="{{ $item->tempat }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Pegawai</label>
                                        <input type="text" name="nama_pegawai" value="{{ $item->nama_pegawai }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">NIP</label>
                                        <input type="text" name="nip" value="{{ $item->nip }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Divisi</label>
                                        <input type="text" name="divisi" value="{{ $item->divisi }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Hasil Evaluasi / Progres</label>
                                    <textarea name="progres_pekerjaan" rows="4" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ $item->progres_pekerjaan }}</textarea>
                                </div>
                            </div>
                            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl">
                                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold">Batal</button>
                                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700">Simpan Perubahan</button>
                            </div>
                        </form>
                    </x-modal>

                    @empty
                    <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400 font-medium"><i class="fas fa-folder-open text-4xl mb-3 block"></i> Belum ada data LHKP.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH LHKP --}}
    <x-modal name="add-lhkp-modal" focusable>
        <form action="{{ route('indie.manager-unit.lhkp.store') }}" method="POST" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
            @csrf
            <div class="bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                <h3 class="text-white font-bold text-lg"><i class="fas fa-plus-circle mr-2"></i> Form Evaluasi LHKP</h3>
                <button type="button" x-on:click="$dispatch('close')" class="text-indigo-200 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tempat / Area</label>
                        <input type="text" name="tempat" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Contoh: Kantor Cabang A">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Pegawai</label>
                        <input type="text" name="nama_pegawai" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Masukkan nama...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">NIP</label>
                        <input type="text" name="nip" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Masukkan NIP...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Divisi</label>
                        <input type="text" name="divisi" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Contoh: Operasional">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Hasil Evaluasi / Progres Pekerjaan</label>
                    <textarea name="progres_pekerjaan" rows="4" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Tuliskan evaluasi kinerja pegawai..."></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700">Simpan Data</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>