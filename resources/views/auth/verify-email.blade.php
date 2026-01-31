<x-guest-layout>
    {{-- Load Animate.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        /* Background Animation (Konsisten dengan Login) */
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.6;
            animation: float 10s infinite ease-in-out;
        }
        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(20px, -20px) scale(1.1); }
            100% { transform: translate(0, 0) scale(1); }
        }

        /* 3D Card */
        .verify-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform-style: preserve-3d;
            transition: transform 0.3s ease;
        }
        .verify-card:hover {
            transform: translateY(-5px);
        }
    </style>

    {{-- BACKGROUND DECORATION --}}
    <div class="fixed inset-0 bg-slate-900 -z-10 overflow-hidden">
        <div class="blob bg-purple-600 w-96 h-96 rounded-full top-0 left-0 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="blob bg-blue-600 w-96 h-96 rounded-full bottom-0 right-0 translate-x-1/2 translate-y-1/2 animation-delay-2000"></div>
    </div>

    {{-- MAIN CARD --}}
    <div class="w-full sm:max-w-md mt-6 px-8 py-10 verify-card rounded-[2.5rem] relative z-10 animate__animated animate__zoomIn">
        
        {{-- Icon Header --}}
        <div class="text-center mb-8">
            <div class="mx-auto w-24 h-24 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center shadow-lg shadow-orange-500/30 mb-6 animate__animated animate__tada animate__delay-1s">
                <i class="fas fa-envelope-open-text text-4xl text-white"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Verifikasi Email Anda</h2>
        </div>

        {{-- Description Text --}}
        <div class="mb-6 text-sm text-slate-600 font-medium text-center leading-relaxed">
            {{ __('Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email Anda.') }}
            <br><br>
            <span class="text-slate-500 text-xs italic">{{ __('Jika Anda tidak menerima email tersebut, kami dengan senang hati akan mengirimkan yang baru.') }}</span>
        </div>

        {{-- Success Message --}}
        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-start gap-3 animate__animated animate__fadeIn">
                <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                <div class="text-sm font-bold text-green-700">
                    {{ __('Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.') }}
                </div>
            </div>
        @endif

        {{-- Actions --}}
        <div class="space-y-4">
            {{-- Resend Button --}}
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full group relative flex justify-center items-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 shadow-lg shadow-blue-500/30 transform transition-all duration-200 hover:-translate-y-1 active:scale-95 focus:outline-none focus:ring-4 focus:ring-blue-200">
                    <span class="mr-2"><i class="fas fa-paper-plane"></i></span>
                    {{ __('Kirim Ulang Email Verifikasi') }}
                </button>
            </form>

            {{-- Logout Button --}}
            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="text-sm text-slate-500 hover:text-slate-800 font-bold underline decoration-2 underline-offset-4 decoration-slate-300 hover:decoration-slate-800 transition-all">
                    {{ __('Keluar (Log Out)') }}
                </button>
            </form>
        </div>
    </div>

    {{-- Footer --}}
    <div class="mt-8 text-center text-slate-500 text-xs relative z-10 animate__animated animate__fadeInUp animate__delay-1s">
        &copy; {{ date('Y') }} Sistem Informasi Lapas Kelas IIB Jombang
    </div>
</x-guest-layout>