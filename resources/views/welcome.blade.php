@extends('layouts.main')

@section('content')

{{-- ======================================================================= --}}
{{-- 1. HERO SECTION (RE-DESIGNED)                                           --}}
{{-- ======================================================================= --}}
<section class="relative min-h-screen flex items-center justify-center overflow-hidden pt-24 pb-12">
    
    {{-- A. BACKGROUND DINAMIS (PENGGANTI PUTIH POLOS) --}}
    <div class="absolute inset-0 z-0 bg-slate-50">
        {{-- 1. Pola Grid (Memberi tekstur teknis) --}}
        <div class="absolute inset-0" style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 40px 40px; opacity: 0.4;"></div>
        
        {{-- 2. Mesh Gradient (Warna-warni blur yang bergerak) --}}
        {{-- Biru Kiri Atas --}}
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-blue-400/30 rounded-full blur-[100px] animate-blob mix-blend-multiply filter"></div>
        {{-- Cyan Kanan Atas --}}
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-cyan-300/30 rounded-full blur-[100px] animate-blob animation-delay-2000 mix-blend-multiply filter"></div>
        {{-- Ungu Bawah Kiri --}}
        <div class="absolute bottom-[-20%] left-[20%] w-[500px] h-[500px] bg-indigo-300/30 rounded-full blur-[100px] animate-blob animation-delay-4000 mix-blend-multiply filter"></div>
    </div>

    {{-- B. KONTEN HERO --}}
    <div class="container mx-auto px-6 text-center relative z-10">
        
        {{-- Badge Kecil di Atas --}}
        <div class="inline-flex items-center px-3 py-1 rounded-full border border-blue-200 bg-white/60 backdrop-blur-sm text-blue-600 text-xs font-bold uppercase tracking-wider mb-8 shadow-sm animate-fade-in-up">
            <span class="w-2 h-2 rounded-full bg-blue-500 mr-2 animate-pulse"></span>
            Integrated Business Solution
        </div>

        {{-- Logo (Tanpa Kotak Putih, Langsung Float) --}}
        <div class="mb-6 flex justify-center animate-fade-in-up">
            {{-- Efek Glow di belakang logo --}}
            <div class="relative">
                <div class="absolute inset-0 bg-white/50 blur-xl rounded-full scale-150"></div>
                <img src="{{ asset('img/logo.png') }}" alt="Logo Syafa Group" 
                     class="relative w-40 h-auto md:w-48 drop-shadow-2xl transform hover:scale-105 transition-transform duration-500">
            </div>
        </div>

        {{-- Main Heading --}}
        <h1 class="text-5xl sm:text-6xl md:text-7xl font-black mb-6 tracking-tight leading-tight text-slate-800 animate-fade-in-up" style="animation-delay: 0.1s;">
            SYAFA <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">GROUP</span>
        </h1>

        {{-- Subtitle --}}
        <p class="text-lg sm:text-xl md:text-2xl text-slate-600 mb-10 max-w-3xl mx-auto font-medium leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s;">
            Mitra strategis terpercaya. Melayani <span class="text-blue-600 font-bold bg-blue-50 px-1 rounded">Properti</span>, 
            <span class="text-blue-600 font-bold bg-blue-50 px-1 rounded">Konstruksi</span>, & 
            <span class="text-blue-600 font-bold bg-blue-50 px-1 rounded">Perdagangan</span> 
            dengan standar mutu terbaik.
        </p>

        {{-- CTA Buttons --}}
        <div class="flex flex-col sm:flex-row justify-center gap-4 animate-fade-in-up" style="animation-delay: 0.3s;">
            <a href="#perusahaan" class="group relative px-8 py-4 bg-slate-900 rounded-full text-white text-lg font-bold shadow-xl overflow-hidden transition-all hover:shadow-2xl hover:bg-slate-800 hover:-translate-y-1">
                <span class="relative z-10 flex items-center gap-2">
                    Anak Perusahaan <i class="fa-solid fa-arrow-down group-hover:translate-y-1 transition-transform"></i>
                </span>
            </a>
            
            <a href="#kontak" class="group relative px-8 py-4 bg-white rounded-full text-slate-700 border border-slate-200 text-lg font-bold shadow-lg overflow-hidden transition-all hover:shadow-xl hover:border-blue-300 hover:text-blue-600 hover:-translate-y-1">
                <span class="relative z-10 flex items-center gap-2">
                    Hubungi Kami <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </span>
            </a>
        </div>

        {{-- Stats / Trust Indicators --}}
        <div class="mt-16 pt-8 border-t border-slate-200/60 grid grid-cols-2 md:grid-cols-4 gap-8 max-w-4xl mx-auto animate-fade-in-up" style="animation-delay: 0.5s;">
            <div>
                <div class="text-3xl font-black text-slate-800">2022</div>
                <div class="text-sm text-slate-500 font-semibold uppercase tracking-wide">Didirikan</div>
            </div>
            <div>
                <div class="text-3xl font-black text-slate-800">3+</div>
                <div class="text-sm text-slate-500 font-semibold uppercase tracking-wide">Anak Perusahaan</div>
            </div>
            <div>
                <div class="text-3xl font-black text-slate-800">100%</div>
                <div class="text-sm text-slate-500 font-semibold uppercase tracking-wide">Legalitas</div>
            </div>
            <div>
                <div class="text-3xl font-black text-slate-800">Jatim-Jateng</div>
                <div class="text-sm text-slate-500 font-semibold uppercase tracking-wide">Cakupan Area</div>
            </div>
        </div>
    </div>
</section>

{{-- ======================================================================= --}}
{{-- 2. TENTANG SYAFA GROUP (Glass Card)                                     --}}
{{-- ======================================================================= --}}
<section class="py-20 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="bg-white/70 backdrop-blur-xl border border-white/50 rounded-[2.5rem] p-8 md:p-16 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] text-center max-w-5xl mx-auto">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-slate-100 text-slate-600 text-sm font-bold mb-6">
                <i class="fas fa-building mr-2"></i> Tentang Kami
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-800 mb-6 leading-tight">
                Membangun Ekosistem Bisnis <br>yang <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Berkelanjutan</span>
            </h2>
            <p class="text-lg text-slate-600 leading-relaxed max-w-3xl mx-auto">
                Didirikan pada tahun 2022, <strong>SYAFA Group</strong> adalah holding company yang menaungi unit bisnis strategis yang terintegrasi. Kami berkomitmen untuk memberikan kontribusi nyata dalam pembangunan ekonomi melalui sektor properti, perdagangan, dan konstruksi.
            </p>
        </div>
    </div>
</section>

{{-- ======================================================================= --}}
{{-- 3. ANAK PERUSAHAAN (Subsidiaries)                                       --}}
{{-- ======================================================================= --}}
<section id="perusahaan" class="py-24 bg-slate-50 relative overflow-hidden">
    {{-- Background Pattern Halus --}}
    <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    {{-- Aksen Blur di Background --}}
    <div class="absolute top-1/2 left-0 w-64 h-64 bg-blue-200/20 rounded-full blur-3xl -translate-x-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-sky-200/20 rounded-full blur-3xl translate-x-1/3 translate-y-1/3"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-16">
            <div class="inline-block bg-sky-100 text-sky-600 font-bold px-4 py-1 rounded-full text-xs uppercase tracking-widest mb-4">Holding Company</div>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-800 mb-4">
                Anak <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-blue-700">Perusahaan</span>
            </h2>
            <div class="h-1.5 w-24 bg-gradient-to-r from-sky-400 to-blue-600 mx-auto rounded-full mb-6"></div>
            <p class="text-slate-500 text-lg max-w-2xl mx-auto">
                Sinergi unit bisnis strategis yang bergerak di bidang Properti, Perdagangan, dan Konstruksi untuk mendukung pertumbuhan ekonomi.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            {{-- A. PT. INDIE SYAFA TRANSFORMA --}}
            <div class="group bg-white rounded-[2rem] shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-slate-100 flex flex-col h-full overflow-hidden relative">
                {{-- Decorative Header --}}
                <div class="h-24 bg-gradient-to-br from-blue-500 to-blue-700 relative">
                    <div class="absolute -bottom-10 left-8 p-4 bg-white rounded-2xl shadow-lg">
                        <i class="fa-solid fa-house-chimney text-3xl text-blue-600"></i>
                    </div>
                    <div class="absolute right-0 top-0 p-4 opacity-10">
                        <i class="fa-solid fa-building text-6xl text-white"></i>
                    </div>
                </div>

                <div class="px-8 pt-14 pb-8 flex-grow">
                    <h3 class="text-xl font-extrabold text-slate-800 mb-1">PT. Indie Syafa Transforma</h3>
                    <p class="text-xs font-bold text-blue-500 uppercase tracking-widest mb-4 border-b border-blue-100 pb-4">Property Developer</p>
                    
                    <div class="prose prose-sm text-slate-600 text-justify mb-6">
                        <p class="mb-2">
                            Berdiri pertengahan 2022 atas saran <strong>Bapak Dr. Wahju Prijo Djatmiko, S.H., M.Hum., M.Sc., G.Dipl.IfSc., S.S. </strong>. Perusahaan berbasis properti ini didukung pengalaman owner yang telah berkecimpung sejak tahun 2012.
                        </p>
                        <p class="text-xs text-slate-500 bg-slate-50 p-3 rounded-lg border border-slate-100">
                            <strong>Fokus Proyek:</strong><br>
                            Jawa Timur, Jawa Tengah, dan Jawa Barat (Sedang Dikembangkan).
                        </p>
                    </div>

                    <div class="text-xs text-slate-500 flex items-start gap-2 mb-2">
                        <i class="fa-solid fa-location-dot text-blue-500 mt-0.5"></i> 
                        <span>Gedung PRAXIS, Jl. Sono Kembang No. 4-6, Surabaya (Pusat)</span>
                    </div>
                </div>
                
                {{-- Direksi Section --}}
                <div class="bg-slate-50/80 px-8 py-5 border-t border-slate-100 backdrop-blur-sm">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase mb-3 tracking-wider">Susunan Direksi</h4>
                    <ul class="space-y-2 text-xs">
                        <li class="flex justify-between items-center">
                            <span class="text-slate-500 font-medium">Direktur Utama</span>
                            <span class="font-bold text-slate-800 bg-white px-2 py-1 rounded shadow-sm border border-slate-100">Sa’diah, S.Pd.I, M.Pd.I</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="text-slate-500 font-medium">Direktur</span>
                            <span class="font-bold text-slate-800">Puspi, SE</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="text-slate-500 font-medium">Komisaris</span>
                            <span class="font-bold text-slate-800">A. P. Mustiko, SH</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- B. PT. ECO SYAFA HARVEST --}}
            <div class="group bg-white rounded-[2rem] shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-slate-100 flex flex-col h-full overflow-hidden relative">
                {{-- Badge EST --}}
                <div class="absolute top-4 right-4 z-20 bg-white/20 backdrop-blur text-white text-[10px] font-bold px-3 py-1 rounded-full border border-white/30 shadow-sm">EST 2023</div>
                
                {{-- Decorative Header --}}
                <div class="h-24 bg-gradient-to-br from-green-500 to-emerald-600 relative">
                    <div class="absolute -bottom-10 left-8 p-4 bg-white rounded-2xl shadow-lg">
                        <i class="fa-solid fa-wheat-awn text-3xl text-green-600"></i>
                    </div>
                    <div class="absolute right-0 top-0 p-4 opacity-10">
                        <i class="fa-solid fa-boxes-stacked text-6xl text-white"></i>
                    </div>
                </div>

                <div class="px-8 pt-14 pb-8 flex-grow">
                    <h3 class="text-xl font-extrabold text-slate-800 mb-1">PT. Eco Syafa Harvest</h3>
                    <p class="text-xs font-bold text-green-500 uppercase tracking-widest mb-4 border-b border-green-100 pb-4">Industry & Trade</p>
                    
                    <div class="prose prose-sm text-slate-600 text-justify mb-6">
                        <p class="mb-2">
                            Bergerak di bidang industri dan perdagangan dengan fokus utama saat ini pada <strong>Pendistribusian Beras</strong>.
                        </p>
                        <p class="mb-2 text-xs">
                            <em>Izin usaha juga mencakup: Air kemasan, buku, tekstil, dan pakaian.</em>
                        </p>
                        <p class="text-xs text-slate-500 bg-slate-50 p-3 rounded-lg border border-slate-100">
                            <strong>Kantor Cabang:</strong><br>
                            Kab. Malang, Kota & Kab. Probolinggo, Lumajang, Jember.
                        </p>
                    </div>

                    <div class="text-xs text-slate-500 flex items-start gap-2 mb-2">
                        <i class="fa-solid fa-location-dot text-green-500 mt-0.5"></i> 
                        <span>Gedung PRAXIS, Jl. Sono Kembang No. 4-6, Surabaya (Pusat)</span>
                    </div>
                </div>

                {{-- Direksi Section --}}
                <div class="bg-slate-50/80 px-8 py-5 border-t border-slate-100 backdrop-blur-sm">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase mb-3 tracking-wider">Susunan Direksi</h4>
                    <ul class="space-y-2 text-xs">
                        <li class="flex justify-between items-center">
                            <span class="text-slate-500 font-medium">Direktur Utama</span>
                            <span class="font-bold text-slate-800 bg-white px-2 py-1 rounded shadow-sm border border-slate-100">Sa’diah, S.Pd.I, M.Pd.I</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="text-slate-500 font-medium">Direktur</span>
                            <span class="font-bold text-slate-800">Puspi, SE</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="text-slate-500 font-medium">Komisaris</span>
                            <span class="font-bold text-slate-800">M. Adhiek</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- C. PT. ATHENA SYAFA PANCA --}}
            <div class="group bg-white rounded-[2rem] shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-slate-100 flex flex-col h-full overflow-hidden relative">
                 {{-- Badge EST --}}
                 <div class="absolute top-4 right-4 z-20 bg-white/20 backdrop-blur text-white text-[10px] font-bold px-3 py-1 rounded-full border border-white/30 shadow-sm">EST 2025</div>

                {{-- Decorative Header --}}
                <div class="h-24 bg-gradient-to-br from-orange-500 to-red-500 relative">
                    <div class="absolute -bottom-10 left-8 p-4 bg-white rounded-2xl shadow-lg">
                        <i class="fa-solid fa-helmet-safety text-3xl text-orange-600"></i>
                    </div>
                    <div class="absolute right-0 top-0 p-4 opacity-10">
                        <i class="fa-solid fa-trowel-bricks text-6xl text-white"></i>
                    </div>
                </div>

                <div class="px-8 pt-14 pb-8 flex-grow">
                    <h3 class="text-xl font-extrabold text-slate-800 mb-1">PT. Athena Syafa Panca</h3>
                    <p class="text-xs font-bold text-orange-500 uppercase tracking-widest mb-4 border-b border-orange-100 pb-4">Construction & Mining</p>
                    
                    <div class="prose prose-sm text-slate-600 text-justify mb-6">
                        <p class="mb-2">
                            Perusahaan termuda yang bergerak strategis di bidang <strong>Konstruksi</strong>. Memiliki cakupan izin usaha di bidang pertambangan dan perdagangan material.
                        </p>
                        <p class="text-xs text-slate-500 bg-slate-50 p-3 rounded-lg border border-slate-100">
                            <strong>Fokus Proyek Saat Ini:</strong><br>
                            Berbagai Kota & Kabupaten di Provinsi Jawa Tengah.
                        </p>
                    </div>

                    <div class="text-xs text-slate-500 flex items-start gap-2 mb-2">
                        <i class="fa-solid fa-map-location-dot text-orange-500 mt-0.5"></i> 
                        <span>Operasional: Jawa Tengah</span>
                    </div>
                </div>

                {{-- Direksi Section --}}
                <div class="bg-slate-50/80 px-8 py-5 border-t border-slate-100 backdrop-blur-sm">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase mb-3 tracking-wider">Susunan Direksi</h4>
                    <ul class="space-y-2 text-xs">
                        <li class="flex justify-between items-center">
                            <span class="text-slate-500 font-medium">Direktur Utama</span>
                            <span class="font-bold text-slate-800 bg-white px-2 py-1 rounded shadow-sm border border-slate-100">Eliza Putri S, SH</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="text-slate-500 font-medium">Direktur</span>
                            <span class="font-bold text-slate-800">Linda Adelia, SE</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="text-slate-500 font-medium">Komisaris</span>
                            <span class="font-bold text-slate-800">Puguh P.W., Amd</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ======================================================================= --}}
{{-- 4. VISI & MISI (Side by Side)                                           --}}
{{-- ======================================================================= --}}
<section class="py-24 bg-white relative overflow-hidden">
    {{-- Background Circle Decoration --}}
    <div class="absolute -left-20 top-1/2 -translate-y-1/2 w-96 h-96 bg-slate-50 rounded-full blur-3xl -z-10"></div>

    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row items-center gap-16">
            
            {{-- Visual / Image Placeholder --}}
            <div class="w-full md:w-1/2 relative">
                <div class="relative z-10 bg-slate-900 rounded-[2.5rem] p-12 text-white shadow-2xl transform rotate-1 hover:rotate-0 transition-transform duration-500">
                    <div class="w-20 h-20 bg-blue-500 rounded-2xl flex items-center justify-center text-4xl mb-8">
                        <i class="fa-solid fa-rocket"></i>
                    </div>
                    <h3 class="text-3xl font-black mb-6">Komitmen <br>Pertumbuhan</h3>
                    <p class="text-slate-300 text-lg leading-relaxed">
                        "Menjadi pilihan nyata bagi masyarakat sekitar dan relasi yang berujung kepada pertumbuhan ekonomi Tanah Air."
                    </p>
                </div>
                {{-- Decor --}}
                <div class="absolute -bottom-10 -right-10 w-full h-full border-2 border-slate-200 rounded-[2.5rem] -z-10"></div>
            </div>

            {{-- Text Content --}}
            <div class="w-full md:w-1/2">
                <div class="mb-12">
                    <h3 class="text-2xl font-black text-slate-800 mb-4 flex items-center gap-3">
                        <span class="text-blue-600">01.</span> VISI
                    </h3>
                    <p class="text-slate-600 text-lg leading-relaxed">
                        Mengembangkan group bisnis dengan semangat inovasi, menciptakan lapangan kerja, serta meningkatkan kesejahteraan masyarakat melalui pertumbuhan ekonomi.
                    </p>
                </div>

                <div>
                    <h3 class="text-2xl font-black text-slate-800 mb-4 flex items-center gap-3">
                        <span class="text-blue-600">02.</span> MISI
                    </h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <i class="fa-solid fa-check-circle text-blue-500 text-xl mt-0.5"></i>
                            <span class="text-slate-700 font-medium">Menjadi mitra bisnis yang memberikan keuntungan bagi semua pihak.</span>
                        </li>
                        <li class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <i class="fa-solid fa-check-circle text-blue-500 text-xl mt-0.5"></i>
                            <span class="text-slate-700 font-medium">Menjadi pilihan utama konsumen melalui pelayanan prima.</span>
                        </li>
                        <li class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <i class="fa-solid fa-check-circle text-blue-500 text-xl mt-0.5"></i>
                            <span class="text-slate-700 font-medium">Menciptakan lingkungan kerja yang mensejahterakan karyawan.</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ======================================================================= --}}
{{-- 5. OUR TEAM (Tim Manajemen)                                             --}}
{{-- ======================================================================= --}}
<section id="tim" class="py-24 bg-white relative overflow-hidden">
    {{-- Background Decoration --}}
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
    <div class="absolute right-0 top-1/3 w-72 h-72 bg-blue-100/30 rounded-full blur-3xl mix-blend-multiply"></div>
    <div class="absolute left-0 bottom-1/3 w-72 h-72 bg-cyan-100/30 rounded-full blur-3xl mix-blend-multiply"></div>

    <div class="container mx-auto px-6 relative z-10">
        
        {{-- Section Header --}}
        <div class="text-center mb-16">
            <div class="inline-block bg-blue-50 text-blue-600 font-bold px-4 py-1 rounded-full text-xs uppercase tracking-widest mb-4">Profesional & Berpengalaman</div>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-800 mb-4">
                Tim <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Manajemen</span>
            </h2>
            <div class="h-1.5 w-24 bg-gradient-to-r from-blue-600 to-cyan-400 mx-auto rounded-full mb-6"></div>
            <p class="text-slate-500 text-lg max-w-2xl mx-auto">
                Dibalik kesuksesan Syafa Group, terdapat individu-individu berdedikasi tinggi yang berkomitmen memberikan solusi terbaik.
            </p>
        </div>
        

        {{-- Team Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            
            {{-- Loop Data Team (1-9) --}}
            @php
                // Data Dummy untuk Jabatan (Bisa disesuaikan nanti)
                $roles = [
                    1 => ['role' => 'Founder & CEO', 'color' => 'text-blue-600'],
                    2 => ['role' => 'Direktur Keuangan', 'color' => 'text-slate-600'],
                    3 => ['role' => 'MANAGER SUMBER DAYA MANUSIA', 'color' => 'text-slate-600'],
                    4 => ['role' => 'ASISTEN DIREKSI', 'color' => 'text-slate-500'],
                    5 => ['role' => 'Manager IT', 'color' => 'text-slate-500'],
                    6 => ['role' => 'STAFF LEGAL', 'color' => 'text-slate-500'],
                    7 => ['role' => 'Manager KEAGAMAAN', 'color' => 'text-slate-500'],
                    8 => ['role' => 'Manager OPERASIONAL', 'color' => 'text-slate-500'],
                    9 => ['role' => 'ASISTEN MANAGER SUMBER DAYA MANUSIA', 'color' => 'text-slate-500'],
                ];
            @endphp

            @for ($i = 1; $i <= 9; $i++)
                <div class="group relative bg-white rounded-3xl p-4 shadow-lg border border-slate-100 hover:shadow-2xl hover:border-blue-200 transition-all duration-500 hover:-translate-y-2">
                    
                    {{-- Image Container --}}
                    <div class="relative w-full aspect-[4/5] rounded-2xl overflow-hidden bg-slate-100 mb-6">
                        {{-- Background Gradient saat Hover --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>
                        
                        {{-- Image --}}
                        {{-- Menggunakan asset path sesuai request: public/img/team/1.png dst --}}
                        <img src="{{ asset('img/team/' . $i . '.png') }}" 
                             alt="Team Member {{ $i }}" 
                             class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-110"
                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=Team+{{ $i }}&background=cbd5e1&color=fff&size=500';"> {{-- Fallback jika gambar tidak ada --}}
                        
                        {{-- Social Icons (Muncul saat Hover) --}}
                        <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-3 translate-y-10 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 z-20">
                            <a href="#" class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center hover:bg-white hover:text-blue-600 transition-colors"><i class="fa-brands fa-linkedin-in"></i></a>
                            <a href="#" class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center hover:bg-white hover:text-blue-600 transition-colors"><i class="fa-brands fa-instagram"></i></a>
                            <a href="#" class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center hover:bg-white hover:text-blue-600 transition-colors"><i class="fa-regular fa-envelope"></i></a>
                        </div>
                    </div>

                    {{-- Text Content --}}
                    <div class="text-center px-2 pb-4">
                        <h4 class="text-xl font-bold text-slate-800 mb-1 group-hover:text-blue-600 transition-colors">
                            {{-- Nama Dummy (Ganti sesuai kebutuhan) --}}
                            @if($i == 1)  @else Nama Lengkap {{ $i }} @endif
                        </h4>
                        <p class="text-sm font-bold uppercase tracking-wider {{ $roles[$i]['color'] }}">
                            {{ $roles[$i]['role'] }}
                        </p>
                        
                        {{-- Garis Hiasan Kecil --}}
                        <div class="w-8 h-1 bg-slate-200 mx-auto mt-4 rounded-full group-hover:bg-blue-500 group-hover:w-16 transition-all duration-300"></div>
                    </div>

                    {{-- Corner Accent (Opsional) --}}
                    @if($i == 1)
                        <div class="absolute top-6 right-6 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-blue-500/30 z-20" title="Founder">
                            <i class="fa-solid fa-star text-xs"></i>
                        </div>
                    @endif
                </div>
            @endfor

        </div>
    </div>
</section>


{{-- ======================================================================= --}}
{{-- 5. CONTACT SECTION                                                      --}}
{{-- ======================================================================= --}}
<section id="kontak" class="py-24 bg-slate-900 text-white relative overflow-hidden">
    {{-- Decorative Blobs --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600 rounded-full blur-[120px] opacity-20"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-cyan-500 rounded-full blur-[120px] opacity-20"></div>

    <div class="container mx-auto px-6 relative z-10 text-center">
        <h2 class="text-3xl sm:text-4xl font-black mb-6">Hubungi Kami</h2>
        <p class="text-slate-400 text-lg mb-12 max-w-2xl mx-auto">
            Kami siap berdiskusi mengenai kebutuhan bisnis dan kerjasama strategis dengan Anda.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto text-left">
            <div class="bg-white/5 backdrop-blur-md p-8 rounded-3xl border border-white/10 hover:bg-white/10 transition-colors">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-xl mb-6">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <h4 class="font-bold text-lg mb-2">Kantor Pusat</h4>
                <p class="text-slate-300 leading-relaxed">Gedung PRAXIS,<br>Jl. Sono Kembang No. 4-6,<br>Surabaya, Jawa Timur</p>
            </div>
            
            <div class="bg-white/5 backdrop-blur-md p-8 rounded-3xl border border-white/10 hover:bg-white/10 transition-colors">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-xl mb-6">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <h4 class="font-bold text-lg mb-2">Kontak & WhatsApp</h4>
                <p class="text-slate-300 leading-relaxed mb-4">(0321) 123456</p>
                <a href="#" class="text-green-400 font-bold hover:text-green-300 inline-flex items-center gap-2">
                    Chat Sekarang <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="bg-white/5 backdrop-blur-md p-8 rounded-3xl border border-white/10 hover:bg-white/10 transition-colors">
                <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center text-xl mb-6">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <h4 class="font-bold text-lg mb-2">Jam Operasional</h4>
                <p class="text-slate-300 leading-relaxed">
                    Senin - Jumat<br>
                    08.00 - 16.00 WIB
                </p>
            </div>
        </div>
        
        <div class="mt-16 pt-8 border-t border-white/10 text-slate-500 text-sm">
            &copy; {{ date('Y') }} Syafa Group. All Rights Reserved.
        </div>
    </div>
</section>

@endsection