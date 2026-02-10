<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Syafa Group - Integrated Business Solution</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/png">

    {{-- 1. FONTS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- 2. CSS LIBRARIES --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- 3. TAILWIND & SCRIPTS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        
        /* Animasi Background Blob */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }

        /* Scrollbar Biru */
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #eff6ff; }
        ::-webkit-scrollbar-thumb { background: #1e40af; border-radius: 5px; } 
        ::-webkit-scrollbar-thumb:hover { background: #1e3a8a; }

        /* Line Clamp untuk Berita */
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</head>

<body class="antialiased text-slate-600 bg-white" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">

    {{-- ======================================================================= --}}
    {{-- NAVBAR (BIRU SOLID & STICKY)                                            --}}
    {{-- ======================================================================= --}}
    <nav class="fixed w-full z-50 transition-all duration-300 bg-blue-900 shadow-xl border-b border-blue-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                {{-- LOGO --}}
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-10 w-auto bg-white p-1 rounded-lg shadow-md transition-transform group-hover:scale-110">
                    <div class="flex flex-col">
                        <span class="font-black text-xl tracking-tight leading-none text-white">
                            SYAFA <span class="text-blue-300">GROUP</span>
                        </span>
                        <span class="text-[10px] font-bold tracking-widest uppercase mt-0.5 text-blue-200/80">
                            Integrated Solution
                        </span>
                    </div>
                </a>

                {{-- DESKTOP MENU --}}
                <div class="hidden md:flex items-center space-x-1 bg-blue-950/40 p-1 rounded-full border border-blue-700/50 backdrop-blur-sm">
                    @foreach([
                        ['url' => '#home', 'label' => 'Beranda'],
                        ['url' => '#perusahaan', 'label' => 'Unit Bisnis'],
                        ['url' => '#tim', 'label' => 'Manajemen'],
                        ['url' => '#berita', 'label' => 'Berita'],
                        ['url' => '#kontak', 'label' => 'Kontak'],
                    ] as $item)
                        <a href="{{ $item['url'] }}" class="px-5 py-2 rounded-full text-sm font-bold text-blue-100 hover:bg-blue-600 hover:text-white transition-all duration-300">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>

                {{-- LOGIN BUTTON --}}
                {{-- RIGHT ACTION BUTTONS --}}
                <div class="hidden md:flex items-center gap-3">
                    {{-- Button Portofolio (Baru) --}}
                    <a href="{{ route('portfolio') }}" class="px-5 py-2.5 rounded-xl border border-blue-300 text-blue-100 font-bold text-sm hover:bg-white hover:text-blue-900 transition-all duration-300 flex items-center gap-2">
                        <i class="fa-solid fa-briefcase"></i> Portofolio
                    </a>

                    {{-- Button Login --}}
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 rounded-xl bg-blue-500 text-white text-sm font-bold shadow-lg shadow-blue-900/50 hover:bg-blue-400 hover:-translate-y-0.5 transition-all flex items-center gap-2 border border-blue-400">
                                <i class="fa-solid fa-gauge"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-xl bg-white text-blue-900 text-sm font-bold shadow-lg hover:bg-blue-50 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                                <i class="fa-solid fa-lock text-blue-600"></i> Login Staff
                            </a>
                        @endauth
                    @endif
                </div>

                {{-- MOBILE MENU BUTTON --}}
                <button class="md:hidden text-white text-2xl focus:outline-none" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>

        {{-- MOBILE MENU --}}
        <div id="mobileMenu" class="hidden md:hidden bg-blue-900 border-t border-blue-800 absolute w-full shadow-xl">
            <div class="p-4 space-y-2">
                <a href="#home" class="block px-4 py-3 text-white font-bold hover:bg-blue-800 rounded-lg">Beranda</a>
                <a href="#perusahaan" class="block px-4 py-3 text-white font-bold hover:bg-blue-800 rounded-lg">Unit Bisnis</a>
                <a href="#tim" class="block px-4 py-3 text-white font-bold hover:bg-blue-800 rounded-lg">Tim Kami</a>
                <a href="#berita" class="block px-4 py-3 text-white font-bold hover:bg-blue-800 rounded-lg">Berita</a>
                <a href="#kontak" class="block px-4 py-3 text-white font-bold hover:bg-blue-800 rounded-lg">Kontak</a>
                <a href="{{ route('login') }}" class="block px-4 py-3 text-blue-900 bg-white font-bold rounded-lg mt-4 text-center">Login Staff</a>
            </div>
        </div>
    </nav>

    {{-- ======================================================================= --}}
    {{-- 1. HERO SECTION (Blue Theme)                                            --}}
    {{-- ======================================================================= --}}
    <section id="home" class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20 bg-blue-50/30">
        {{-- Background Blobs --}}
        <div class="absolute top-0 -left-4 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-[100px] opacity-30 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-96 h-96 bg-cyan-400 rounded-full mix-blend-multiply filter blur-[100px] opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-[100px] opacity-30 animate-blob animation-delay-4000"></div>
        
        <div class="container mx-auto px-6 text-center relative z-10">
            
            <div data-aos="fade-down" data-aos-duration="1000">
                <span class="inline-block py-2 px-5 rounded-full bg-blue-100 text-blue-800 text-xs font-extrabold uppercase tracking-widest mb-6 border border-blue-200 shadow-sm">
                    <i class="fas fa-certificate mr-1.5 text-blue-600"></i> Mitra Terpercaya Sejak 2022
                </span>
            </div>

            <h1 class="text-5xl md:text-7xl font-black text-blue-950 mb-6 leading-tight drop-shadow-sm" data-aos="fade-up" data-aos-duration="1000">
                Membangun Masa Depan <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Bisnis yang Solid</span>
            </h1>

            <p class="text-lg md:text-xl text-slate-600 mb-10 max-w-3xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                Syafa Group menghadirkan solusi terintegrasi di bidang <b class="text-blue-700">Properti, Konstruksi,</b> dan <b class="text-blue-700">Perdagangan</b> untuk mendukung pertumbuhan ekonomi Indonesia.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4" data-aos="fade-up" data-aos-delay="400">
                <button onclick="showSwingAlert()" class="group px-8 py-4 bg-blue-800 text-white rounded-full font-bold shadow-xl shadow-blue-800/30 hover:bg-blue-700 transition-all duration-300 flex items-center justify-center gap-2 hover:-translate-y-1">
                    <span>Pelajari Lebih Lanjut</span>
                    <i class="fa-solid fa-arrow-down group-hover:animate-bounce"></i>
                </button>
                <a href="#kontak" class="px-8 py-4 bg-white text-blue-900 border-2 border-blue-100 rounded-full font-bold shadow-md hover:border-blue-500 hover:text-blue-700 transition-all duration-300 hover:-translate-y-1">
                    Hubungi Kami
                </a>
            </div>

            {{-- Stats Bar --}}
            <div class="mt-20 bg-gradient-to-r from-blue-900 to-blue-800 rounded-3xl p-10 shadow-2xl text-white max-w-5xl mx-auto transform hover:scale-[1.01] transition-transform duration-500 relative overflow-hidden" data-aos="zoom-in-up" data-aos-delay="600">
                <div class="absolute top-0 right-0 p-4 opacity-10"><i class="fas fa-chart-line text-9xl"></i></div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-blue-700/50 relative z-10">
                    <div><h3 class="text-4xl font-black mb-1">3+</h3><p class="text-xs font-bold text-blue-200 uppercase tracking-wider">Anak Perusahaan</p></div>
                    <div><h3 class="text-4xl font-black mb-1">100%</h3><p class="text-xs font-bold text-blue-200 uppercase tracking-wider">Legalitas Resmi</p></div>
                    <div><h3 class="text-4xl font-black mb-1">Jatim</h3><p class="text-xs font-bold text-blue-200 uppercase tracking-wider">Kantor Pusat</p></div>
                    <div><h3 class="text-4xl font-black mb-1">24/7</h3><p class="text-xs font-bold text-blue-200 uppercase tracking-wider">Dukungan</p></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================================================================= --}}
    {{-- 2. ANAK PERUSAHAAN (Cards with Blue Accents & Full Data)                --}}
    {{-- ======================================================================= --}}
    <section id="perusahaan" class="py-24 bg-white relative">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-blue-50/50 -skew-x-12 -z-10"></div>
        <div class="absolute top-1/4 left-0 w-64 h-64 bg-cyan-50 rounded-full blur-3xl -z-10"></div>
        
        <div class="container mx-auto px-6">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-blue-600 font-bold text-sm tracking-widest uppercase bg-blue-50 px-3 py-1 rounded-full">Holding Company</span>
                <h2 class="text-3xl md:text-4xl font-black text-blue-950 mt-3 mb-4">Unit Bisnis <span class="text-blue-600">Strategis</span></h2>
                <div class="h-1.5 w-24 bg-gradient-to-r from-blue-600 to-cyan-400 mx-auto rounded-full"></div>
                <p class="text-slate-500 mt-4 max-w-2xl mx-auto">Kami mengelola portofolio bisnis yang beragam dengan standar kualitas tertinggi dan legalitas terjamin.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- A. PT. INDIE SYAFA TRANSFORMA --}}
                <div class="group bg-white rounded-[2.5rem] border border-blue-100 shadow-xl shadow-blue-100/50 hover:shadow-2xl hover:border-blue-300 transition-all duration-500 hover:-translate-y-2 relative overflow-hidden flex flex-col h-full" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-gradient-to-br from-blue-600 to-blue-800 p-6 relative overflow-hidden">
                        <div class="absolute right-0 top-0 opacity-10 transform translate-x-4 -translate-y-4"><i class="fa-solid fa-city text-8xl text-white"></i></div>
                        <div class="relative z-10">
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white text-2xl mb-4 border border-white/20"><i class="fa-solid fa-house-chimney"></i></div>
                            <h3 class="text-xl font-black text-white leading-tight">PT. INDIE Syafa Transforma</h3>
                            <p class="text-blue-200 text-xs font-bold uppercase mt-1 tracking-wider">Property Developer</p>
                        </div>
                    </div>
                    <div class="p-8 flex-grow flex flex-col">
                        <div class="mb-6 flex-grow">
                            <p class="text-slate-600 text-sm leading-relaxed mb-4 text-justify">
                                Berdiri pertengahan 2022 atas saran <strong>Bapak Dr. Wahju Prijo Djatmiko</strong>. Didukung pengalaman owner di bidang properti sejak 2012.
                            </p>
                            <ul class="text-sm space-y-3 text-slate-600">
                                <li class="flex items-start gap-3"><i class="fa-solid fa-map-location-dot text-blue-500 mt-1"></i><span><strong>Kantor:</strong> Gedung PRAXIS, Jl. Sono Kembang No. 4-6, Surabaya.</span></li>
                                <li class="flex items-start gap-3"><i class="fa-solid fa-building-circle-check text-blue-500 mt-1"></i><span><strong>Proyek:</strong> Jawa Timur, Jawa Tengah, Jawa Barat.</span></li>
                            </ul>
                        </div>
                        <div class="bg-blue-50/50 rounded-xl p-5 border border-blue-100">
                            <h4 class="text-xs font-black text-blue-800 uppercase mb-3 border-b border-blue-200 pb-2">Susunan Direksi</h4>
                            <ul class="space-y-2 text-xs">
                                <li class="flex justify-between"><span class="text-slate-500">Direktur Utama</span> <span class="font-bold text-blue-900">Sa’diah, S.Pd.I, M.Pd.I</span></li>
                                <li class="flex justify-between"><span class="text-slate-500">Direktur</span> <span class="font-bold text-blue-900">Puspi, SE</span></li>
                                <li class="flex justify-between"><span class="text-slate-500">Komisaris</span> <span class="font-bold text-blue-900">A. P. Mustiko, SH</span></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- B. PT. ECO SYAFA HARVEST --}}
                <div class="group bg-white rounded-[2.5rem] border border-cyan-100 shadow-xl shadow-cyan-100/50 hover:shadow-2xl hover:border-cyan-300 transition-all duration-500 hover:-translate-y-2 relative overflow-hidden flex flex-col h-full" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-gradient-to-br from-cyan-600 to-blue-700 p-6 relative overflow-hidden">
                        <div class="absolute right-0 top-0 opacity-10 transform translate-x-4 -translate-y-4"><i class="fa-solid fa-boxes-stacked text-8xl text-white"></i></div>
                        <div class="relative z-10">
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white text-2xl mb-4 border border-white/20"><i class="fa-solid fa-wheat-awn"></i></div>
                            <h3 class="text-xl font-black text-white leading-tight">PT. Eco Syafa Harvest</h3>
                            <p class="text-cyan-100 text-xs font-bold uppercase mt-1 tracking-wider">Industry & Trade</p>
                        </div>
                    </div>
                    <div class="p-8 flex-grow flex flex-col">
                        <div class="mb-6 flex-grow">
                            <p class="text-slate-600 text-sm leading-relaxed mb-4 text-justify">
                                Didirikan pertengahan 2023. Fokus utama pada <strong>pendistribusian beras</strong>, serta izin usaha air kemasan, tekstil, dan pakaian.
                            </p>
                            <ul class="text-sm space-y-3 text-slate-600">
                                <li class="flex items-start gap-3"><i class="fa-solid fa-map-location-dot text-cyan-600 mt-1"></i><span><strong>Kantor:</strong> Gedung PRAXIS, Surabaya.</span></li>
                                <li class="flex items-start gap-3"><i class="fa-solid fa-network-wired text-cyan-600 mt-1"></i><span><strong>Cabang:</strong> Kab. Malang, Probolinggo, Lumajang, Jember.</span></li>
                            </ul>
                        </div>
                        <div class="bg-cyan-50/50 rounded-xl p-5 border border-cyan-100">
                            <h4 class="text-xs font-black text-cyan-800 uppercase mb-3 border-b border-cyan-200 pb-2">Susunan Direksi</h4>
                            <ul class="space-y-2 text-xs">
                                <li class="flex justify-between"><span class="text-slate-500">Direktur Utama</span> <span class="font-bold text-blue-900">Sa’diah, S.Pd.I, M.Pd.I</span></li>
                                <li class="flex justify-between"><span class="text-slate-500">Direktur</span> <span class="font-bold text-blue-900">Puspi, SE</span></li>
                                <li class="flex justify-between"><span class="text-slate-500">Komisaris</span> <span class="font-bold text-blue-900">D Oki</span></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- C. PT. ATHENA SYAFA PANCA --}}
                <div class="group bg-white rounded-[2.5rem] border border-indigo-100 shadow-xl shadow-indigo-100/50 hover:shadow-2xl hover:border-indigo-300 transition-all duration-500 hover:-translate-y-2 relative overflow-hidden flex flex-col h-full" data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-gradient-to-br from-indigo-600 to-blue-800 p-6 relative overflow-hidden">
                        <div class="absolute right-0 top-0 opacity-10 transform translate-x-4 -translate-y-4"><i class="fa-solid fa-trowel-bricks text-8xl text-white"></i></div>
                        <div class="relative z-10">
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white text-2xl mb-4 border border-white/20"><i class="fa-solid fa-helmet-safety"></i></div>
                            <h3 class="text-xl font-black text-white leading-tight">PT. Athena Syafa Panca</h3>
                            <p class="text-indigo-200 text-xs font-bold uppercase mt-1 tracking-wider">Construction & Mining</p>
                        </div>
                    </div>
                    <div class="p-8 flex-grow flex flex-col">
                        <div class="mb-6 flex-grow">
                            <p class="text-slate-600 text-sm leading-relaxed mb-4 text-justify">
                                Perusahaan ketiga (Est. 2025). Fokus di bidang <strong>konstruksi</strong>, pertambangan, dan perdagangan material.
                            </p>
                            <ul class="text-sm space-y-3 text-slate-600">
                                <li class="flex items-start gap-3"><i class="fa-solid fa-map-location-dot text-indigo-500 mt-1"></i><span><strong>Kantor:</strong> Nganjuk, Jawa Timur.</span></li>
                                <li class="flex items-start gap-3"><i class="fa-solid fa-briefcase text-indigo-500 mt-1"></i><span><strong>Legalitas:</strong> Izin Pertambangan & Konstruksi.</span></li>
                            </ul>
                        </div>
                        <div class="bg-indigo-50/50 rounded-xl p-5 border border-indigo-100">
                            <h4 class="text-xs font-black text-indigo-800 uppercase mb-3 border-b border-indigo-200 pb-2">Susunan Direksi</h4>
                            <ul class="space-y-2 text-xs">
                                <li class="flex justify-between"><span class="text-slate-500">Direktur Utama</span> <span class="font-bold text-blue-900">Eliza Putri S, SH</span></li>
                                <li class="flex justify-between"><span class="text-slate-500">Direktur</span> <span class="font-bold text-blue-900">Linda Adelia, SE</span></li>
                                <li class="flex justify-between"><span class="text-slate-500">Komisaris</span> <span class="font-bold text-blue-900">Puguh P.W., Amd</span></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ======================================================================= --}}
    {{-- 3. VISI & MISI (Blue Modern)                                            --}}
    {{-- ======================================================================= --}}
    <section class="py-24 bg-blue-50/30 relative overflow-hidden">
        <div class="absolute -left-20 top-20 w-96 h-96 bg-blue-200/40 rounded-full blur-3xl -z-10"></div>
        <div class="container mx-auto px-6">
            <div class="bg-white rounded-[3rem] p-8 md:p-16 shadow-2xl border border-blue-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-100/50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
                
                <div class="flex flex-col md:flex-row gap-12 items-center">
                    <div class="w-full md:w-1/2" data-aos="fade-right">
                        <div class="inline-block bg-blue-100 text-blue-700 px-4 py-1 rounded-full text-xs font-bold uppercase mb-4 tracking-wide">Core Values</div>
                        <h2 class="text-3xl md:text-4xl font-black text-blue-950 mb-6">Komitmen Pertumbuhan Berkelanjutan</h2>
                        <p class="text-lg text-slate-600 mb-6 leading-relaxed italic">
                            "Menjadi pilihan nyata bagi masyarakat sekitar dan relasi yang berujung kepada pertumbuhan ekonomi Tanah Air."
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600"><i class="fas fa-check"></i></div>
                                <span class="font-medium text-slate-700">Inovasi tiada henti</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600"><i class="fas fa-check"></i></div>
                                <span class="font-medium text-slate-700">Integritas dalam bisnis</span>
                            </li>
                        </ul>
                    </div>
                    <div class="w-full md:w-1/2 grid gap-6" data-aos="fade-left">
                        <div class="bg-blue-50 p-8 rounded-3xl border-l-8 border-blue-600 hover:shadow-lg transition-all">
                            <h4 class="font-bold text-2xl text-blue-900 mb-3"><i class="fas fa-eye text-blue-500 mr-2"></i> Visi</h4>
                            <p class="text-slate-600">Mengembangkan group bisnis dengan semangat inovasi, menciptakan lapangan kerja, serta meningkatkan kesejahteraan masyarakat melalui pertumbuhan ekonomi.</p>
                        </div>
                        <div class="bg-cyan-50 p-8 rounded-3xl border-l-8 border-cyan-500 hover:shadow-lg transition-all">
                            <h4 class="font-bold text-2xl text-cyan-900 mb-3"><i class="fas fa-bullseye text-cyan-500 mr-2"></i> Misi</h4>
                            <ul class="space-y-2 text-slate-600 text-sm">
                                <li>• Mitra bisnis yang menguntungkan semua pihak.</li>
                                <li>• Pilihan utama konsumen melalui pelayanan prima.</li>
                                <li>• Lingkungan kerja yang mensejahterakan karyawan.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

   {{-- ======================================================================= --}}
    {{-- 4. OUR TEAM (Dynamic from Database)                                     --}}
    {{-- ======================================================================= --}}
    <section id="tim" class="py-24 bg-white relative">
        <div class="container mx-auto px-6 relative z-10">
            
            {{-- Header Section --}}
            <div class="text-center mb-16" data-aos="fade-down">
                <span class="text-blue-600 font-bold text-sm tracking-widest uppercase bg-blue-50 px-3 py-1 rounded-full">Struktur Organisasi</span>
                <h2 class="text-3xl md:text-4xl font-black text-blue-950 mt-3 mb-4">Tim Manajemen Profesional</h2>
                <p class="mt-2 text-sm text-slate-500 max-w-2xl mx-auto">
                    Diluar orang-orang terkait, informasi kontak atau alamat email yang tercantum
                    <span class="font-medium">bukan merupakan milik pribadi masing-masing individu</span>.
                </p><br>
                <div class="h-1.5 w-24 bg-gradient-to-r from-blue-700 to-blue-400 mx-auto rounded-full"></div>
            </div>

            {{-- Grid Team --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                
                {{-- MULAI LOOPING DATABASE --}}
                @foreach($teams as $team)
                    <div class="group bg-white rounded-[2rem] p-4 shadow-lg border border-slate-100 hover:shadow-blue-200/50 hover:border-blue-300 transition-all duration-500 hover:-translate-y-2" 
                         data-aos="zoom-in" 
                         data-aos-delay="{{ ($loop->index + 1) * 100 }}">
                        
                        {{-- FOTO --}}
                        <div class="relative w-full aspect-[4/5] rounded-3xl overflow-hidden bg-slate-100 mb-6 border border-slate-200">
                            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>
                            
                            {{-- Cek apakah ada foto di database (Base64) --}}
                            @if($team->photo)
                                <img src="{{ $team->photo }}" 
                                     alt="{{ $team->name }}" 
                                     class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-110">
                            @else
                                {{-- Fallback jika tidak ada foto (Pakai Inisial Nama) --}}
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($team->name) }}&background=bfdbfe&color=1e3a8a&size=500" 
                                     alt="{{ $team->name }}" 
                                     class="w-full h-full object-cover object-top">
                            @endif

                            <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-3 translate-y-10 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 z-20">
                                <span class="text-white font-bold text-sm tracking-widest bg-blue-600/80 px-3 py-1 rounded-full backdrop-blur-sm">SYAFA GROUP</span>
                            </div>
                        </div>

                        {{-- INFO DETAIL --}}
                        <div class="text-center px-2 pb-4">
                            <h4 class="text-xl font-bold text-slate-800 mb-1 group-hover:text-blue-600 transition-colors">
                                {{ $team->name }}
                            </h4>

                            {{-- Highlight Warna Biru untuk urutan pertama (Biasanya CEO) --}}
                            <p class="text-xs font-bold uppercase tracking-wider {{ $loop->first ? 'text-blue-600' : 'text-slate-400' }}">
                                {{ $team->role }}
                            </p>

                            <div class="mt-4 space-y-2 text-sm text-slate-600">
                                
                                {{-- Tampilkan WA hanya jika ada --}}
                                @if($team->phone)
                                <div class="flex items-center justify-center gap-2">
                                    <i class="fa-brands fa-whatsapp text-emerald-500"></i>
                                    {{-- Format nomor WA (hapus 0 depan, tambah 62) --}}
                                    <a href="https://wa.me/62{{ ltrim($team->phone, '0') }}" target="_blank" class="hover:text-emerald-600 transition">
                                        {{ $team->phone }}
                                    </a>
                                </div>
                                @endif

                                {{-- Tampilkan Email hanya jika ada --}}
                                @if($team->email)
                                <div class="flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-envelope text-blue-500"></i>
                                    <a href="mailto:{{ $team->email }}" class="hover:text-blue-600 transition">
                                        {{ $team->email }}
                                    </a>
                                </div>
                                @endif

                                {{-- Tampilkan Alamat hanya jika ada --}}
                                @if($team->address)
                                <div class="flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-location-dot text-red-500"></i>
                                    <span class="text-center">{{ $team->address }}</span>
                                </div>
                                @endif

                            </div>

                            {{-- Garis Bawah Dekoratif --}}
                            @if($loop->first)
                                <div class="w-full h-1 bg-blue-600 mt-4 rounded-full shadow-sm"></div>
                            @else
                                <div class="w-8 h-1 bg-slate-200 mx-auto mt-4 rounded-full group-hover:bg-blue-400 group-hover:w-16 transition-all duration-300"></div>
                            @endif
                        </div>
                    </div>
                @endforeach
                {{-- SELESAI LOOPING --}}

            </div>
        </div>
    </section>

   {{-- ======================================================================= --}}
    {{-- 5. NEW: BERITA & ARTIKEL (Dynamic from DB)                              --}}
    {{-- ======================================================================= --}}
    <section id="berita" class="py-24 bg-slate-50 relative">
        <div class="absolute inset-0 bg-slate-100/50 -skew-y-3 origin-top-left -z-10 h-full w-full"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12" data-aos="fade-right">
                <div class="mb-4 md:mb-0">
                    <span class="text-blue-600 font-bold text-sm tracking-widest uppercase bg-blue-50 px-3 py-1 rounded-full">Informasi Terkini</span>
                    <h2 class="text-3xl md:text-4xl font-black text-blue-950 mt-3">Berita & Artikel</h2>
                </div>
                {{-- PERBAIKAN 1: Button Lihat Semua --}}
                <a href="{{ route('components.berita') }}" class="text-blue-600 font-bold hover:text-blue-800 transition-colors flex items-center gap-2">
                    Lihat Semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($berita as $item)
                    <div class="group bg-white rounded-3xl overflow-hidden shadow-lg border border-slate-100 hover:shadow-2xl hover:border-blue-200 transition-all duration-500 hover:-translate-y-2" data-aos="fade-up">
                        <div class="relative h-56 overflow-hidden">
                            {{-- Handle gambar (Base64 atau URL) --}}
                            @if($item->gambar)
                                <img src="{{ $item->gambar }}" alt="{{ $item->judul }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full bg-blue-100 flex items-center justify-center text-blue-300">
                                    <i class="fas fa-image text-5xl"></i>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                                {{ \Carbon\Carbon::parse($item->tanggal_publish)->format('d M Y') }}
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 text-xs text-slate-400 mb-3">
                                <i class="fas fa-user-circle"></i>
                                <span>{{ $item->penulis }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                <a href="{{ route('components.berita.detail', $item->id_berita) }}">
                                    {{ $item->judul }}
                                </a>
                            </h3>
                            <p class="text-slate-500 text-sm mb-6 line-clamp-3 leading-relaxed">
                                {{ Str::limit(strip_tags($item->isi), 100) }}
                            </p>
                            
                            {{-- PERBAIKAN 2: Button Baca Selengkapnya --}}
                            <a href="{{ route('components.berita.detail', $item->id_berita) }}" class="inline-flex items-center text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">
                                Baca Selengkapnya <i class="fas fa-long-arrow-alt-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <div class="bg-blue-50 rounded-3xl p-8 inline-block">
                            <i class="fas fa-newspaper text-4xl text-blue-300 mb-4"></i>
                            <p class="text-slate-500 font-medium">Belum ada berita yang dipublish.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    {{-- ======================================================================= --}}
    {{-- 6. FOOTER (Dark Blue & Fixed Map)                                       --}}
    {{-- ======================================================================= --}}
    <footer id="kontak" class="bg-blue-950 text-white pt-20 pb-10 relative overflow-hidden border-t-8 border-blue-700">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-700 rounded-full blur-[150px] opacity-30"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-800 rounded-full blur-[150px] opacity-30"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:gap-20 gap-10 mb-16">
                
                <div data-aos="fade-right">
                    <div class="flex items-center gap-3 mb-6">
                        <img src="{{ asset('img/logo.png') }}" class="h-14 w-auto bg-white p-2 rounded-xl shadow-lg" alt="Logo">
                        <div>
                            <span class="block text-2xl font-black tracking-tight">SYAFA GROUP</span>
                            <span class="text-blue-300 text-xs font-bold tracking-widest uppercase">Integrated Solution</span>
                        </div>
                    </div>
                    <p class="text-blue-200/80 leading-relaxed mb-8 border-l-4 border-blue-600 pl-4">
                        Kami siap menjadi mitra strategis Anda untuk mencapai pertumbuhan bisnis yang berkelanjutan. Hubungi kami untuk konsultasi lebih lanjut.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 bg-blue-900/50 p-4 rounded-xl border border-blue-800 hover:border-blue-600 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-location-dot"></i></div>
                            <div><h5 class="font-bold text-white">Kantor Pusat</h5><p class="text-blue-200 text-sm">Gedung PRAXIS, Jl. Sono Kembang No. 4-6, Surabaya</p></div>
                        </div>
                       <div class="flex items-center gap-4 bg-blue-900/50 p-4 rounded-xl border border-blue-800 hover:border-blue-600 transition-colors">
    <div class="w-10 h-10 rounded-lg bg-emerald-500 flex items-center justify-center shrink-0">
        <i class="fa-solid fa-headset"></i>
    </div>
    <div>
        <h5 class="font-bold text-white">Hubungi Kami</h5>
        <p class="text-blue-200 text-sm">Email: indiesyafa@gmail.com</p>
        <p class="text-blue-200 text-sm">Kontak: 0895-1542-5734</p>
    </div>
</div>

                    </div>
                </div>

                {{-- PETA GEDUNG PRAXIS SURABAYA --}}
                <div class="h-80 rounded-2xl overflow-hidden shadow-2xl border-4 border-blue-800 relative group" data-aos="fade-left">
                    <div class="absolute inset-0 bg-blue-900/20 group-hover:bg-transparent transition-all z-10 pointer-events-none"></div>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.735756360877!2d112.74233737575027!3d-7.270908671440939!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7f9627793bdfb%3A0x536b7b2527233732!2sGedung%20Praxis!5e0!3m2!1sen!2sid!4v1705000000000!5m2!1sen!2sid" 
                            class="w-full h-full border-0 filter grayscale hover:grayscale-0 transition-all duration-700" 
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <div class="border-t border-blue-900 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-blue-400">
                <p>© {{ date('Y') }} PT. Syafa Group. All Rights Reserved.</p>
                <div class="flex gap-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white transition-colors font-semibold">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors font-semibold">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- SCRIPTS --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, offset: 50, duration: 800 });

        // Fungsi Swing Alert
        function showSwingAlert() {
            Swal.fire({
                title: 'Halo!',
                text: 'Selamat datang di Syafa Group. Mari berkolaborasi!',
                icon: 'info',
                confirmButtonText: 'Siap!',
                confirmButtonColor: '#1e40af', // Blue-800
                showClass: { popup: 'animate__animated animate__swing' },
                hideClass: { popup: 'animate__animated animate__fadeOutUp' },
                customClass: { popup: 'rounded-3xl border-4 border-blue-100' }
            });
        }
    </script>
</body>
</html>