<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate__animated animate__fadeInDown">
        <div>
            <h2 class="text-3xl font-black text-emerald-800 tracking-tight">Kegiatan & Distribusi Indie</h2>
            <p class="text-slate-500 font-medium">Dokumentasi penjualan dan distribusi produk pangan.</p>
        </div>
        
        <div class="flex gap-3 w-full md:w-auto">
            <div class="relative w-full md:w-64 group">
                <input type="text" id="searchInput" placeholder="Cari kegiatan..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 text-sm shadow-sm transition-all group-hover:border-emerald-300">
                <i class="fas fa-search absolute left-3 top-3 text-slate-400 group-focus-within:text-emerald-500 transition-colors"></i>
            </div>

            <button x-data @click="$dispatch('open-modal', 'add-portfolio-modal')" 
                class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-emerald-500/30 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                <i class="fas fa-plus-circle"></i> <span class="hidden md:inline">Tambah Data</span>
            </button>
        </div>
    </div>

    {{-- SUCCESS ALERT --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
            });
        </script>
    @endif

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 overflow-hidden animate__animated animate__fadeInUp">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600" id="portfolioTable">
                <thead class="bg-emerald-50 border-b border-emerald-100 text-xs uppercase font-bold text-emerald-700 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Kegiatan & Jenis</th>
                        <th class="px-6 py-4">Pelanggan & Tanggal</th>
                        <th class="px-6 py-4">Lokasi & Keterangan</th>
                        <th class="px-6 py-4 text-center">Gambar</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($portfolios as $item)
                    <tr class="hover:bg-emerald-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div>
                                <span class="font-bold text-slate-800 block text-base">{{ $item->title }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase mt-1">
                                    {{ $item->category }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-bold text-slate-700"><i class="fas fa-user-tie w-4 text-slate-400"></i> {{ $item->client_name ?? '-' }}</span>
                                <span class="text-xs text-slate-500"><i class="fas fa-calendar w-4 text-slate-400"></i> {{ $item->completion_date ? \Carbon\Carbon::parse($item->completion_date)->format('d M Y') : '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="mb-1 text-xs font-bold text-slate-600"><i class="fas fa-map-marker-alt w-4 text-slate-400"></i> {{ $item->location ?? '-' }}</div>
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
                                <a href="{{ route('indie.portfolios.edit', $item->id) }}" 
                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 flex items-center justify-center transition-all shadow-sm">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                
                                <form action="{{ route('indie.portfolios.destroy', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this)"
                                        class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white hover:border-red-600 flex items-center justify-center transition-all shadow-sm">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada data kegiatan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <x-modal name="add-portfolio-modal" focusable>
        <form method="POST" action="{{ route('indie.portfolios.store') }}" enctype="multipart/form-data" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
            @csrf
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                <h3 class="text-white font-bold text-lg"><i class="fas fa-leaf text-emerald-200 mr-2"></i> Tambah Kegiatan</h3>
                <button type="button" x-on:click="$dispatch('close')" class="text-emerald-200 hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Judul Kegiatan</label>
                        <input type="text" name="title" class="w-full border-slate-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="Contoh: Pengiriman Beras ke Toko A" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Jenis Penjualan</label>
                            <input type="text" name="category" class="w-full border-slate-200 rounded-xl text-sm focus:ring-emerald-500" placeholder="Contoh: Grosir / Eceran" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                            <input type="date" name="completion_date" class="w-full border-slate-200 rounded-xl text-sm focus:ring-emerald-500" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Pelanggan</label>
                        <input type="text" name="client_name" class="w-full border-slate-200 rounded-xl text-sm focus:ring-emerald-500" placeholder="Nama Toko / Perorangan">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tujuan Distribusi (Lokasi)</label>
                        <input type="text" name="location" class="w-full border-slate-200 rounded-xl text-sm focus:ring-emerald-500" placeholder="Nama Desa / Kota">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Keterangan (Volume, Jenis Beras)</label>
                        <textarea name="description" rows="3" class="w-full border-slate-200 rounded-xl text-sm focus:ring-emerald-500" placeholder="Detail pengiriman..."></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Upload Gambar</label>
                        <input type="file" name="image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3 bg-slate-50 rounded-b-2xl shrink-0">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-0.5">Simpan Data</button>
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
                title: 'Hapus Data?', text: "Tidak bisa dikembalikan!", icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#ef4444', cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) { btn.closest('form').submit(); }
            })
        }
    </script>
</x-admin-layout>