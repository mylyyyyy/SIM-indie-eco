<x-admin-layout>
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Laporan Kinerja Pegawai (LHKP)</h2>
            <p class="text-sm text-slate-500">Input dan kelola laporan harian aktivitas pegawai.</p>
        </div>
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000,
                    customClass: { popup: 'rounded-2xl' }
                });
            });
        </script>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- BAGIAN 1: FORM INPUT (1 Kolom) --}}
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 sticky top-24">
                <h3 class="font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-emerald-500"></i> Input Laporan Baru
                </h3>

                <form action="{{ route('manager_unit.lhkp.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    {{-- Tanggal --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all" required>
                    </div>

                    {{-- Tempat --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tempat / Lokasi</label>
                        <input type="text" name="tempat" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all" placeholder="Contoh: Gudang Pusat..." required>
                    </div>

                    {{-- Nama Pegawai --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Pegawai</label>
                        <input type="text" name="nama_pegawai" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all" placeholder="Nama lengkap pegawai" required>
                    </div>

                    {{-- NIP & Divisi --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">NIP</label>
                            <input type="text" name="nip" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all" placeholder="Nomor Induk" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Divisi</label>
                            <input type="text" name="divisi" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all" placeholder="Contoh: Logistik" required>
                        </div>
                    </div>

                    {{-- Progres --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Progres Pekerjaan</label>
                        <textarea name="progres_pekerjaan" rows="4" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all" placeholder="Detail pekerjaan hari ini..." required></textarea>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl shadow-lg shadow-emerald-500/30 transition-all flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                        <i class="fas fa-save"></i> Simpan Data
                    </button>
                </form>
            </div>
        </div>

        {{-- BAGIAN 2: TABEL RIWAYAT (2 Kolom) --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="font-bold text-slate-800">Riwayat LHKP</h3>
                    <span class="text-xs font-medium bg-white border border-slate-200 text-slate-500 px-3 py-1 rounded-full">{{ $lhkps->count() }} Data</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-white border-b border-slate-200 text-xs uppercase font-bold text-slate-500">
                            <tr>
                                <th class="px-6 py-4">Tanggal & Lokasi</th>
                                <th class="px-6 py-4">Pegawai</th>
                                <th class="px-6 py-4">Ringkasan Progres</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($lhkps as $lhkp)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-6 py-4 align-top">
                                    <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($lhkp->tanggal)->format('d/m/Y') }}</div>
                                    <div class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                        <i class="fas fa-map-pin text-red-400"></i> {{ $lhkp->tempat }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="font-bold text-emerald-700">{{ $lhkp->nama_pegawai }}</div>
                                    <div class="text-xs text-slate-400">NIP: {{ $lhkp->nip }}</div>
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-slate-100 text-slate-500 text-[10px] rounded border border-slate-200">
                                        {{ $lhkp->divisi }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <p class="line-clamp-2 text-xs leading-relaxed text-slate-600" title="{{ $lhkp->progres_pekerjaan }}">
                                        {{ $lhkp->progres_pekerjaan }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 align-top text-center">
                                    <div class="flex items-center justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                        {{-- Tombol Edit (Trigger Modal) --}}
                                        <button x-data @click="$dispatch('open-modal', 'edit-modal-{{ $lhkp->id }}')" 
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all border border-blue-100" 
                                            title="Edit">
                                            <i class="fas fa-pen text-xs"></i>
                                        </button>

                                        {{-- Tombol Hapus --}}
                                        <button type="button" onclick="confirmDelete('{{ $lhkp->id }}')" 
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all border border-red-100" 
                                            title="Hapus">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                        
                                        <form id="delete-form-{{ $lhkp->id }}" action="{{ route('manager_unit.lhkp.destroy', $lhkp->id) }}" method="POST" class="hidden">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- MODAL EDIT (Disimpan di dalam loop agar unik per item) --}}
                            <x-modal name="edit-modal-{{ $lhkp->id }}" focusable>
                                <form method="POST" action="{{ route('manager_unit.lhkp.update', $lhkp->id) }}" class="bg-white rounded-2xl overflow-hidden flex flex-col max-h-[90vh]">
                                    @csrf @method('PUT')
                                    
                                    {{-- Header Modal --}}
                                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                                        <h3 class="text-lg font-bold text-slate-800">Edit Laporan Kinerja</h3>
                                        <button type="button" x-on:click="$dispatch('close')" class="text-slate-400 hover:text-slate-600">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>

                                    {{-- Body Modal --}}
                                    <div class="p-6 overflow-y-auto custom-scrollbar space-y-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                                                <input type="date" name="tanggal" value="{{ $lhkp->tanggal }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-blue-500" required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tempat</label>
                                                <input type="text" name="tempat" value="{{ $lhkp->tempat }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-blue-500" required>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Pegawai</label>
                                            <input type="text" name="nama_pegawai" value="{{ $lhkp->nama_pegawai }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-blue-500" required>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">NIP</label>
                                                <input type="text" name="nip" value="{{ $lhkp->nip }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-blue-500" required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Divisi</label>
                                                <input type="text" name="divisi" value="{{ $lhkp->divisi }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-blue-500" required>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Progres Pekerjaan</label>
                                            <textarea name="progres_pekerjaan" rows="5" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-blue-500" required>{{ $lhkp->progres_pekerjaan }}</textarea>
                                        </div>
                                    </div>

                                    {{-- Footer Modal --}}
                                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                                        <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50">Batal</button>
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </x-modal>

                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="far fa-folder-open text-4xl mb-3 text-slate-300"></i>
                                        <p>Belum ada data laporan kinerja.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Script SweetAlert untuk Hapus --}}
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Laporan?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
</x-admin-layout>