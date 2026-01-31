<x-guest-layout>
    {{-- 1. LOAD LIBRARY (Animate.css, FontAwesome, SweetAlert2) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Background Animation */
        .bg-pattern {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
            background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
            overflow: hidden;
        }
        
        .blob {
            position: absolute; filter: blur(80px); opacity: 0.4;
            animation: float 10s infinite ease-in-out;
        }
        .blob-1 { top: -10%; left: -10%; width: 500px; height: 500px; background: #3b82f6; }
        .blob-2 { bottom: -10%; right: -10%; width: 400px; height: 400px; background: #0ea5e9; }
        .blob-3 { top: 40%; left: 40%; width: 300px; height: 300px; background: #6366f1; opacity: 0.3; }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.05); }
        }

        /* Modern Glass Card */
        .login-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* Input Styling */
        .glass-input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white; transition: all 0.3s ease;
        }
        .glass-input:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #38bdf8; outline: none;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        }
        .glass-input::placeholder { color: #94a3b8; }
        
        /* Autofill Fix for Dark Theme */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #1e293b inset !important;
            -webkit-text-fill-color: white !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>

    {{-- BACKGROUND --}}
    <div class="bg-pattern">
        <div class="blob blob-1"></div>
        <div class="blob blob-2" style="animation-delay: 2s;"></div>
        <div class="blob blob-3" style="animation-delay: 4s;"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 contrast-150"></div>
    </div>

    {{-- TOMBOL KEMBALI KE BERANDA (POSITION FIXED) --}}
    <a href="{{ url('/') }}" class="fixed top-6 left-6 z-50 flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 backdrop-blur-md border border-white/10 text-white/80 hover:text-white hover:bg-white/10 hover:border-white/20 transition-all duration-300 text-sm font-bold group shadow-lg">
        <i class="fa-solid fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
        <span>Beranda</span>
    </a>

    {{-- MAIN CONTENT --}}
    <div class="min-h-screen w-full flex flex-col justify-center items-center px-4 py-12 relative z-10 overflow-y-auto font-sans">
        
        <div class="w-full max-w-md my-auto flex flex-col items-center">

            {{-- LOGO --}}
            <div class="mb-8 text-center animate__animated animate__fadeInDown">
                <a href="{{ url('/') }}" class="inline-block group">
                    <div class="relative mb-5 mx-auto w-24 h-24 flex items-center justify-center">
                        <div class="absolute inset-0 bg-blue-500 blur-2xl opacity-20 group-hover:opacity-40 transition-opacity duration-500 rounded-full"></div>
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="relative w-full h-auto drop-shadow-2xl transform group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <h2 class="text-3xl font-black text-white tracking-tight drop-shadow-lg">
                        SYAFA <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-blue-500">GROUP</span>
                    </h2>
                    <p class="text-[10px] text-slate-400 font-bold tracking-[0.3em] uppercase mt-2">Internal Management System</p>
                </a>
            </div>

            {{-- FORM CARD --}}
            <div class="w-full p-8 sm:p-10 login-card rounded-[2rem] relative overflow-hidden animate__animated animate__fadeInUp border-t border-white/10">
                
                {{-- Decorative Top Line --}}
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-1/3 h-1 bg-gradient-to-r from-transparent via-sky-500 to-transparent opacity-70"></div>

                <div class="mb-8 text-center">
                    <h3 class="text-xl font-bold text-white">Selamat Datang</h3>
                    <p class="text-slate-400 text-sm mt-1">Silakan masuk untuk melanjutkan</p>
                </div>

                {{-- FORM START (With AlpineJS for Loading State) --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ loading: false }" @submit="loading = true">
                    @csrf

                    {{-- INPUT NIP / NIB --}}
                    <div class="group">
                        <label class="block text-[10px] font-bold text-sky-200/80 uppercase tracking-wider mb-2 ml-1">NIB / NIP</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400 transition-colors">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            
                            {{-- PERHATIKAN: type="text" dan placeholder sudah benar --}}
                            <input id="email" type="text" name="email" :value="old('email')" required autofocus 
                                class="glass-input w-full pl-11 pr-4 py-3.5 rounded-xl text-sm font-medium focus:ring-0 placeholder:text-slate-600" 
                                placeholder="Masukkan Kode NIB/NIP">
                        </div>
                    </div>

                    {{-- PASSWORD --}}
                    <div x-data="{ show: false }" class="group">
                        <label class="block text-[10px] font-bold text-sky-200/80 uppercase tracking-wider mb-2 ml-1">Kata Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400 transition-colors">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                                class="glass-input w-full pl-11 pr-12 py-3.5 rounded-xl text-sm font-medium focus:ring-0 placeholder:text-slate-600" 
                                placeholder="••••••••">
                            
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-white transition-colors cursor-pointer focus:outline-none">
                                <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    {{-- OPTIONS --}}
                    <div class="flex items-center justify-between pt-1">
                        <label class="inline-flex items-center cursor-pointer group select-none">
                            <input id="remember_me" type="checkbox" class="peer sr-only" name="remember">
                            <div class="w-4 h-4 bg-white/5 border border-slate-500 rounded peer-checked:bg-sky-500 peer-checked:border-sky-500 transition-all"></div>
                            <span class="ml-2.5 text-xs text-slate-400 group-hover:text-slate-200 font-medium transition-colors">Ingat Saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-xs font-bold text-sky-400 hover:text-sky-300 transition-colors" href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    {{-- SUBMIT BUTTON --}}
                    <button type="submit" :disabled="loading" 
                        class="w-full py-4 px-4 bg-gradient-to-r from-sky-600 to-blue-700 hover:from-sky-500 hover:to-blue-600 text-white font-bold rounded-xl shadow-lg shadow-sky-500/20 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-2 mt-6 disabled:opacity-70 disabled:cursor-wait">
                        
                        <span x-show="!loading" class="flex items-center gap-2">
                            MASUK SISTEM <i class="fa-solid fa-arrow-right-long"></i>
                        </span>

                        <span x-show="loading" x-cloak class="flex items-center gap-2">
                            <i class="fas fa-circle-notch fa-spin"></i> Memproses...
                        </span>
                    </button>
                </form>
            </div>

            {{-- FOOTER --}}
            <div class="mt-8 text-center animate__animated animate__fadeInUp animate__delay-1s pb-6">
                <p class="text-slate-500 text-xs font-medium tracking-wide">
                    &copy; {{ date('Y') }} Syafa Group. All Rights Reserved.
                </p>
            </div>
        </div>
    </div>

    {{-- SWEETALERT2 NOTIFICATION LOGIC --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cek jika ada error dari Laravel (Validation Errors)
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal!',
                    html: `
                        <div class="text-left text-sm text-slate-600">
                            <ul class="list-disc pl-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    `,
                    confirmButtonText: 'Coba Lagi',
                    confirmButtonColor: '#3b82f6',
                    background: '#fff',
                    customClass: {
                        popup: 'rounded-2xl'
                    },
                    showClass: {
                        popup: 'animate__animated animate__shakeX' // Efek Getar (Shake)
                    }
                });
            @endif

            // Cek jika ada session error manual (Misal: Akun tidak aktif)
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#ef4444'
                });
            @endif

            // Cek jika session habis
            @if (session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('status') }}",
                    confirmButtonColor: '#3b82f6'
                });
            @endif
        });
    </script>
</x-guest-layout>