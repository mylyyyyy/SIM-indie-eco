<x-admin-layout>
    {{-- Load SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate__animated animate__fadeInDown">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Daftar Proyek</h2>
            <p class="text-slate-500 font-medium">Monitor dan kelola proyek yang sedang berjalan.</p>
        </div>
        
        <div class="flex gap-3 w-full md:w-auto">
            {{-- Search Bar --}}
            <div class="relative w-full md:w-64 group">
                <input type="text" id="searchInput" placeholder="Cari proyek..." 
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm transition-all group-hover:border-blue-300">
                <i class="fas fa-search absolute left-3 top-3 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
            </div>

            <button x-data @click="$dispatch('open-modal', 'add-project')" 
                class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                <i class="fas fa-plus-circle"></i> <span class="hidden md:inline">Buat Proyek</span>
            </button>
        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            });
        </script>
    @endif

    {{-- TABEL PROYEK --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate__animated animate__fadeInUp">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600" id="projectTable">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Nama Proyek</th>
                        <th class="px-6 py-4">Lokasi</th>
                        <th class="px-6 py-4">Periode & Durasi</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($projects as $p)
                    <tr class="hover:bg-blue-50/50 transition-colors group">
                        {{-- Kolom 1: Nama --}}
                        <td class="px-6 py-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-lg shrink-0">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div>
                                    <span class="font-bold text-slate-800 block text-base">{{ $p->project_name }}</span>
                                    <span class="text-xs text-slate-400 line-clamp-1">{{ $p->description ?? 'Tidak ada deskripsi' }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Kolom 2: Lokasi --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 text-slate-600">
                                <i class="fas fa-map-marker-alt text-red-400"></i>
                                <span class="font-medium">{{ $p->location }}</span>
                            </div>
                        </td>

                        {{-- Kolom 3: Tanggal --}}
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-bold text-slate-700">
                                    {{ \Carbon\Carbon::parse($p->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($p->end_date)->format('d M Y') }}
                                </span>
                                <span class="text-[10px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full w-fit">
                                    <i class="fas fa-clock mr-1"></i> 
                                    {{ \Carbon\Carbon::parse($p->start_date)->diffInDays(\Carbon\Carbon::parse($p->end_date)) }} Hari
                                </span>
                            </div>
                        </td>

                        {{-- Kolom 4: Status --}}
                        <td class="px-6 py-4">
                            @if($p->status == 'berjalan')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold border border-emerald-200 uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span> Berjalan
                                </span>
                            @elseif($p->status == 'selesai')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200 uppercase">
                                    <i class="fas fa-check-circle"></i> Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold border border-amber-200 uppercase">
                                    <i class="fas fa-pause-circle"></i> Tertunda
                                </span>
                            @endif
                        </td>

                        {{-- Kolom 5: Aksi --}}
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <button x-data @click="$dispatch('open-modal', 'edit-project-{{ $p->id }}')" 
                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-blue-600 hover:text-white hover:border-blue-600 flex items-center justify-center transition-all shadow-sm" title="Edit">
                                    <i class="fas fa-pen text-xs"></i>
                                </button>
                                
                                <button type="button" onclick="confirmDelete('{{ $p->id }}')"
                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white hover:border-red-600 flex items-center justify-center transition-all shadow-sm" title="Hapus">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                                
                                <form id="delete-form-{{ $p->id }}" action="{{ route('admin.projects.destroy', $p->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- ================= MODAL EDIT (Looping) ================= --}}
                    <x-modal name="edit-project-{{ $p->id }}" focusable>
                        <form method="POST" action="{{ route('admin.projects.update', $p->id) }}" class="bg-white rounded-2xl flex flex-col max-h-[90vh] shadow-2xl">
                            @csrf @method('PUT')

                            {{-- Header --}}
                            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-8 py-5 flex justify-between items-center shrink-0 rounded-t-2xl">
                                <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                    <i class="fas fa-edit text-blue-400"></i> Edit Proyek
                                </h2>
                                <button type="button" x-on:click="$dispatch('close')" class="text-slate-400 hover:text-white transition-colors">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>

                            {{-- Content --}}
                            <div class="p-8 overflow-y-auto custom-scrollbar flex-1">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Nama Proyek</label>
                                        <input type="text" name="project_name" value="{{ $p->project_name }}" class="w-full px-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-bold text-slate-700 bg-slate-50" required>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Lokasi</label>
                                        <input type="text" name="location" value="{{ $p->location }}" class="w-full px-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Tanggal Mulai</label>
                                        <input type="date" name="start_date" value="{{ $p->start_date }}" class="w-full px-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Tanggal Selesai</label>
                                        <input type="date" name="end_date" value="{{ $p->end_date }}" class="w-full px-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-blue-500" required>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Status Proyek</label>
                                        <select name="status" class="w-full px-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-blue-500">
                                            <option value="berjalan" {{ $p->status == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                            <option value="tertunda" {{ $p->status == 'tertunda' ? 'selected' : '' }}>Tertunda</option>
                                            <option value="selesai" {{ $p->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Deskripsi (Opsional)</label>
                                        <textarea name="description" rows="3" class="w-full px-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-blue-500">{{ $p->description }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Footer --}}
                            <div class="px-8 py-5 border-t border-slate-100 flex justify-end gap-3 bg-white rounded-b-2xl shrink-0">
                                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200">Batal</button>
                                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 shadow-lg flex items-center gap-2">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </x-modal>

                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-300">
                                <i class="fas fa-clipboard-list text-5xl mb-3"></i>
                                <p class="font-medium">Belum ada proyek yang dibuat.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= MODAL TAMBAH (Sticky Layout) ================= --}}
    <x-modal name="add-project" focusable>
        <form action="{{ route('admin.projects.store') }}" method="POST" class="bg-white rounded-2xl flex flex-col max-h-[90vh] shadow-2xl">
            @csrf
            
            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-5 flex justify-between items-center shrink-0 rounded-t-2xl">
                <h2 class="text-xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-plus-square text-blue-200"></i> Buat Proyek Baru
                </h2>
                <button type="button" x-on:click="$dispatch('close')" class="text-blue-200 hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- Content --}}
            <div class="p-8 overflow-y-auto custom-scrollbar flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Nama Proyek</label>
                        <div class="relative group">
                            <i class="fas fa-building absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            <input type="text" name="project_name" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-bold text-slate-700 bg-slate-50/50" placeholder="Contoh: Renovasi Gedung A" required>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Lokasi</label>
                        <div class="relative group">
                            <i class="fas fa-map-marker-alt absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            <input type="text" name="location" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Jakarta Selatan" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="w-full px-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="w-full px-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-blue-500" required>
                    </div>
                    
                    {{-- Default Status (Hidden or Select) --}}
                    <input type="hidden" name="status" value="berjalan">

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Deskripsi (Opsional)</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-blue-500" placeholder="Keterangan singkat proyek..."></textarea>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-8 py-5 border-t border-slate-100 flex justify-end gap-3 bg-white rounded-b-2xl shrink-0">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl text-sm font-bold hover:from-blue-700 hover:to-indigo-700 shadow-lg flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Proyek
                </button>
            </div>
        </form>
    </x-modal>

    {{-- Script & Style (Sama dengan User) --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; margin-block: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#projectTable tbody tr');
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Proyek?',
                text: "Data akan dihapus permanen!",
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