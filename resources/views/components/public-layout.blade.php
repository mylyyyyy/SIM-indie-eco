<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Syafa Group - Contractor & Supplier</title>
    
    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-600 flex flex-col min-h-screen">

    {{-- NAVBAR --}}
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            {{-- Logo --}}
            <a href="{{ url('img/logo.png') }}" class="flex items-center gap-2 group">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-black text-xl shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">S</div>
                <div class="leading-tight">
                    <h1 class="font-bold text-slate-800 text-lg tracking-tight">SYAFA GROUP</h1>
                    <p class="text-[10px] text-slate-500 font-bold tracking-widest uppercase">Contractor & Supplier</p>
                </div>
            </a>

            {{-- Menu Desktop --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ url('/') }}" class="text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors">Beranda</a>
                <a href="{{ url('/#tentang') }}" class="text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors">Tentang Kami</a>
                <a href="{{ url('/#layanan') }}" class="text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors">Layanan</a>
                <a href="{{ route('portfolio') }}" class="text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors">Portofolio</a>
                <a href="{{ route('components.berita') }}" class="text-sm font-bold text-blue-600">Berita</a>
            </div>

            {{-- Login Button --}}
            <a href="{{ route('login') }}" class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-full text-xs font-bold transition-all shadow-lg hover:shadow-xl">
                Login Sistem
            </a>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main class="flex-grow pt-24">
        {{ $slot }}
    </main>

    {{-- FOOTER --}}
    <footer class="bg-slate-900 text-white pt-16 pb-8 mt-20">
        <div class="container mx-auto px-6 text-center">
            <h3 class="text-2xl font-black mb-4">SYAFA GROUP</h3>
            <p class="text-slate-400 text-sm mb-8 max-w-md mx-auto">Membangun masa depan dengan integritas, kualitas, dan inovasi berkelanjutan.</p>
            <div class="border-t border-slate-800 pt-8 text-xs text-slate-500">
                &copy; {{ date('Y') }} Syafa Group. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>