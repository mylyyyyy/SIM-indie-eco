<x-public-layout>
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="container mx-auto px-6">
            
            {{-- Breadcrumb --}}
            <div class="mb-8 flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider">
                <a href="{{ url('/') }}" class="hover:text-blue-600">Beranda</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <a href="{{ route('components.berita') }}" class="hover:text-blue-600">Berita</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-slate-600 line-clamp-1">{{ $item->judul }}</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                {{-- KONTEN UTAMA (Kiri) --}}
                <div class="lg:col-span-2">
                    <article class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden p-6 md:p-10">
                        
                        {{-- Meta Header --}}
                        <div class="mb-6 pb-6 border-b border-slate-100">
                            <h1 class="text-2xl md:text-4xl font-black text-slate-800 leading-tight mb-4">{{ $item->judul }}</h1>
                            <div class="flex items-center gap-6 text-sm text-slate-500">
                                <div class="flex items-center gap-2">
                                    <i class="far fa-calendar-alt text-blue-500"></i>
                                    {{ \Carbon\Carbon::parse($item->tanggal_publish)->translatedFormat('d F Y') }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="far fa-user text-blue-500"></i>
                                    {{ $item->penulis ?? 'Tim Redaksi' }}
                                </div>
                            </div>
                        </div>

                        {{-- Gambar Utama --}}
                        @if($item->gambar)
                            <div class="w-full h-[300px] md:h-[400px] rounded-2xl overflow-hidden mb-8 bg-slate-100">
                                <img src="{{ $item->gambar }}" class="w-full h-full object-cover">
                            </div>
                        @endif

                        {{-- Isi Artikel --}}
                        <div class="prose prose-lg prose-slate max-w-none text-slate-600 leading-loose">
                            {{-- Render HTML dari database --}}
                            {!! nl2br(e($item->isi)) !!} 
                        </div>

                        {{-- Footer Artikel --}}
                        <div class="mt-10 pt-8 border-t border-slate-100 flex justify-between items-center">
                            <a href="{{ route('components.berita') }}" class="inline-flex items-center gap-2 font-bold text-slate-500 hover:text-blue-600 transition-colors">
                                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                            </a>
                            <div class="flex gap-2">
                                {{-- Tombol Share Dummy --}}
                                <button class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors"><i class="fab fa-facebook-f"></i></button>
                                <button class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-blue-400 hover:text-white transition-colors"><i class="fab fa-twitter"></i></button>
                                <button class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-green-500 hover:text-white transition-colors"><i class="fab fa-whatsapp"></i></button>
                            </div>
                        </div>
                    </article>
                </div>

                {{-- SIDEBAR (Kanan) --}}
                <div class="lg:col-span-1 space-y-8">
                    
                    {{-- Widget: Berita Terbaru --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 md:p-8">
                        <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                            <span class="w-1 h-6 bg-blue-600 rounded-full"></span> Berita Terbaru
                        </h3>
                        
                        <div class="space-y-6">
                            @forelse($terbaru as $news)
                                <a href="{{ route('components.berita.detail', $news->id_berita) }}" class="flex gap-4 group">
                                    <div class="w-20 h-20 rounded-xl overflow-hidden bg-slate-100 shrink-0">
                                        @if($news->gambar)
                                            <img src="{{ $news->gambar }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fas fa-image"></i></div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-slate-700 text-sm line-clamp-2 mb-1 group-hover:text-blue-600 transition-colors">
                                            {{ $news->judul }}
                                        </h4>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">
                                            {{ \Carbon\Carbon::parse($news->tanggal_publish)->format('d M Y') }}
                                        </span>
                                    </div>
                                </a>
                            @empty
                                <p class="text-sm text-slate-400 italic">Tidak ada berita lain.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Widget: Kontak Cepat --}}
                    <div class="bg-blue-600 rounded-3xl shadow-lg p-8 text-white text-center relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                        <h3 class="text-xl font-black mb-2 relative z-10">Butuh Informasi Proyek?</h3>
                        <p class="text-blue-100 text-sm mb-6 relative z-10">Hubungi kami untuk konsultasi dan detil lengkap.</p>
                        <a href="#" class="inline-block w-full bg-white text-blue-600 font-bold py-3 rounded-xl hover:bg-blue-50 transition-colors relative z-10">
                            <i class="fab fa-whatsapp mr-1"></i> 089515425734
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-public-layout>