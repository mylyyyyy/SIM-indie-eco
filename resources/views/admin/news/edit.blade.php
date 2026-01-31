<x-admin-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate__animated animate__fadeInDown">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Edit Berita</h2>
            <p class="text-slate-500 font-medium">Perbarui konten berita yang sudah ada.</p>
        </div>
        <a href="{{ route('admin.news.index') }}" class="text-slate-500 hover:text-blue-600 font-bold text-sm flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        {{-- Perhatikan route menggunakan ID Berita --}}
        <form action="{{ route('admin.news.update', $berita->id_berita) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate__animated animate__fadeInUp">
            @csrf
            @method('PUT')
            
            <div class="p-8 space-y-6">
                
                {{-- Judul --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Judul Berita</label>
                    <input type="text" name="judul" value="{{ $berita->judul }}" class="w-full px-4 py-3 border-slate-200 rounded-xl text-lg font-bold text-slate-800 focus:ring-2 focus:ring-blue-500 transition-all" required>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Status Publikasi</label>
                    <select name="status" class="w-full md:w-1/2 px-4 py-3 border-slate-200 rounded-xl text-sm font-semibold text-slate-600 focus:ring-blue-500">
                        <option value="draft" {{ $berita->status == 'draft' ? 'selected' : '' }}>Simpan sebagai Draft</option>
                        <option value="publish" {{ $berita->status == 'publish' ? 'selected' : '' }}>Terbitkan (Publish)</option>
                    </select>
                </div>

                {{-- Upload Gambar (AlpineJS Preview + Show Existing) --}}
                <div x-data="{ photoName: null, photoPreview: '{{ $berita->gambar }}' }">
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
                            
                            {{-- Jika tidak ada preview baru & tidak ada gambar lama --}}
                            <div class="text-center p-4" x-show="!photoPreview">
                                <i class="fas fa-camera text-3xl text-slate-300 mb-2"></i>
                                <span class="block text-[10px] text-slate-400 font-bold uppercase">Ganti Foto</span>
                            </div>

                            {{-- Image Preview (Base64 dari DB atau Upload Baru) --}}
                            <img x-show="photoPreview" :src="photoPreview" class="w-full h-full object-cover">
                            
                            {{-- Overlay Text --}}
                            <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                                <span class="text-white text-xs font-bold"><i class="fas fa-pen"></i> Ubah</span>
                            </div>
                        </div>

                        <div class="flex-1">
                            <p class="text-sm text-slate-600 mb-2">Klik gambar untuk mengubah foto berita.</p>
                            <p class="text-xs text-orange-500 bg-orange-50 p-2 rounded border border-orange-100 inline-block">
                                *Biarkan kosong jika tidak ingin mengubah gambar.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Isi Berita --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Isi Berita</label>
                    <textarea name="isi" rows="12" class="w-full px-4 py-3 border-slate-200 rounded-xl text-sm leading-relaxed text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all">{{ $berita->isi }}</textarea>
                </div>

            </div>

            {{-- Footer --}}
            <div class="bg-slate-50 px-8 py-5 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.news.index') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50">Batal</a>
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>