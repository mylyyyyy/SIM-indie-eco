<x-admin-layout>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Header --}}
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-800">Dashboard Kepala Kantor</h2>
        <p class="text-sm text-slate-500">Selamat datang, silakan input laporan kegiatan harian Anda.</p>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", showConfirmButton: false, timer: 1500 });
            });
        </script>
    @endif

    {{-- Error Alert --}}
    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memproses!',
                    text: "{{ $errors->first() }}",
                    confirmButtonColor: '#ef4444'
                });
            });
        </script>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- FORM INPUT (KIRI) --}}
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 sticky top-24">
                <h3 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fas fa-file-signature text-amber-500"></i> Form Tambah Laporan Harian
                </h3>

                <form id="lhForm" action="{{ route('kepala_kantor.lh.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(event, 'dokumentasi')">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal Laporan</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full md:w-1/2 px-4 py-2 border border-slate-200 rounded-xl focus:ring-amber-500 bg-slate-50" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Rincian Kegiatan</label>
                        <div id="kegiatan-wrapper" class="space-y-3">
                            <div class="flex gap-2">
                                <span class="bg-amber-100 text-amber-700 w-8 h-10 flex items-center justify-center rounded-lg font-bold shrink-0">1</span>
                                <input type="text" name="kegiatan[]" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-amber-500" placeholder="Contoh: Briefing pagi dengan tim..." required>
                            </div>
                        </div>
                        
                        <button type="button" onclick="tambahBaris('kegiatan-wrapper')" class="mt-3 text-sm text-amber-600 font-bold hover:text-amber-800 flex items-center gap-1 transition-colors">
                            <i class="fas fa-plus-circle"></i> Tambah Baris Kegiatan
                        </button>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Dokumentasi (Hanya Gambar: JPG/PNG)</label>
                        <input type="file" id="dokumentasi" name="dokumentasi" accept="image/png, image/jpeg, image/jpg" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-colors">
                        <p class="text-[10px] text-slate-400 mt-2">*Maksimal ukuran file: 2MB.</p>
                    </div>

                    <div class="border-t border-slate-100 pt-4">
                        <button type="submit" class="bg-gradient-to-r from-amber-500 to-orange-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-amber-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all w-full md:w-auto">
                            Simpan Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- HISTORY (KANAN) --}}
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 min-h-[500px]">
                <h3 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Riwayat Terakhir</h3>
                
                <div class="space-y-4 max-h-[calc(100vh-15rem)] overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($lhs as $lh)
                        <div x-data="{ openEdit: false }" class="p-4 bg-slate-50 rounded-xl border border-slate-100 hover:border-amber-200 transition-all group relative">
                            
                           {{-- Action Buttons (Edit & Delete) --}}
<div class="absolute top-3 right-3 flex gap-2">
    <button @click="openEdit = true" class="w-7 h-7 bg-blue-100 text-blue-600 rounded flex items-center justify-center hover:bg-blue-600 hover:text-white shadow-sm transition-colors" title="Edit">
        <i class="fas fa-edit text-xs"></i>
    </button>
    <form action="{{ route('kepala_kantor.lh.destroy', $lh->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?');" class="inline">
        @csrf @method('DELETE')
        <button type="submit" class="w-7 h-7 bg-red-100 text-red-600 rounded flex items-center justify-center hover:bg-red-600 hover:text-white shadow-sm transition-colors" title="Hapus">
            <i class="fas fa-trash text-xs"></i>
        </button>
    </form>
</div>

                            <div class="text-xs text-slate-400 mb-2 font-medium flex items-center gap-1">
                                <i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($lh->tanggal)->format('d F Y') }}
                            </div>
                            
                            <div class="text-sm font-bold text-slate-700 leading-tight pr-16">
                                @php 
                                    $kegiatans = is_string($lh->rincian_kegiatan) ? json_decode($lh->rincian_kegiatan, true) : $lh->rincian_kegiatan;
                                    if (!is_array($kegiatans)) $kegiatans = [];
                                    echo $kegiatans[0] ?? 'Tidak ada detail';
                                @endphp
                            </div>
                            
                            @if(count($kegiatans) > 1)
                                <div class="text-[10px] font-bold text-amber-600 bg-amber-50 inline-block px-2 py-0.5 rounded mt-2">
                                    + {{ count($kegiatans) - 1 }} kegiatan lainnya
                                </div>
                            @endif
                            
                            @if($lh->dokumentasi)
                                <div class="mt-3 flex items-center gap-1 text-[10px] text-slate-500 border-t border-slate-200/50 pt-2">
                                    <i class="fas fa-image text-emerald-500"></i> Ada Lampiran Foto
                                </div>
                            @endif

                            {{-- ================= MODAL EDIT ================= --}}
                            <div x-show="openEdit" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm px-4">
                                <div @click.away="openEdit = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden animate__animated animate__fadeInUp animate__faster">
                                    
                                    <div class="flex justify-between items-center p-5 border-b border-slate-100 bg-slate-50">
                                        <h3 class="font-bold text-slate-800 text-lg">Edit Laporan Harian</h3>
                                        <button @click="openEdit = false" class="text-slate-400 hover:text-red-500 transition-colors">
                                            <i class="fas fa-times text-xl"></i>
                                        </button>
                                    </div>
                                    
                                    <div class="p-6 overflow-y-auto flex-1 custom-scrollbar">
                                        <form id="editForm{{ $lh->id }}" action="{{ route('kepala_kantor.lh.update', $lh->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(event, 'dokumentasi_edit_{{ $lh->id }}')">
                                            @csrf @method('PUT')
                                            
                                            <div class="mb-4">
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal Laporan</label>
                                                <input type="date" name="tanggal" value="{{ $lh->tanggal }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-amber-500 bg-slate-50" required>
                                            </div>

                                            <div class="mb-4">
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Rincian Kegiatan</label>
                                                <div id="kegiatan-edit-wrapper-{{ $lh->id }}" class="space-y-3">
                                                    @foreach($kegiatans as $index => $keg)
                                                        <div class="flex gap-2">
                                                            <span class="bg-amber-100 text-amber-700 w-8 h-10 flex items-center justify-center rounded-lg font-bold shrink-0">{{ $index + 1 }}</span>
                                                            <input type="text" name="kegiatan[]" value="{{ $keg }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-amber-500" required>
                                                            @if($index > 0)
                                                                <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 px-2"><i class="fas fa-trash"></i></button>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <button type="button" onclick="tambahBaris('kegiatan-edit-wrapper-{{ $lh->id }}')" class="mt-3 text-sm text-amber-600 font-bold hover:text-amber-800 flex items-center gap-1 transition-colors">
                                                    <i class="fas fa-plus-circle"></i> Tambah Baris Kegiatan
                                                </button>
                                            </div>

                                            <div class="mb-6 p-4 border border-dashed border-slate-300 rounded-xl bg-slate-50">
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Ganti Dokumentasi (Opsional)</label>
                                                @if($lh->dokumentasi)
                                                    <div class="mb-3 flex items-center gap-2 text-xs text-emerald-600 font-bold bg-emerald-50 p-2 rounded border border-emerald-100">
                                                        <i class="fas fa-check-circle"></i> File saat ini: {{ Str::limit($lh->dokumentasi, 20) }}
                                                    </div>
                                                @endif
                                                <input type="file" id="dokumentasi_edit_{{ $lh->id }}" name="dokumentasi" accept="image/png, image/jpeg, image/jpg" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-white file:text-slate-700 hover:file:bg-slate-200 border border-slate-200 rounded-xl bg-white cursor-pointer">
                                                <p class="text-[10px] text-slate-400 mt-2">*Biarkan kosong jika tidak ingin mengubah gambar.</p>
                                            </div>
                                            
                                            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                                                <button type="button" @click="openEdit = false" class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:bg-slate-100 rounded-xl transition-colors">Batal</button>
                                                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow transition-colors">
                                                    Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            {{-- ================= END MODAL EDIT ================= --}}

                        </div>
                    @empty
                        <div class="text-center py-10">
                            <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                                <i class="fas fa-folder-open text-xl"></i>
                            </div>
                            <p class="text-slate-400 text-sm font-medium">Belum ada laporan bulan ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    <script>
        function tambahBaris(wrapperId) {
            const wrapper = document.getElementById(wrapperId);
            const count = wrapper.children.length + 1;
            const div = document.createElement('div');
            div.className = 'flex gap-2 mt-2'; 
            div.innerHTML = `
                <span class="bg-amber-100 text-amber-700 w-8 h-10 flex items-center justify-center rounded-lg font-bold shrink-0">${count}</span>
                <input type="text" name="kegiatan[]" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-amber-500" placeholder="Kegiatan selanjutnya..." required>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 px-2 transition-colors"><i class="fas fa-trash"></i></button>
            `;
            wrapper.appendChild(div);
            
            // Rekalkulasi nomor urut
            updateNomorUrut(wrapperId);
        }

        function updateNomorUrut(wrapperId) {
            const wrapper = document.getElementById(wrapperId);
            Array.from(wrapper.children).forEach((child, index) => {
                const numberSpan = child.querySelector('span');
                if(numberSpan) numberSpan.innerText = index + 1;
            });
        }

        // VALIDASI FILE SEBELUM UPLOAD (Dinamis untuk ID input tertentu)
        function validateForm(e, inputId) {
            const fileInput = document.getElementById(inputId);
            if (!fileInput) return true; // Lolos jika input tidak ditemukan

            const file = fileInput.files[0];

            if (file) {
                const allowedExtensions = ['image/jpeg', 'image/png', 'image/jpg'];
                
                if (!allowedExtensions.includes(file.type)) {
                    e.preventDefault(); 
                    Swal.fire({
                        icon: 'warning',
                        title: 'Format File Salah!',
                        text: 'Silakan upload file berupa gambar (JPG, JPEG, atau PNG).',
                        confirmButtonColor: '#f59e0b'
                    });
                    fileInput.value = ''; 
                    return false;
                }

                if (file.size > 2 * 1024 * 1024) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'File Terlalu Besar!',
                        text: 'Ukuran maksimal gambar adalah 2MB.',
                        confirmButtonColor: '#f59e0b'
                    });
                    fileInput.value = ''; 
                    return false;
                }
            }
            return true; 
        }
    </script>
</x-admin-layout>