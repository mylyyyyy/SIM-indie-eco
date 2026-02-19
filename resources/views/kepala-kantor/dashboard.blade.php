<x-admin-layout>
    {{-- Header --}}
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-800">Dashboard Kepala Kantor</h2>
        <p class="text-sm text-slate-500">Selamat datang, silakan input laporan kegiatan harian Anda.</p>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ icon: 'success', title: 'Tersimpan', text: "{{ session('success') }}", showConfirmButton: false, timer: 1500 });
            });
        </script>
    @endif

    {{-- Error Alert (dari Controller) --}}
    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Gagal!',
                    text: "{{ $errors->first() }}",
                    confirmButtonColor: '#ef4444'
                });
            });
        </script>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- FORM INPUT (KIRI) --}}
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <h3 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fas fa-file-signature text-amber-500"></i> Form Laporan Harian (LH)
                </h3>

                {{-- Tambahkan onsubmit untuk validasi sebelum kirim ke server --}}
                <form id="lhForm" action="{{ route('kepala_kantor.lh.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(event)">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal Laporan</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full md:w-1/2 px-4 py-2 border border-slate-200 rounded-xl focus:ring-amber-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Rincian Kegiatan</label>
                        <div id="kegiatan-wrapper" class="space-y-3">
                            <div class="flex gap-2">
                                <span class="bg-amber-100 text-amber-700 w-8 h-10 flex items-center justify-center rounded-lg font-bold shrink-0">1</span>
                                <input type="text" name="kegiatan[]" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-amber-500" placeholder="Contoh: Meeting dengan Manager Unit..." required>
                            </div>
                        </div>
                        
                        <button type="button" onclick="tambahBaris()" class="mt-3 text-sm text-amber-600 font-bold hover:text-amber-800 flex items-center gap-1">
                            <i class="fas fa-plus-circle"></i> Tambah Baris Kegiatan
                        </button>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Dokumentasi (Hanya Gambar: JPG/PNG)</label>
                        {{-- Tambahkan accept="image/*" dan ID --}}
                        <input type="file" id="dokumentasi" name="dokumentasi" accept="image/png, image/jpeg, image/jpg" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                        <p class="text-[10px] text-slate-400 mt-1">*Maksimal ukuran file: 2MB.</p>
                    </div>

                    <button type="submit" class="bg-gradient-to-r from-amber-500 to-orange-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-amber-500/30 hover:from-amber-600 hover:to-orange-700 transition-all w-full md:w-auto">
                        Simpan Laporan
                    </button>
                </form>
            </div>
        </div>

        {{-- HISTORY (KANAN) --}}
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 h-full">
                <h3 class="font-bold text-slate-800 mb-4">Riwayat Terakhir</h3>
                <div class="space-y-4">
                    @forelse($lhs as $lh)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 hover:bg-amber-50 transition-colors">
                            <div class="text-xs text-slate-400 mb-1">{{ \Carbon\Carbon::parse($lh->tanggal)->format('d M Y') }}</div>
                            <div class="text-sm font-bold text-slate-700 truncate">
                                @php 
                                    $kegiatans = is_string($lh->rincian_kegiatan) ? json_decode($lh->rincian_kegiatan, true) : $lh->rincian_kegiatan;
                                    if (!is_array($kegiatans)) $kegiatans = [];
                                    echo $kegiatans[0] ?? 'Tidak ada detail';
                                @endphp
                            </div>
                            @if(count($kegiatans) > 1)
                                <div class="text-[10px] text-amber-600 mt-1">+ {{ count($kegiatans) - 1 }} kegiatan lainnya</div>
                            @endif
                            
                            {{-- Tampilkan indikator jika ada foto --}}
                            @if($lh->dokumentasi)
                                <div class="mt-2 flex items-center gap-1 text-[10px] text-blue-500 font-bold">
                                    <i class="fas fa-image"></i> Ada Lampiran Foto
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-center text-slate-400 text-sm py-10">Belum ada laporan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function tambahBaris() {
            const wrapper = document.getElementById('kegiatan-wrapper');
            const count = wrapper.children.length + 1;
            const div = document.createElement('div');
            div.className = 'flex gap-2 mt-2'; // tambah margin top agar rapi
            div.innerHTML = `
                <span class="bg-amber-100 text-amber-700 w-8 h-10 flex items-center justify-center rounded-lg font-bold shrink-0">${count}</span>
                <input type="text" name="kegiatan[]" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-amber-500" placeholder="Kegiatan selanjutnya..." required>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 px-2"><i class="fas fa-trash"></i></button>
            `;
            wrapper.appendChild(div);
        }

        // VALIDASI FILE SEBELUM UPLOAD
        function validateForm(e) {
            const fileInput = document.getElementById('dokumentasi');
            const file = fileInput.files[0];

            if (file) {
                // Daftar ekstensi yang diizinkan (mimes: jpeg,png,jpg)
                const allowedExtensions = ['image/jpeg', 'image/png', 'image/jpg'];
                
                // Cek format file
                if (!allowedExtensions.includes(file.type)) {
                    e.preventDefault(); // Hentikan pengiriman form
                    Swal.fire({
                        icon: 'warning',
                        title: 'Format File Salah!',
                        text: 'Silakan upload file berupa gambar (JPG, JPEG, atau PNG).',
                        confirmButtonColor: '#f59e0b'
                    });
                    fileInput.value = ''; // Kosongkan input
                    return false;
                }

                // Cek ukuran file (Maksimal 2MB = 2 * 1024 * 1024 bytes)
                if (file.size > 2 * 1024 * 1024) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'File Terlalu Besar!',
                        text: 'Ukuran maksimal gambar adalah 2MB.',
                        confirmButtonColor: '#f59e0b'
                    });
                    fileInput.value = ''; // Kosongkan input
                    return false;
                }
            }
            return true; // Lanjutkan proses submit
        }
    </script>
</x-admin-layout>