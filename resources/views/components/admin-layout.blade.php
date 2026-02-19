<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - Contractor System</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/png">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Scripts & Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Custom Scrollbar Premium */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #3b82f6; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #2563eb; }

        /* Sidebar Scrollbar Hidden but scrollable */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-600 selection:bg-blue-200 selection:text-blue-900">

    <div class="min-h-screen flex bg-slate-50/50" x-data="{ sidebarOpen: false }">
        
        {{-- ================= SIDEBAR (DOMINAN GELAP/HITAM ELEGAN) ================= --}}
        {{-- Background diubah ke sangat gelap (slate-950 ke atas) dengan border halus --}}
        <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-[#0b1120] text-slate-300 transition-all duration-300 transform shadow-[20px_0_40px_rgba(0,0,0,0.15)] border-r border-slate-800/50 flex flex-col"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
            
            {{-- Header Sidebar (Logo Asli Dikembalikan) --}}
            <div class="flex items-center gap-4 h-20 px-6 bg-[#0f172a] border-b border-slate-800/80 shrink-0">
                {{-- Menggunakan asset('img/logo.png') asli --}}
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-10 h-10 bg-white p-1.5 rounded-lg shadow-sm object-contain">
                <div class="flex flex-col">
                    <span class="text-lg font-black tracking-tight text-white leading-tight drop-shadow-md">SYAFA GROUP</span>
                    <span class="text-[10px] font-bold text-blue-400 tracking-[0.2em] uppercase">Internal System</span>
                </div>
            </div>

            {{-- Menu Items --}}
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto sidebar-scroll pb-24">
                
                {{-- KHUSUS ADMIN PUSAT --}}
                @if(Auth::user()->role == 'admin')
                    <div class="mb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-2">Utama</div>
                    
                    {{-- Item Menu Aktif: Background Biru Solid yang Tegas --}}
                    {{-- Item Menu Tidak Aktif: Hover halus ke abu-abu gelap --}}
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-home w-6 text-center {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-blue-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Dashboard</span>
                    </a>
                    
                    <div class="mt-6 mb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Master Data</div>
                    
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-users w-6 text-center {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-500 group-hover:text-blue-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Data Pengguna</span>
                    </a>
                    <a href="{{ route('admin.teams.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('admin.teams.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-user-tie w-6 text-center {{ request()->routeIs('admin.teams.*') ? 'text-white' : 'text-slate-500 group-hover:text-blue-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Manajemen Tim</span>
                    </a>
                    <a href="{{ route('admin.projects.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('admin.projects.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-building w-6 text-center {{ request()->routeIs('admin.projects.*') ? 'text-white' : 'text-slate-500 group-hover:text-blue-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Data Proyek</span>
                    </a>
                    <a href="{{ route('admin.locations.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('admin.locations.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-map-marked-alt w-6 text-center {{ request()->routeIs('admin.locations.*') ? 'text-white' : 'text-slate-500 group-hover:text-blue-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Kelola Cabang</span>
                    </a>

                    <div class="mt-6 mb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Publikasi & Log</div>

                    <a href="{{ route('admin.news.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('admin.news.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-newspaper w-6 text-center {{ request()->routeIs('admin.news.*') ? 'text-white' : 'text-slate-500 group-hover:text-blue-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Berita & Artikel</span>
                    </a>
                    <a href="{{ route('login-history.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('login-history.index') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-history w-6 text-center {{ request()->routeIs('login-history.index') ? 'text-white' : 'text-slate-500 group-hover:text-blue-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Riwayat Login</span>
                    </a>
                @endif


                {{-- KHUSUS ECO (ADMIN KANTOR - DIVISI BERAS & LINGKUNGAN) --}}
                @if(Auth::user()->role == 'eco')
                    <div class="mb-2 px-4 text-[10px] font-bold text-emerald-500 uppercase tracking-widest mt-2">Dashboard & Laporan</div>
                    
                    <a href="{{ route('eco.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('eco.dashboard') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-warehouse w-6 text-center {{ request()->routeIs('eco.dashboard') ? 'text-white' : 'text-emerald-500 group-hover:text-emerald-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Dashboard Stok</span>
                    </a>
                    <a href="{{ route('eco.lhkp.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('eco.lhkp.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-id-badge w-6 text-center {{ request()->routeIs('eco.lhkp.*') ? 'text-white' : 'text-emerald-500 group-hover:text-emerald-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Input LHKP</span>
                    </a>

                    <div class="mt-6 mb-2 px-4 text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Operasional Toko</div>

                    <a href="{{ route('eco.visit-plans.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('eco.visit-plans.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-route w-6 text-center {{ request()->routeIs('eco.visit-plans.*') ? 'text-white' : 'text-emerald-500 group-hover:text-emerald-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Plan Kunjungan</span>
                    </a>
                    <a href="{{ route('eco.visit-results.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('eco.visit-results.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-clipboard-check w-6 text-center {{ request()->routeIs('eco.visit-results.*') ? 'text-white' : 'text-emerald-500 group-hover:text-emerald-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Hasil Kunjungan</span>
                    </a>
                    <a href="{{ route('eco.store-partners.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('eco.store-partners.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-store-alt w-6 text-center {{ request()->routeIs('eco.store-partners.*') ? 'text-white' : 'text-emerald-500 group-hover:text-emerald-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Mitra Toko</span>
                    </a>
                    <a href="{{ route('eco.store-rice-stocks.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('eco.store-rice-stocks.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-boxes w-6 text-center {{ request()->routeIs('eco.store-rice-stocks.*') ? 'text-white' : 'text-emerald-500 group-hover:text-emerald-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Stok Beras Toko</span>
                    </a>
                    <a href="{{ route('eco.sold-rices.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('eco.sold-rices.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-shopping-cart w-6 text-center {{ request()->routeIs('eco.sold-rices.*') ? 'text-white' : 'text-emerald-500 group-hover:text-emerald-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Beras Terjual</span>
                    </a>
                    <a href="{{ route('eco.milling-reports.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('eco.milling-reports.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-tractor w-6 text-center {{ request()->routeIs('eco.milling-reports.*') ? 'text-white' : 'text-emerald-500 group-hover:text-emerald-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Laporan Selep</span>
                    </a>
                    <a href="{{ route('eco.plastic-stocks.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('eco.plastic-stocks.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-shopping-bag w-6 text-center {{ request()->routeIs('eco.plastic-stocks.*') ? 'text-white' : 'text-emerald-500 group-hover:text-emerald-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Stok Plastik</span>
                    </a>

                    <div class="mt-6 mb-2 px-4 text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Publikasi</div>

                    <a href="{{ route('eco.portfolios.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('eco.portfolios.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-images w-6 text-center {{ request()->routeIs('eco.portfolios.*') ? 'text-white' : 'text-emerald-500 group-hover:text-emerald-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Portofolio Eco</span>
                    </a>
                @endif


                {{-- KHUSUS KEPALA KANTOR --}}
                @if(Auth::user()->role == 'kepala_kantor')
                    <div class="mb-2 px-4 text-[10px] font-bold text-amber-500 uppercase tracking-widest mt-2">Kepala Kantor</div>
                    
                    <a href="{{ route('kepala_kantor.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('kepala_kantor.dashboard') ? 'bg-amber-600 text-white shadow-md shadow-amber-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                         <i class="fas fa-file-signature w-6 text-center text-amber-500 group-hover:text-amber-300"></i>
                        <span class="ml-3 font-semibold text-sm">Dashboard Input Laporan (LH)</span>
                    </a>
                    {{-- <a href="{{ route('kepala_kantor.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group text-slate-400 hover:bg-slate-800/50 hover:text-white">
                        <i class="fas fa-file-signature w-6 text-center text-amber-500 group-hover:text-amber-300"></i>
                        <span class="ml-3 font-semibold text-sm">Input Laporan (LH)</span>
                    </a> --}}
                @endif


                {{-- KHUSUS MANAGER UNIT --}}
                @if(Auth::user()->role == 'manager_unit')
                    <div class="mb-2 px-4 text-[10px] font-bold text-purple-500 uppercase tracking-widest mt-2">Manager Unit</div>
                    
                    <a href="{{ route('manager_unit.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('manager_unit.dashboard') ? 'bg-purple-600 text-white shadow-md shadow-purple-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-chart-line w-6 text-center {{ request()->routeIs('manager_unit.dashboard') ? 'text-white' : 'text-purple-500 group-hover:text-purple-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Dashboard Evaluasi (Download LHKP & LH)</span>
                    </a>
                   
                @endif


                {{-- KHUSUS KEUANGAN ECO --}}
                @if(Auth::user()->role == 'keuangan_eco')
                    <div class="mb-2 px-4 text-[10px] font-bold text-teal-500 uppercase tracking-widest mt-2">Keuangan Eco</div>
                    <a href="{{ route('keuangan_eco.visit-results.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('keuangan_eco.visit-results.*') ? 'bg-teal-600 text-white shadow-md shadow-teal-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-file-excel w-6 text-center {{ request()->routeIs('keuangan_eco.visit-results.*') ? 'text-white' : 'text-teal-500 group-hover:text-teal-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Hasil Kunjungan (Excel)</span>
                    </a>
                @endif


                {{-- KHUSUS KEUANGAN INDIE --}}
                @if(Auth::user()->role == 'keuangan_indie')
                    <div class="mb-2 px-4 text-[10px] font-bold text-blue-500 uppercase tracking-widest mt-2">Keuangan Indie</div>
                    
                    <a href="{{ route('keuangan.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('keuangan.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-file-invoice-dollar w-6 text-center {{ request()->routeIs('keuangan.dashboard') ? 'text-white' : 'text-blue-500 group-hover:text-blue-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Verifikasi Laporan</span>
                    </a>
                @endif


                {{-- KHUSUS INDIE (DIVISI INFRASTRUKTUR & GEDUNG) --}}
                @if(Auth::user()->role == 'indie')
                    <div class="mb-2 px-4 text-[10px] font-bold text-indigo-500 uppercase tracking-widest mt-2">Operasional</div>
                    
                    <a href="{{ route('indie.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('indie.dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-city w-6 text-center {{ request()->routeIs('indie.dashboard') ? 'text-white' : 'text-indigo-500 group-hover:text-indigo-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Dashboard Proyek</span>
                    </a>

                    <div class="mt-6 mb-2 px-4 text-[10px] font-bold text-indigo-500 uppercase tracking-widest">Publikasi</div>

                    <a href="{{ route('indie.news.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('indie.news.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-newspaper w-6 text-center {{ request()->routeIs('indie.news.*') ? 'text-white' : 'text-indigo-500 group-hover:text-indigo-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Portal Berita</span>
                    </a>

                    <a href="{{ route('indie.portfolios.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('indie.portfolios.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-images w-6 text-center {{ request()->routeIs('indie.portfolios.*') ? 'text-white' : 'text-indigo-500 group-hover:text-indigo-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Portofolio Indie</span>
                    </a>
                @endif


                {{-- KHUSUS SUBKON PT --}}
                @if(Auth::user()->role == 'subkon_pt')
                    <div class="mb-2 px-4 text-[10px] font-bold text-blue-500 uppercase tracking-widest mt-2">Monitoring</div>
                    <a href="{{ route('subkon-pt.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('subkon-pt.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-chart-pie w-6 text-center {{ request()->routeIs('subkon-pt.dashboard') ? 'text-white' : 'text-blue-500 group-hover:text-blue-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Dashboard Monitoring</span>
                    </a>
                @endif


                {{-- KHUSUS SUBKON EKS (VENDOR) --}}
                @if(Auth::user()->role == 'subkon_eks')
                    <div class="mb-2 px-4 text-[10px] font-bold text-sky-500 uppercase tracking-widest mt-2">Pekerjaan</div>
                    <a href="{{ route('subkon-eks.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('subkon-eks.dashboard') ? 'bg-sky-600 text-white shadow-md shadow-sky-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-briefcase w-6 text-center {{ request()->routeIs('subkon-eks.dashboard') ? 'text-white' : 'text-sky-500 group-hover:text-sky-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Proyek Saya</span>
                    </a>
                    
                    <a href="{{ route('subkon-eks.reports.create') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('subkon-eks.reports.create') ? 'bg-sky-600 text-white shadow-md shadow-sky-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-file-upload w-6 text-center {{ request()->routeIs('subkon-eks.reports.create') ? 'text-white' : 'text-sky-500 group-hover:text-sky-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Input Laporan</span>
                    </a>
                    <a href="{{ route('subkon-eks.report-payments.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('subkon-eks.report-payments.*') ? 'bg-sky-600 text-white shadow-md shadow-sky-900/30' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <i class="fas fa-file-invoice-dollar w-6 text-center {{ request()->routeIs('subkon-eks.report-payments.*') ? 'text-white' : 'text-sky-500 group-hover:text-sky-300' }}"></i>
                        <span class="ml-3 font-semibold text-sm">Klaim Pembayaran</span>
                    </a>
                @endif


                {{-- ================= MENU PENGATURAN AKUN (SEMUA USER) ================= --}}
                <div class="mt-8 mb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Pengaturan</div>

                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('profile.edit') ? 'bg-slate-700 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <i class="fas fa-user-cog w-6 text-center {{ request()->routeIs('profile.edit') ? 'text-white' : 'text-slate-500 group-hover:text-blue-300' }}"></i>
                    <span class="ml-3 font-semibold text-sm">Edit Profil</span>
                </a>

                {{-- BUTTON LOGOUT SIDEBAR --}}
                <div class="mt-4 pt-4">
                    <form method="POST" action="{{ route('logout') }}" id="sidebar-logout-form">
                        @csrf
                        <button type="button" onclick="confirmLogout()" class="w-full flex items-center px-4 py-3 rounded-xl transition-all duration-300 text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 group">
                            <i class="fas fa-sign-out-alt w-6 text-center group-hover:text-rose-300"></i>
                            <span class="ml-3 font-semibold text-sm">Keluar Sistem</span>
                        </button>
                    </form>
                </div>

            </nav>

            {{-- Sidebar Footer (User Profile dengan Logo Asli) --}}
            <div class="h-20 bg-[#0f172a] border-t border-slate-800/80 absolute bottom-0 w-full flex items-center px-6 shrink-0 z-50">
                <div class="flex items-center gap-3 w-full">
                    {{-- Menggunakan asset('img/logo.png') asli --}}
                    <img src="{{ asset('img/logo.png') }}" alt="User" class="w-10 h-10 rounded-full p-1 bg-white shadow-sm object-contain border border-slate-700 shrink-0">
                    
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-400 truncate capitalize tracking-wide">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ================= MAIN CONTENT WRAPPER ================= --}}
        <div class="flex-1 flex flex-col md:ml-72 transition-all duration-300 min-h-screen relative">
            
            {{-- Topbar (Navbar Atas Modern & Bersih) --}}
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200/80 flex items-center justify-between px-6 sticky top-0 z-40 shadow-sm">
                
                {{-- Hamburger Mobile & Breadcrumb --}}
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-blue-600 focus:outline-none md:hidden bg-white p-2 rounded-lg shadow-sm border border-slate-100 transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <div class="hidden md:flex flex-col">
                        <span class="text-xs font-bold text-blue-600 uppercase tracking-widest">Sistem Manajemen</span>
                        <h2 class="text-xl font-black text-slate-800 leading-tight">
                            @if(request()->routeIs('admin.dashboard') || request()->routeIs('eco.dashboard') || request()->routeIs('indie.dashboard') || request()->routeIs('subkon-pt.dashboard') || request()->routeIs('subkon-eks.dashboard') || request()->routeIs('kepala_kantor.dashboard') || request()->routeIs('manager_unit.dashboard') || request()->routeIs('keuangan.dashboard'))
                                Dashboard Overview
                            @elseif(request()->routeIs('admin.users.*')) Manajemen Pengguna
                            @elseif(request()->routeIs('admin.projects.*')) Manajemen Proyek
                            @elseif(request()->routeIs('admin.news.*')) Berita & Artikel
                            @elseif(request()->routeIs('login-history.index')) Log Aktivitas Login
                            @elseif(request()->routeIs('profile.edit')) Pengaturan Profil
                            @else Ruang Kerja
                            @endif
                        </h2>
                    </div>
                </div>

                {{-- Right Actions (Profile Kanan Atas dengan Logo Asli) --}}
                <div class="flex items-center gap-4">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-bold text-slate-700 leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
                    </div>
                    {{-- Menggunakan asset('img/logo.png') asli --}}
                    <img src="{{ asset('img/logo.png') }}" alt="User" class="w-10 h-10 rounded-full p-1 bg-white shadow-sm object-contain border border-slate-200 shrink-0">
                </div>
            </header>

            {{-- Main Content Slot --}}
            <main class="p-6 md:p-8 flex-1 overflow-y-auto w-full max-w-[100vw]">
                {{ $slot }}
            </main>
        </div>

        {{-- Overlay for Mobile --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm md:hidden transition-opacity" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>
    </div>

    {{-- SCRIPT SWEETALERT LOGOUT --}}
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Keluar Sistem?',
                text: "Sesi Anda akan diakhiri.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', 
                cancelButtonColor: '#cbd5e1', 
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { popup: 'rounded-3xl shadow-2xl border border-slate-100' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('sidebar-logout-form').submit();
                }
            })
        }
    </script>
</body>
</html>