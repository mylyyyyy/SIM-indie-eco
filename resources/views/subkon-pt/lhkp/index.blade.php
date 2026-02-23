<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- HEADER PAGE --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Input LHKP Proyek</h2>
            <p class="text-slate-500 font-medium">Laporan Harian Kegiatan Proyek (LHKP) Divisi Indie.</p>
        </div>
        
        <div class="flex gap-3">
            {{-- Tombol PDF Baru --}}
            <a href="{{ route('subkon-pt.lhkp.export-pdf') }}" class="bg-white border border-red-200 text-red-600 hover:bg-red-50 px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-sm transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-file-pdf"></i> <span class="hidden md:inline">Download PDF</span>
            </a>

            <button x-data @click="$dispatch('open-modal', 'add-lhkp-modal')" 
                class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-plus-circle"></i> <span>Tambah LHKP</span>
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
            <table class="w-full text-left text-sm text-slate-600" id="dataTable">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Tanggal & Tempat</th>
                        <th class="px-6 py-4">Pegawai / Pelaksana</th>
                        <th class="px-6 py-4">Progres Pekerjaan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lhkps as $item)
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</div>
                            <div class="text-xs text-slate-500 flex items-center gap-1 mt-1"><i class="fas fa-map-marker-alt text-red-400"></i> {{ $item->tempat }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-blue-600">{{ $item->nama_pegawai }}</div>
                            <div class="text-xs text-slate-500">NIP: {{ $item->nip }} | {{ $item->divisi }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-600 line-clamp-2">{{ $item->progres_pekerjaan }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button x-data @click="$dispatch('open-modal', 'edit-lhkp-modal-{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all shadow-sm flex items-center justify-center">
                                    <i class="fas fa-pen text-xs"></i>
                                </button>
                                <form action="{{ route('subkon-pt.lhkp.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus LHKP ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all shadow-sm flex items-center justify-center">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- MODAL EDIT LHKP --}}
                    <x-modal name="edit-lhkp-modal-{{ $item->id }}" focusable>
                        <form action="{{ route('subkon-pt.lhkp.update', $item->id) }}" method="POST" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
                            @csrf @method('PUT')
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                                <h3 class="text-white font-bold text-lg">Edit LHKP</h3>
                                <button type="button" x-on:click="$dispatch('close')" class="text-blue-200 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
                            </div>
                            <div class="p-6 overflow-y-auto custom-scrollbar space-y-5">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal</label>
                                        <input type="date" name="tanggal" value="{{ $item->tanggal }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tempat Proyek</label>
                                        <input type="text" name="tempat" value="{{ $item->tempat }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Pegawai</label>
                                        <input type="text" name="nama_pegawai" value="{{ $item->nama_pegawai }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">NIP</label>
                                        <input type="text" name="nip" value="{{ $item->nip }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Divisi</label>
                                        <input type="text" name="divisi" value="{{ $item->divisi }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Progres Pekerjaan</label>
                                    <textarea name="progres_pekerjaan" rows="4" class="w-full border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required>{{ $item->progres_pekerjaan }}</textarea>
                                </div>
                            </div>
                            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl">
                                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold">Batal</button>
                                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700">Simpan Perubahan</button>
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
        <form action="{{ route('subkon-pt.lhkp.store') }}" method="POST" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
            @csrf
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                <h3 class="text-white font-bold text-lg"><i class="fas fa-plus-circle mr-2"></i> Tambah LHKP</h3>
                <button type="button" x-on:click="$dispatch('close')" class="text-blue-200 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tempat Proyek</label>
                        <input type="text" name="tempat" class="w-full border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required placeholder="Contoh: Proyek Gedung A">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Pegawai / Pelaksana</label>
                        <input type="text" name="nama_pegawai" class="w-full border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required placeholder="Masukkan nama...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">NIP</label>
                        <input type="text" name="nip" class="w-full border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required placeholder="Masukkan NIP...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Divisi / Bagian</label>
                        <input type="text" name="divisi" class="w-full border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required placeholder="Contoh: Infrastruktur">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Progres Pekerjaan</label>
                    <textarea name="progres_pekerjaan" rows="4" class="w-full border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required placeholder="Deskripsikan progres pekerjaan hari ini..."></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700">Simpan Data</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>