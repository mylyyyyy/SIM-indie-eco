<x-admin-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate__animated animate__fadeInDown">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Tulis Berita Baru</h2>
            <p class="text-slate-500 font-medium">Buat artikel menarik untuk pengunjung website.</p>
        </div>
        <a href="{{ route('eco.news.index') }}" class="text-slate-500 hover:text-blue-600 font-bold text-sm flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('eco.news.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate__animated animate__fadeInUp">
            @csrf
            
            <div class="p-8 space-y-6">
                
                {{-- Judul --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Judul Berita</label>
                    <input type="text" name="judul" class="w-full px-4 py-3 border-slate-200 rounded-xl text-lg font-bold text-slate-800 focus:ring-2 focus:ring-blue-500 transition-all placeholder:text-slate-300" placeholder="Contoh: Syafa Group Raih Penghargaan..." required>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Status Publikasi</label>
                    <select name="status" class="w-full md:w-1/2 px-4 py-3 border-slate-200 rounded-xl text-sm font-semibold text-slate-600 focus:ring-blue-500">
                        <option value="draft">Simpan sebagai Draft</option>
                        <option value="publish">Langsung Terbitkan (Publish)</option>
                    </select>
                </div>

                {{-- Upload Gambar (AlpineJS Preview) --}}
                <div x-data="{ photoName: null, photoPreview: null }">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Gambar Utama</label>
                    
                    <input type="file" name="gambar" class="hidden" x-ref="photo"
                        x-on:change="
                            photoName = $refs.photo.files[0].name;
                            const reader = new FileReader();
                            reader.onload = (e) => { photoPreview = e.target.result; };
                            reader.readAsDataURL($refs.photo.files[0]);
                        " />

                    <div class="flex items-start gap-6">
                        {{-- Preview Area --}}
                        <div class="w-40 h-40 bg-slate-50 border-2 border-dashed border-slate-300 rounded-2xl flex items-center justify-center overflow-hidden relative group cursor-pointer hover:border-blue-400 transition-all"
                             @click.prevent="$refs.photo.click()">
                            
                            {{-- Placeholder --}}
                            <div class="text-center p-4" x-show="!photoPreview">
                                <i class="fas fa-cloud-upload-alt text-3xl text-slate-300 mb-2 group-hover:text-blue-400 transition-colors"></i>
                                <span class="block text-[10px] text-slate-400 font-bold uppercase">Upload Foto</span>
                            </div>

                            {{-- Image Preview --}}
                            <img x-show="photoPreview" :src="photoPreview" class="w-full h-full object-cover" style="display: none;">
                        </div>

                        <div class="flex-1">
                            <p class="text-sm text-slate-600 mb-2">Klik kotak di samping untuk mengunggah gambar utama berita.</p>
                            <p class="text-xs text-slate-400 bg-slate-50 p-2 rounded border border-slate-100 inline-block">
                                <i class="fas fa-info-circle mr-1"></i> Format: JPG/PNG, Max 2MB.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Isi Berita --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Isi Berita</label>
                    <textarea name="isi" rows="12" class="w-full px-4 py-3 border-slate-200 rounded-xl text-sm leading-relaxed text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Tulis isi berita di sini..."></textarea>
                </div>

            </div>

            {{-- Footer --}}
            <div class="bg-slate-50 px-8 py-5 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('eco.news.index') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50">Batal</a>
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                    <i class="fas fa-paper-plane mr-2"></i> Simpan Berita
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>