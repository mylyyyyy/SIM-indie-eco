<x-admin-layout>
    <div class="max-w-4xl mx-auto">
        {{-- Header & Tombol Kembali --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('eco.portfolios.index') }}" class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 rounded-xl text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 transition-all shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-black text-slate-800">Edit Portofolio</h2>
                <p class="text-sm text-slate-500">Perbarui data Kegiatan yang sudah ada.</p>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-emerald-50/50 px-8 py-4 border-b border-emerald-100 flex items-center gap-2">
                <i class="fas fa-edit text-emerald-600"></i>
                <span class="text-sm font-bold text-emerald-800 uppercase tracking-wide">Formulir Edit</span>
            </div>

            <form method="POST" action="{{ route('eco.portfolios.update', $portfolio->id) }}" enctype="multipart/form-data" class="p-8">
                @csrf @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    {{-- Judul --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Judul Kegiatan <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ $portfolio->title }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm font-semibold" required>
                    </div>

                    {{-- Kategori & Tanggal --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jenis Penjualan <span class="text-red-500">*</span></label>
                        <input type="text" name="category" value="{{ $portfolio->category }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal Distribusi <span class="text-red-500">*</span></label>
                        <input type="date" name="completion_date" value="{{ $portfolio->completion_date ? $portfolio->completion_date->format('Y-m-d') : '' }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
                    </div>

                    {{-- Lokasi & Klien --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tujuan Distribusi</label>
                        <input type="text" name="location" value="{{ $portfolio->location }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Pelanggan</label>
                        <input type="text" name="client_name" value="{{ $portfolio->client_name }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    </div>

                    {{-- Deskripsi --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Deskripsi Detail</label>
                        <textarea name="description" rows="5" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm leading-relaxed">{{ $portfolio->description }}</textarea>
                    </div>

                    {{-- Gambar --}}
                    <div class="md:col-span-2 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Gambar Dokumentasi</label>
                        <div class="flex items-start gap-6">
                            @if($portfolio->image_path)
                                <div class="w-32 h-24 rounded-lg overflow-hidden border border-slate-300 shadow-sm shrink-0">
                                    <img src="{{ $portfolio->image_path }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="image" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200 cursor-pointer">
                                <p class="text-[10px] text-slate-400 mt-2">*Upload gambar baru jika ingin mengganti yang lama.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('eco.portfolios.index') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>