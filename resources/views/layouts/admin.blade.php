<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - Contractor System</title>

    {{-- Scripts & Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-600">

    <div class="min-h-screen flex" x-data="{ sidebarOpen: false }">
        
        {{-- SIDEBAR --}}
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transition-transform duration-300 transform"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
            
            {{-- Logo Area --}}
            <div class="flex items-center justify-center h-20 border-b border-slate-700 bg-slate-950">
                <h1 class="text-xl font-bold tracking-wider">ADMIN <span class="text-blue-500">PANEL</span></h1>
            </div>

            {{-- Menu Items --}}
            <nav class="flex-1 px-4 py-6 space-y-2">
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-home w-6"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-wider mt-6 mb-2">Master Data</p>

                {{-- Menu Kelola User --}}
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-users w-6"></i>
                    <span class="font-medium">Data Pengguna</span>
                </a>

                {{-- Menu Kelola Proyek --}}
                <a href="{{ route('admin.projects.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.projects.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-building w-6"></i>
                    <span class="font-medium">Data Proyek</span>
                </a>

                <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-wider mt-6 mb-2">Akun</p>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center px-4 py-3 rounded-xl text-red-400 hover:bg-red-900/20 hover:text-red-300 transition-colors cursor-pointer">
                        <i class="fas fa-sign-out-alt w-6"></i>
                        <span class="font-medium">Keluar</span>
                    </a>
                </form>
            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col md:ml-64 transition-all duration-300">
            
            {{-- Topbar Mobile --}}
            <header class="flex items-center justify-between p-4 bg-white border-b border-slate-200 md:hidden">
                <div class="font-bold text-slate-800">Syafa Group Internal</div>
                <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </header>

            {{-- Content Slot --}}
            <main class="p-6 md:p-10 flex-1 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>

        {{-- Overlay for Mobile --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black opacity-50 md:hidden" style="display: none;"></div>
    </div>
</body>
</html>