<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portofolio - Syafa Group</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/png">

    {{-- Fonts & CSS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #eff6ff; }
        ::-webkit-scrollbar-thumb { background: #1e40af; border-radius: 5px; } 
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="antialiased text-slate-600 bg-slate-50">

    {{-- NAVBAR --}}
    <nav class="fixed w-full z-50 bg-blue-900 shadow-xl border-b border-blue-800 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    {{-- Logo Placeholder --}}
                    <div class="h-10 w-10 bg-white rounded-lg flex items-center justify-center text-blue-900 font-black text-xl shadow-md group-hover:scale-110 transition-transform">S</div>
                    <div class="flex flex-col">
                        <span class="font-black text-xl tracking-tight leading-none text-white">SYAFA <span class="text-blue-300">GROUP</span></span>
                        <span class="text-[10px] font-bold tracking-widest uppercase mt-0.5 text-blue-200/80">Integrated Solution</span>
                    </div>
                </a>
                <div class="flex items-center gap-4">
                    <a href="{{ url('/') }}" class="text-blue-200 hover:text-white font-bold text-sm transition-colors flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- HEADER SECTION --}}
    <section class="pt-32 pb-12 bg-blue-900 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600 rounded-full blur-[150px] opacity-30"></div>
        <div class="container mx-auto px-6 text-center relative z-10">
            <h1 class="text-4xl md:text-5xl font-black text-white mb-4" data-aos="fade-down">Portofolio Proyek</h1>
            <p class="text-blue-200 text-lg max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Rekam jejak dedikasi kami dalam membangun infrastruktur dan hunian berkualitas di berbagai wilayah.
            </p>
        </div>
    </section>

    {{-- PORTFOLIO SECTION (WITH TABS) --}}
    {{-- Inisialisasi Alpine Data: Default aktif tab 'eco' --}}
    <section class="py-20 bg-slate-50 min-h-screen" x-data="{ activeTab: 'eco' }">
        <div class="container mx-auto px-6">
            
            {{-- TAB BUTTONS --}}
            <div class="flex justify-center mb-12" data-aos="fade-up">
                <div class="bg-white p-1.5 rounded-full border border-slate-200 shadow-sm inline-flex">
                    {{-- Tombol ECO --}}
                    <button @click="activeTab = 'eco'" 
                        :class="activeTab === 'eco' ? 'bg-emerald-500 text-white shadow-md' : 'text-slate-500 hover:text-emerald-600'"
                        class="px-8 py-3 rounded-full text-sm font-bold transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-leaf"></i> Syafa Eco
                    </button>

                    {{-- Tombol INDIE --}}
                    <button @click="activeTab = 'indie'" 
                        :class="activeTab === 'indie' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:text-blue-600'"
                        class="px-8 py-3 rounded-full text-sm font-bold transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-city"></i> Syafa Indie
                    </button>
                </div>
            </div>

            {{-- GRID LOOP --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($portfolios as $index => $project)
                    @php
                        // PERBAIKAN: Gunakan kolom 'division' langsung dari database
                        // Pastikan model Portfolio sudah memiliki kolom 'division'
                        $type = $project->division ?? 'indie'; 
                    @endphp

                    {{-- CARD ITEM --}}
                    {{-- x-show: Mengatur tampil/sembunyi berdasarkan tab yang aktif --}}
                    <div x-show="activeTab === '{{ $type }}'"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="group bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 flex flex-col h-full">
                        
                        {{-- IMAGE SECTION --}}
                        <div class="relative h-64 overflow-hidden shrink-0">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-60 z-10"></div>
                            
                            {{-- Badge Kategori --}}
                            <div class="absolute top-4 right-4 z-20">
                                <span class="text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-md uppercase tracking-wider {{ $type == 'eco' ? 'bg-emerald-500' : 'bg-blue-600' }}">
                                    {{ $project->category }}
                                </span>
                            </div>

                            {{-- Gambar --}}
                            @php
                                $imageSrc = filter_var($project->image_path, FILTER_VALIDATE_URL) 
                                            ? $project->image_path 
                                            : ($project->image_path ? $project->image_path : 'https://via.placeholder.com/600x400/f1f5f9/94a3b8?text=No+Image');
                            @endphp

                            <img src="{{ $imageSrc }}" 
                                 alt="{{ $project->title }}" 
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            
                            @if($project->location)
                            <div class="absolute bottom-4 left-4 z-20 flex items-center text-white text-xs font-bold drop-shadow-md">
                                <i class="fa-solid fa-location-dot text-white mr-1.5"></i>
                                {{ $project->location }}
                            </div>
                            @endif
                        </div>

                        {{-- CONTENT SECTION --}}
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="text-lg font-black text-slate-800 mb-3 group-hover:text-blue-600 transition-colors line-clamp-2">
                                {{ $project->title }}
                            </h3>
                            
                            <div class="text-slate-500 text-sm leading-relaxed mb-6 line-clamp-3 flex-grow">
                                {{ $project->description }}
                            </div>

                            {{-- Spesifikasi (Jika Ada) --}}
                            @if(!empty($project->specs) && is_array($project->specs))
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 mb-4">
                                <h4 class="text-[10px] font-bold text-slate-400 uppercase mb-2 flex items-center gap-1">
                                    <i class="fa-solid fa-list-check"></i> Highlight
                                </h4>
                                <div class="grid grid-cols-2 gap-y-2 gap-x-4 text-xs">
                                    @foreach(array_slice($project->specs, 0, 4) as $key => $value)
                                        <div>
                                            <span class="block text-slate-400 font-medium text-[10px]">{{ $key }}</span>
                                            <span class="block text-slate-700 font-bold truncate">{{ $value }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="mt-auto pt-4 border-t border-slate-100 flex justify-between items-center text-xs text-slate-400 font-medium">
                                <span class="flex items-center gap-1"><i class="fas fa-user-tie text-slate-300"></i> {{ $project->client_name ?? 'Klien Privat' }}</span>
                                <span class="flex items-center gap-1"><i class="fas fa-calendar-check text-slate-300"></i> {{ $project->completion_date ? \Carbon\Carbon::parse($project->completion_date)->format('Y') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20">
                        <div class="inline-block p-6 rounded-full bg-slate-100 mb-4 text-slate-400 animate-pulse">
                            <i class="fas fa-folder-open text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-600">Belum Ada Portofolio</h3>
                        <p class="text-slate-400 text-sm">Data proyek akan segera ditambahkan oleh tim kami.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- FOOTER SIMPLE --}}
    <footer class="bg-blue-950 text-white py-8 border-t-4 border-blue-600 text-center relative z-10">
        <p class="text-sm text-blue-200 font-medium">© {{ date('Y') }} Syafa Group. All Rights Reserved.</p>
    </footer>

    {{-- SCRIPTS --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, duration: 800, offset: 50 });
    </script>
</body>
</html>