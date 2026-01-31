<x-admin-layout>
    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate__animated animate__fadeInDown">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Kelola Berita</h2>
            <p class="text-slate-500 font-medium">Daftar artikel dan berita yang ditampilkan di website utama.</p>
        </div>
        
        <div class="flex gap-3 w-full md:w-auto">
            {{-- Search --}}
            <div class="relative w-full md:w-64 group">
                <input type="text" id="searchInput" placeholder="Cari judul..." 
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm transition-all">
                <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
            </div>

            <a href="{{ route('eco.news.create') }}" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                <i class="fas fa-plus-circle"></i> <span class="hidden md:inline">Tulis Berita</span>
            </a>
        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
                });
                Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
            });
        </script>
    @endif

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate__animated animate__fadeInUp">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600" id="newsTable">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4 w-20">Gambar</th>
                        <th class="px-6 py-4">Judul Berita</th>
                        <th class="px-6 py-4">Penulis & Tanggal</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($berita as $item)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        {{-- Gambar --}}
                        <td class="px-6 py-4">
                            <div class="w-16 h-16 rounded-lg overflow-hidden bg-slate-100 border border-slate-200">
                                @if($item->gambar)
                                    <img src="{{ $item->gambar }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full text-slate-400"><i class="fas fa-image"></i></div>
                                @endif
                            </div>
                        </td>

                        {{-- Judul --}}
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800 block text-base line-clamp-2">{{ $item->judul }}</span>
                            <span class="text-xs text-slate-400">{{ Str::limit(strip_tags($item->isi), 50) }}</span>
                        </td>

                        {{-- Info --}}
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-bold text-slate-700 flex items-center gap-2">
                                    <i class="fas fa-user-circle text-blue-500"></i> {{ $item->penulis }}
                                </span>
                                <span class="text-[10px] text-slate-500">
                                    {{ \Carbon\Carbon::parse($item->tanggal_publish)->format('d M Y, H:i') }}
                                </span>
                            </div>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4">
                            @if($item->status == 'publish')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span> Terbit
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">
                                    <i class="fas fa-pencil-alt"></i> Draft
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('eco.news.edit', $item->id_berita) }}" 
                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-blue-600 hover:text-white hover:border-blue-600 flex items-center justify-center transition-all shadow-sm" title="Edit">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                
                                <button type="button" onclick="confirmDelete('{{ $item->id_berita }}')"
                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white hover:border-red-600 flex items-center justify-center transition-all shadow-sm" title="Hapus">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                                
                                <form id="delete-form-{{ $item->id_berita }}" action="{{ route('eco.news.destroy', $item->id_berita) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-300">
                                <i class="far fa-newspaper text-5xl mb-3"></i>
                                <p class="font-medium">Belum ada berita yang ditulis.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Fitur Pencarian Sederhana
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#newsTable tbody tr');
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        // SweetAlert Delete
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Berita?', text: "Data tidak bisa dikembalikan!", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
            })
        }
    </script>
</x-admin-layout>