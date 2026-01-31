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
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-600">

    <div class="min-h-screen flex bg-slate-50" x-data="{ sidebarOpen: false }">
        
        {{-- ================= SIDEBAR ================= --}}
        <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-white transition-transform duration-300 transform shadow-2xl"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
            
            {{-- Header Sidebar (Logo) --}}
            <div class="flex items-center gap-3 h-20 px-6 border-b border-slate-800 bg-slate-950">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-10 w-auto bg-white p-1 rounded">
                <div class="flex flex-col">
                    <span class="text-lg font-black tracking-tight text-white leading-tight">SYAFA GROUP</span>
                    <span class="text-[10px] font-bold text-blue-500 tracking-widest uppercase">Internal System</span>
                </div>
            </div>


            {{-- Menu Items --}}
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto h-[calc(100vh-10rem)]">
                
               {{-- KHUSUS ADMIN --}}
                @if(Auth::user()->role == 'admin')
                    <div class="mb-2 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Utama</div>
                    
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-[0_0_20px_rgba(37,99,235,0.3)]' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fas fa-home w-5 text-center {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                        <span class="ml-3 font-medium">Dashboard</span>
                    </a>

                    <div class="mt-6 mb-2 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Master Data</div>
                    
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white shadow-[0_0_20px_rgba(37,99,235,0.3)]' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fas fa-users w-5 text-center {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                        <span class="ml-3 font-medium">Data Pengguna</span>
                    </a>

                    <a href="{{ route('admin.projects.index') }}" class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.projects.*') ? 'bg-blue-600 text-white shadow-[0_0_20px_rgba(37,99,235,0.3)]' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fas fa-building w-5 text-center {{ request()->routeIs('admin.projects.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                        <span class="ml-3 font-medium">Data Proyek</span>
                    </a>

                    <a href="{{ route('admin.locations.index') }}" class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.locations.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
    <i class="fas fa-map-marked-alt w-5 text-center {{ request()->routeIs('admin.locations.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
    <span class="ml-3 font-medium">Kelola Data Cabang</span>
</a>

                    <div class="mt-6 mb-2 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Publikasi</div>

                    <a href="{{ route('admin.news.index') }}" class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.news.*') ? 'bg-blue-600 text-white shadow-[0_0_20px_rgba(37,99,235,0.3)]' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fas fa-newspaper w-5 text-center {{ request()->routeIs('admin.news.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                        <span class="ml-3 font-medium">Berita & Artikel</span>
                    </a>
                    <a href="{{ route('admin.portfolios.index') }}" class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.portfolios.*') ? 'bg-blue-600 text-white shadow-[0_0_20px_rgba(37,99,235,0.3)]' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
    <i class="fas fa-briefcase w-5 text-center {{ request()->routeIs('admin.portfolios.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
    <span class="ml-3 font-medium">Portofolio</span>
</a>

                    {{-- MENU RIWAYAT LOGIN --}}
                    <div class="mt-6 mb-2 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Log Sistem</div>

                    <a href="{{ route('login-history.index') }}" class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('login-history.index') ? 'bg-blue-600 text-white shadow-[0_0_20px_rgba(37,99,235,0.3)]' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fas fa-history w-5 text-center {{ request()->routeIs('login-history.index') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                        <span class="ml-3 font-medium">Riwayat Login</span>
                    </a>
                @endif

                {{-- KHUSUS SUBKON PT --}}
                @if(Auth::user()->role == 'subkon_pt')
                    <div class="mb-2 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Monitoring</div>
                    <a href="{{ route('subkon-pt.dashboard') }}" class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('subkon-pt.dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fas fa-chart-pie w-5 text-center"></i>
                        <span class="ml-3 font-medium">Dashboard Monitoring</span>
                    </a>
                @endif

{{-- KHUSUS KEUANGAN --}}
@if(Auth::user()->role == 'keuangan')
    <div class="mb-2 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Verifikasi</div>
    
    <a href="{{ route('keuangan.dashboard') }}" class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('keuangan.dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <i class="fas fa-file-invoice-dollar w-5 text-center"></i>
        <span class="ml-3 font-medium">Verifikasi Laporan</span>
    </a>
@endif

{{-- KHUSUS ECO (DIVISI BERAS & LINGKUNGAN) --}}
@if(Auth::user()->role == 'eco')
    <div class="mb-2 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Operasional</div>
    
    <a href="{{ route('eco.dashboard') }}" class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('eco.dashboard') ? 'bg-emerald-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <i class="fas fa-warehouse w-5 text-center"></i>
        <span class="ml-3 font-medium">Dashboard Stok</span>
    </a>

    {{-- Menu Konten (Reuse route admin jika controller-nya sama, atau buat khusus) --}}
    <div class="mt-6 mb-2 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Publikasi</div>

    <a href="{{ route('eco.news.index') }}" class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('eco.news.*') ? 'bg-emerald-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <i class="fas fa-newspaper w-5 text-center"></i>
        <span class="ml-3 font-medium">Portal Berita</span>
    </a>

    <a href="{{ route('eco.portfolios.index') }}" class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('eco.portfolios.*') ? 'bg-emerald-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <i class="fas fa-images w-5 text-center"></i>
        <span class="ml-3 font-medium">Portofolio Eco</span>
    </a>
@endif

                {{-- KHUSUS SUBKON EKS (VENDOR) --}}
                @if(Auth::user()->role == 'subkon_eks')
                    <div class="mb-2 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Pekerjaan</div>
                    <a href="{{ route('subkon-eks.dashboard') }}" class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('subkon-eks.dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fas fa-briefcase w-5 text-center"></i>
                        <span class="ml-3 font-medium">Proyek Saya</span>
                    </a>
                    
                    <a href="{{ route('subkon-eks.reports.create') }}" class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('subkon-eks.reports.create') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fas fa-file-upload w-5 text-center"></i>
                        <span class="ml-3 font-medium">Input Laporan</span>
                    </a>
                    {{-- MENU : Report Payment --}}
    <a href="{{ route('subkon-eks.report-payments.index') }}" class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('subkon-eks.report-payments.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <i class="fas fa-file-invoice-dollar w-5 text-center"></i>
        <span class="ml-3 font-medium">Klaim Pembayaran</span>
    </a>
                @endif

                {{-- ================= MENU PENGATURAN AKUN (SEMUA USER) ================= --}}
                <div class="mt-6 mb-2 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Akun Saya</div>

                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('profile.edit') ? 'bg-blue-600 text-white shadow-[0_0_20px_rgba(37,99,235,0.3)]' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-user-cog w-5 text-center {{ request()->routeIs('profile.edit') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                    <span class="ml-3 font-medium">Edit Profil</span>
                </a>

                {{-- BUTTON LOGOUT SIDEBAR (UNTUK SEMUA ROLE) --}}
                <div class="mt-4 border-t border-slate-800 pt-4">
                    <form method="POST" action="{{ route('logout') }}" id="sidebar-logout-form">
                        @csrf
                        <button type="button" onclick="confirmLogout()" class="w-full flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 text-red-400 hover:bg-red-500/10 hover:text-red-300 group">
                            <i class="fas fa-sign-out-alt w-5 text-center group-hover:text-red-300"></i>
                            <span class="ml-3 font-medium">Keluar Sistem</span>
                        </button>
                    </form>
                </div>

            </nav>

            {{-- Sidebar Footer (User Profile) --}}
            <div class="border-t border-slate-800 p-4 bg-slate-950 absolute bottom-0 w-full">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-400 truncate capitalize">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ================= MAIN CONTENT WRAPPER ================= --}}
        <div class="flex-1 flex flex-col md:ml-72 transition-all duration-300 min-h-screen">
            
            {{-- Topbar (Navbar Atas) --}}
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-6 sticky top-0 z-40">
                
                {{-- Hamburger Mobile --}}
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-slate-800 focus:outline-none md:hidden">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    {{-- Breadcrumb Simple --}}
                    <h2 class="text-xl font-bold text-slate-800 hidden md:block">
                        @if(request()->routeIs('admin.dashboard')) Overview Dashboard
                        @elseif(request()->routeIs('admin.users.*')) Manajemen Pengguna
                        @elseif(request()->routeIs('admin.projects.*')) Manajemen Proyek
                        @elseif(request()->routeIs('admin.news.*')) Berita & Artikel
                        @elseif(request()->routeIs('login-history.index')) Log Aktivitas Login
                        @elseif(request()->routeIs('profile.edit')) Pengaturan Profil
                        @else Halaman Sistem
                        @endif
                    </h2>
                </div>

                {{-- Right Actions --}}
                <div class="flex items-center gap-4">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-bold text-slate-700">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-lg shadow-blue-500/20">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            {{-- Main Content Slot --}}
            <main class="p-6 md:p-8 flex-1 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>

        {{-- Overlay for Mobile --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm md:hidden transition-opacity" 
             style="display: none;"></div>
    </div>

    {{-- SCRIPT SWEETALERT LOGOUT --}}
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Keluar Sistem?',
                text: "Sesi Anda akan diakhiri.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', // Red
                cancelButtonColor: '#cbd5e1', // Slate
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { popup: 'rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('sidebar-logout-form').submit();
                }
            })
        }
    </script>
</body>
</html>