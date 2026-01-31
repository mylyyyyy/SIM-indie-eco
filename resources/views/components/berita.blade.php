<x-public-layout>
    {{-- Hero Section Simple --}}
    <div class="bg-blue-600 py-16 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h1 class="text-3xl md:text-4xl font-black text-white mb-2">Berita & Artikel</h1>
            <p class="text-blue-100 font-medium max-w-2xl mx-auto">Ikuti perkembangan terbaru proyek, teknologi konstruksi, dan kegiatan perusahaan kami.</p>
        </div>
    </div>

    {{-- Grid Berita --}}
    <section class="py-16 bg-slate-50">
        <div class="container mx-auto px-6">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($berita as $item)
                    <div class="group bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl hover:border-blue-200 transition-all duration-300 flex flex-col h-full hover:-translate-y-1">
                        
                        {{-- Gambar --}}
                        <div class="relative h-52 overflow-hidden bg-slate-100">
                            @if($item->gambar)
                                <img src="{{ $item->gambar }}" alt="{{ $item->judul }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <i class="fas fa-image text-4xl"></i>
                                </div>
                            @endif
                            {{-- Badge Tanggal --}}
                            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-blue-600 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                                {{ \Carbon\Carbon::parse($item->tanggal_publish)->format('d M Y') }}
                            </div>
                        </div>

                        {{-- Konten --}}
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex items-center gap-2 text-xs text-slate-400 mb-3">
                                <i class="fas fa-pen-nib text-blue-500"></i>
                                <span>{{ $item->penulis ?? 'Admin' }}</span>
                            </div>

                            <h3 class="text-lg font-bold text-slate-800 mb-3 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors">
                                <a href="{{ route('components.berita.detail', $item->id_berita) }}">
                                    {{ $item->judul }}
                                </a>
                            </h3>

                            <p class="text-slate-500 text-sm mb-6 line-clamp-3 leading-relaxed flex-1">
                                {{ Str::limit(strip_tags($item->isi), 120) }}
                            </p>

                            <a href="{{ route('components.berita.detail', $item->id_berita) }}" class="inline-flex items-center text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors mt-auto">
                                Baca Selengkapnya <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-20">
                        <div class="inline-block p-6 bg-slate-100 rounded-full text-slate-400 mb-4">
                            <i class="far fa-newspaper text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-600">Belum ada berita</h3>
                        <p class="text-slate-400 text-sm">Silakan kembali lagi nanti.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-12 flex justify-center">
                {{ $berita->links('pagination::tailwind') }}
            </div>
        </div>
    </section>
</x-public-layout>