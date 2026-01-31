<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate__animated animate__fadeInDown">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Portofolio Proyek</h2>
            <p class="text-slate-500 font-medium">Kelola showcase hasil pekerjaan perusahaan.</p>
        </div>
        
        <div class="flex gap-3 w-full md:w-auto">
            {{-- Search --}}
            <div class="relative w-full md:w-64 group">
                <input type="text" id="searchInput" placeholder="Cari portofolio..." 
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm transition-all group-hover:border-blue-300">
                <i class="fas fa-search absolute left-3 top-3 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
            </div>

            <button x-data @click="$dispatch('open-modal', 'add-portfolio-modal')" 
                class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                <i class="fas fa-plus-circle"></i> <span class="hidden md:inline">Tambah</span>
            </button>
        </div>
    </div>

    {{-- SUCCESS ALERT --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success', title: 'Berhasil', text: "{{ session('success') }}",
                    timer: 2000, showConfirmButton: false, toast: true, position: 'top-end'
                });
            });
        </script>
    @endif

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate__animated animate__fadeInUp">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600" id="portfolioTable">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Proyek & Kategori</th>
                        <th class="px-6 py-4">Klien & Tanggal</th>
                        <th class="px-6 py-4">Deskripsi Singkat</th>
                        <th class="px-6 py-4 text-center">Gambar</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($portfolios as $item)
                    <tr class="hover:bg-blue-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div>
                                <span class="font-bold text-slate-800 block text-base">{{ $item->title }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200 uppercase mt-1">
                                    {{ $item->category }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-bold text-slate-700"><i class="fas fa-user-tie w-4 text-slate-400"></i> {{ $item->client_name ?? '-' }}</span>
                                <span class="text-xs text-slate-500"><i class="fas fa-calendar w-4 text-slate-400"></i> {{ $item->completion_date ? $item->completion_date->format('d M Y') : '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs text-slate-500 line-clamp-2 max-w-[200px]">{{ $item->description ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->image_path)
                                <img src="{{ $item->image_path }}" class="h-12 w-20 object-cover rounded-lg border border-slate-200 shadow-sm mx-auto hover:scale-150 transition-transform cursor-pointer">
                            @else
                                <span class="text-xs text-slate-400 italic">No Image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <button x-data @click="$dispatch('open-modal', 'edit-portfolio-{{ $item->id }}')" 
                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-blue-600 hover:text-white hover:border-blue-600 flex items-center justify-center transition-all shadow-sm">
                                    <i class="fas fa-pen text-xs"></i>
                                </button>
                                
                                <form action="{{ route('admin.portfolios.destroy', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this)"
                                        class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white hover:border-red-600 flex items-center justify-center transition-all shadow-sm">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- MODAL EDIT --}}
                    <x-modal name="edit-portfolio-{{ $item->id }}" focusable>
                        <form method="POST" action="{{ route('admin.portfolios.update', $item->id) }}" enctype="multipart/form-data" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
                            @csrf @method('PUT')
                            <div class="bg-slate-900 px-6 py-4 flex justify-between items-center rounded-t-2xl">
                                <h3 class="text-white font-bold">Edit Portofolio</h3>
                                <button type="button" x-on:click="$dispatch('close')" class="text-slate-400 hover:text-white"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="p-6 overflow-y-auto custom-scrollbar">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Judul Proyek</label>
                                        <input type="text" name="title" value="{{ $item->title }}" class="w-full border-slate-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500" required>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kategori</label>
                                            <input type="text" name="category" value="{{ $item->category }}" class="w-full border-slate-200 rounded-xl text-sm" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal Selesai</label>
                                            <input type="date" name="completion_date" value="{{ $item->completion_date ? $item->completion_date->format('Y-m-d') : '' }}" class="w-full border-slate-200 rounded-xl text-sm" required>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Klien</label>
                                        <input type="text" name="client_name" value="{{ $item->client_name }}" class="w-full border-slate-200 rounded-xl text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Deskripsi</label>
                                        <textarea name="description" rows="3" class="w-full border-slate-200 rounded-xl text-sm">{{ $item->description }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ganti Gambar (Opsional)</label>
                                        <input type="file" name="image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    </div>
                                </div>
                            </div>
                            <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3 bg-slate-50 rounded-b-2xl">
                                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700">Simpan Perubahan</button>
                            </div>
                        </form>
                    </x-modal>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada portofolio.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <x-modal name="add-portfolio-modal" focusable>
        <form method="POST" action="{{ route('admin.portfolios.store') }}" enctype="multipart/form-data" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
            @csrf
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex justify-between items-center rounded-t-2xl">
                <h3 class="text-white font-bold text-lg"><i class="fas fa-briefcase text-blue-300 mr-2"></i> Tambah Portofolio</h3>
                <button type="button" x-on:click="$dispatch('close')" class="text-blue-200 hover:text-white"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Judul Proyek</label>
                        <input type="text" name="title" class="w-full border-slate-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Pembangunan Gudang A" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kategori</label>
                            <input type="text" name="category" class="w-full border-slate-200 rounded-xl text-sm" placeholder="Contoh: Konstruksi" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal Selesai</label>
                            <input type="date" name="completion_date" class="w-full border-slate-200 rounded-xl text-sm" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Klien</label>
                        <input type="text" name="client_name" class="w-full border-slate-200 rounded-xl text-sm" placeholder="Nama PT / Perorangan">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full border-slate-200 rounded-xl text-sm" placeholder="Detail pekerjaan..."></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Gambar Utama</label>
                        <input type="file" name="image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3 bg-slate-50 rounded-b-2xl">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700">Simpan Data</button>
            </div>
        </form>
    </x-modal>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#portfolioTable tbody tr');
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        function confirmDelete(btn) {
            Swal.fire({
                title: 'Hapus Portofolio?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#ef4444', cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) { btn.closest('form').submit(); }
            })
        }
    </script>
</x-admin-layout>