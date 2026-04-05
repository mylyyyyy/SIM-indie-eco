<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Laporan Harian (LH)</h2>
            <p class="text-sm text-slate-500">Input aktivitas harian Kepala Kantor cabang {{ Auth::user()->company_name ?? 'Pusat' }}.</p>
        </div>
    </div>

    {{-- Alert Sukses --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", showConfirmButton: false, timer: 2000, toast: true, position: 'top-end' });
            });
        </script>
    @endif

    {{-- Alert Error Validasi --}}
    @if($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 border border-red-100 font-medium text-sm">
            <div class="flex items-center gap-2 mb-2 font-bold">
                <i class="fas fa-exclamation-triangle"></i> Terjadi Kesalahan:
            </div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ========================================= --}}
    {{-- BAGIAN 1: FORM INPUT LAPORAN --}}
    {{-- ========================================= --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-10">
        <h3 class="text-lg font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2"><i class="fas fa-edit mr-2 text-amber-500"></i> Buat Laporan Baru</h3>
        <form action="{{ route('kepala_kantor.lh.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal Laporan</label>
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full md:w-1/3 px-4 py-2 border border-slate-200 rounded-xl focus:ring-amber-500 focus:border-amber-500 transition-colors" required>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Rincian Kegiatan</label>
                <div id="kegiatan-wrapper" class="space-y-3">
                    <div class="flex gap-2">
                        <span class="bg-amber-100 text-amber-700 w-8 h-10 flex items-center justify-center rounded-lg font-bold shrink-0">1</span>
                        <input type="text" name="kegiatan[]" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-amber-500 focus:border-amber-500 transition-colors" placeholder="Tuliskan kegiatan..." required>
                    </div>
                </div>
                
                <button type="button" onclick="tambahBaris()" class="mt-3 text-sm text-amber-600 font-bold hover:text-amber-800 flex items-center gap-1 transition-colors">
                    <i class="fas fa-plus-circle"></i> Tambah Baris Kegiatan
                </button>
            </div>

            <div class="mb-8">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Dokumentasi (Bisa pilih lebih dari 1 foto)</label>
                <div class="bg-amber-50 border border-amber-100 p-4 rounded-xl">
                    <p class="text-[10px] text-amber-600 mb-3 font-semibold">
                        <i class="fas fa-info-circle mr-1"></i> Tekan tahan <kbd class="bg-white px-1 py-0.5 rounded border border-amber-200">CTRL</kbd> (Windows) atau <kbd class="bg-white px-1 py-0.5 rounded border border-amber-200">CMD</kbd> (Mac) saat memilih untuk upload banyak foto.
                    </p>
                    
                    <input type="file" id="file-input" name="dokumentasi[]" multiple accept="image/jpeg,image/png,image/jpg" onchange="previewImages()" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-white file:text-amber-700 hover:file:bg-amber-100 transition-colors cursor-pointer">
                    
                    {{-- Container Live Preview --}}
                    <div id="image-preview-container" class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-3 empty:hidden"></div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-amber-500/30 hover:from-amber-600 hover:to-orange-600 transition-all w-full md:w-auto flex justify-center items-center gap-2">
                    <i class="fas fa-paper-plane"></i> Simpan Laporan
                </button>
            </div>
        </form>
    </div>

    {{-- ========================================= --}}
    {{-- BAGIAN 2: TABEL RIWAYAT LAPORAN --}}
    {{-- ========================================= --}}
    <div>
        <h3 class="text-xl font-black text-slate-800 mb-4"><i class="fas fa-history text-amber-500 mr-2"></i> Riwayat Laporan Terakhir</h3>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 min-w-[300px]">Rincian Kegiatan</th>
                            <th class="px-6 py-4">Dokumentasi Foto</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($lhs as $item)
                        <tr class="hover:bg-amber-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800 whitespace-nowrap align-top">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 align-top">
                                @php 
                                    // Parse JSON kegiatan menjadi array
                                    $kegiatans = json_decode($item->rincian_kegiatan, true) ?? []; 
                                @endphp
                                <ol class="list-decimal pl-4 space-y-1.5 text-xs text-slate-700">
                                    @foreach($kegiatans as $keg)
                                        <li>{{ $keg }}</li>
                                    @endforeach
                                </ol>
                            </td>
                            <td class="px-6 py-4 align-top">
                                @if($item->fotos && $item->fotos->count() > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($item->fotos as $foto)
                                            <a href="{{ asset('storage/' . $foto->path_foto) }}" target="_blank" class="block group relative">
                                                <img src="{{ asset('storage/' . $foto->path_foto) }}" class="w-12 h-12 object-cover rounded-lg border border-slate-200 shadow-sm group-hover:scale-110 transition-transform">
                                            </a>
                                        @endforeach
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-2">{{ $item->fotos->count() }} Foto terlampir</p>
                                @else
                                    <span class="text-xs text-slate-400 italic bg-slate-50 px-2 py-1 rounded">Tanpa Foto</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center align-top">
                                <form action="{{ route('kepala_kantor.lh.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus laporan ini beserta semua fotonya?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white transition-all shadow-sm flex items-center justify-center mx-auto" title="Hapus Laporan">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-medium">
                                <i class="fas fa-folder-open text-4xl mb-3 block text-slate-300"></i> Belum ada riwayat laporan harian.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Script Dynamic Input & Live Preview --}}
    <script>
        function tambahBaris() {
            const wrapper = document.getElementById('kegiatan-wrapper');
            const count = wrapper.children.length + 1;
            
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <span class="bg-amber-100 text-amber-700 w-8 h-10 flex items-center justify-center rounded-lg font-bold shrink-0">${count}</span>
                <input type="text" name="kegiatan[]" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-amber-500 focus:border-amber-500 transition-colors" placeholder="Tuliskan kegiatan..." required>
                <button type="button" onclick="hapusBaris(this)" class="text-red-400 hover:text-red-600 px-3 bg-red-50 hover:bg-red-100 rounded-xl transition-colors" title="Hapus Baris"><i class="fas fa-trash"></i></button>
            `;
            wrapper.appendChild(div);
        }

        function hapusBaris(btn) {
            btn.parentElement.remove();
            updateNomor();
        }

        function updateNomor() {
            const wrapper = document.getElementById('kegiatan-wrapper');
            const rows = wrapper.children;
            for (let i = 0; i < rows.length; i++) {
                rows[i].querySelector('span').innerText = i + 1;
            }
        }

        function previewImages() {
            const previewContainer = document.getElementById('image-preview-container');
            const fileInput = document.getElementById('file-input');
            
            previewContainer.innerHTML = ''; 

            if (fileInput.files) {
                Array.from(fileInput.files).forEach(file => {
                    if (file.type.match('image.*')) {
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            const imgDiv = document.createElement('div');
                            imgDiv.className = 'relative group rounded-lg overflow-hidden border border-amber-200 shadow-sm';
                            imgDiv.innerHTML = `
                                <img src="${e.target.result}" class="w-full h-24 object-cover">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[10px] font-bold text-center p-2">
                                    <span class="truncate w-full drop-shadow-md">${file.name}</span>
                                </div>
                            `;
                            previewContainer.appendChild(imgDiv);
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        }
    </script>
</x-admin-layout>