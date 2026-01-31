<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-blue-950/90 backdrop-blur-md border-b border-white/10 shadow-md transition-all duration-300 supports-[backdrop-filter]:bg-blue-950/80">
    
    {{-- Load SweetAlert Library --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            
            {{-- LEFT SIDE --}}
            <div class="flex items-center">
                {{-- BRAND LOGO --}}
                <div class="shrink-0 flex items-center group">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <img src="{{ asset('img/logo.png') }}" class="h-9 w-auto group-hover:scale-110 transition-transform duration-300 drop-shadow-[0_0_5px_rgba(255,255,255,0.3)]" alt="Logo">
                        
                        <div class="hidden lg:block">
                            <h1 class="font-bold text-white tracking-tight text-lg leading-tight">SYAFA GROUP</h1>
                            <p class="text-[10px] text-sky-300 uppercase tracking-widest font-bold bg-blue-900/50 px-1.5 py-0.5 rounded-md inline-block mt-0.5 border border-white/10">Internal System</p>
                        </div>
                    </a>
                </div>

                {{-- DESKTOP MENU ITEMS --}}
                <div class="hidden space-x-2 sm:ms-10 sm:flex items-center">
                    
                    {{-- Dashboard Link --}}
                    <a href="{{ route('dashboard') }}" 
                       class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2.5
                       {{ request()->routeIs('dashboard') 
                          ? 'bg-blue-800 text-white shadow-sm shadow-blue-900/50 border border-white/10' 
                          : 'text-sky-200 hover:text-white hover:bg-white/10' 
                       }}">
                        <i class="fas fa-home text-lg {{ request()->routeIs('dashboard') ? 'text-sky-400' : 'text-sky-200/70' }}"></i> 
                        {{ __('Dashboard') }}
                    </a>

                    {{-- Galeri Link --}}
                    <a href="{{ route('gallery.index') }}" 
                       class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2.5
                       {{ request()->routeIs('gallery.index') 
                          ? 'bg-blue-800 text-white shadow-sm shadow-blue-900/50 border border-white/10' 
                          : 'text-sky-200 hover:text-white hover:bg-white/10' 
                       }}">
                        <i class="fas fa-images text-lg {{ request()->routeIs('gallery.index') ? 'text-sky-400' : 'text-sky-200/70' }}"></i> 
                        {{ __('Galeri Karya') }}
                    </a>
                </div>
            </div>

            {{-- RIGHT SIDE (USER PROFILE) --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="flex items-center gap-4">
                    
                    {{-- Notification --}}
                    <button class="text-sky-200 hover:text-white transition-colors relative p-2 hover:bg-white/10 rounded-full">
                        <i class="fa-regular fa-bell text-xl"></i>
                        <span class="absolute top-1 right-1 block h-2.5 w-2.5 rounded-full ring-2 ring-blue-950 bg-sky-500 animate-pulse"></span>
                    </button>
                    
                    <div class="h-8 w-px bg-white/10"></div>

                    {{-- Dropdown Trigger --}}
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-3 px-2 py-1.5 border border-white/10 text-sm leading-4 font-medium rounded-full text-sky-100 bg-white/5 hover:bg-white/10 hover:shadow-md hover:border-white/20 focus:outline-none transition ease-in-out duration-300 group">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-sky-500 to-blue-700 flex items-center justify-center text-white font-bold shadow-sm group-hover:scale-105 transition-transform border border-white/10">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="flex flex-col items-start mr-1">
                                    <span class="font-bold text-xs text-white">{{ Auth::user()->name }}</span>
                                    <span class="text-[10px] text-sky-300">
                                        @if(Auth::user()->role == 'admin') Admin
                                        @elseif(Auth::user()->role == 'subkon_pt') Internal
                                        @else Vendor @endif
                                    </span>
                                </div>
                                <i class="fa-solid fa-chevron-down text-[10px] text-sky-400 mr-2"></i>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
                                <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Signed in as</p>
                                <p class="text-sm font-semibold text-slate-700 truncate mt-0.5">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="p-1">
                                <x-dropdown-link :href="route('profile.edit')" class="rounded-lg hover:bg-sky-50 hover:text-sky-600 group transition-colors">
                                    <span class="w-6 inline-block text-center mr-2"><i class="fas fa-user-gear text-slate-400 group-hover:text-sky-500"></i></span> {{ __('Profile') }}
                                </x-dropdown-link>
                                
                                {{-- LOGOUT DESKTOP --}}
                                <form method="POST" action="{{ route('logout') }}" id="logout-form-desktop">
                                    @csrf
                                    <button type="button" onclick="confirmLogoutDesktop(event)" 
                                        class="w-full text-left px-4 py-2 text-sm leading-5 text-red-600 hover:bg-red-50 hover:text-red-700 transition duration-150 ease-in-out rounded-lg flex items-center group">
                                        <span class="w-6 inline-block text-center mr-2"><i class="fas fa-sign-out-alt text-red-400 group-hover:text-red-600"></i></span> 
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

             {{-- HAMBURGER MENU (MOBILE) --}}
             <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-sky-200 hover:text-white hover:bg-white/10 focus:outline-none transition duration-300">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    {{-- Responsive Menu (Mobile) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-blue-950 border-t border-white/10">
        <div class="pt-2 pb-3 space-y-1 px-3">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                class="rounded-lg text-sky-100 hover:bg-white/10 hover:text-white"
                activeClasses="bg-blue-800 text-white border-white/10">
                <i class="fas fa-home mr-2 w-5 text-center"></i> {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('gallery.index')" :active="request()->routeIs('gallery.index')" 
                class="rounded-lg text-sky-100 hover:bg-white/10 hover:text-white"
                activeClasses="bg-blue-800 text-white border-white/10">
                <i class="fas fa-images mr-2 w-5 text-center"></i> {{ __('Galeri Karya') }}
            </x-responsive-nav-link>
        </div>
        
        {{-- Mobile User Info --}}
        <div class="pt-4 pb-1 border-t border-white/10 bg-blue-900/30">
            <div class="px-4 flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sky-500 to-blue-700 flex items-center justify-center text-white font-bold border border-white/10">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-bold text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-sky-300">{{ Auth::user()->email }}</div>
                </div>
            </div>
            
            <div class="mt-3 space-y-1 px-3 pb-3">
                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-lg text-sky-100 hover:bg-white/10 hover:text-white">
                    <i class="fas fa-user-gear mr-2"></i> {{ __('Profile') }}
                </x-responsive-nav-link>
                
                {{-- LOGOUT MOBILE --}}
                <form method="POST" action="{{ route('logout') }}" id="logout-form-mobile">
                    @csrf
                    <button type="button" onclick="confirmLogoutMobile(event)" class="w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-red-400 hover:text-red-300 hover:bg-red-500/10 hover:border-red-500 transition duration-150 ease-in-out flex items-center rounded-lg">
                        <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT ALERT LOGOUT --}}
    <script>
        function confirmLogoutDesktop(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Keluar?',
                text: "Sesi Anda akan berakhir.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { popup: 'rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form-desktop').submit();
                }
            })
        }

        function confirmLogoutMobile(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Keluar?',
                text: "Anda yakin ingin keluar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { popup: 'rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form-mobile').submit();
                }
            })
        }
    </script>
</nav>