<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syafa Group - Company Profile</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/png">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Custom Navy Colors */
        .bg-navy-dark {
            background-color: #0f172a; /* Slate 900 */
        }

        .bg-navy-light {
            background-color: #1e293b; /* Slate 800 */
        }

        /* Gold Color untuk Aksen Mewah */
        .text-gold {
            color: #fbbf24;
        }
        .bg-gold {
            background-color: #fbbf24;
        }
        .hover-text-gold:hover {
            color: #fbbf24;
        }
    </style>
</head>

<body class="bg-gray-50 flex flex-col min-h-screen font-sans">

    <nav class="bg-navy-dark text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            
            <a href="/" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 bg-gold rounded-lg flex items-center justify-center text-navy-dark shadow-md transition-transform transform group-hover:scale-110">
                    <i class="fa-solid fa-layer-group text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-wide group-hover:text-gold transition-colors">SYAFA GROUP</h1>
                    <p class="text-[10px] text-gray-300 uppercase tracking-widest">Solusi Terpercaya</p>
                </div>
            </a>

            <div class="hidden md:flex space-x-8 text-sm font-medium tracking-wide">
                <a href="/" class="hover-text-gold transition duration-300">Beranda</a>
                <a href="#about" class="hover-text-gold transition duration-300">Tentang Kami</a>
                <a href="#services" class="hover-text-gold transition duration-300">Layanan</a>
                <a href="#portfolio" class="hover-text-gold transition duration-300">Portofolio</a>
                <a href="#contact" class="hover-text-gold transition duration-300">Kontak</a>
            </div>

            <div>
                <a href="#contact" class="border border-gold text-gold hover:bg-gold hover:text-navy-dark px-5 py-2 rounded-full text-sm font-semibold transition duration-300">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-navy-dark text-gray-400 py-12 mt-10 border-t border-gray-800">
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-8">
            
            <div class="md:col-span-1">
                <div class="flex items-center space-x-2 mb-4">
                    <i class="fa-solid fa-layer-group text-gold text-2xl"></i>
                    <h3 class="text-white text-xl font-bold">SYAFA GROUP</h3>
                </div>
                <p class="text-sm leading-relaxed">
                    Mitra strategis Anda dalam menciptakan solusi inovatif dan berkelanjutan. Kami berkomitmen memberikan pelayanan terbaik demi kemajuan bisnis Anda.
                </p>
            </div>

            <div>
                <h3 class="text-white text-lg font-bold mb-4 border-b border-gray-700 pb-2 inline-block">Layanan Kami</h3>
                <ul class="text-sm space-y-2">
                    <li><a href="#" class="hover:text-gold transition">Konsultasi Bisnis</a></li>
                    <li><a href="#" class="hover:text-gold transition">Pengembangan IT</a></li>
                    <li><a href="#" class="hover:text-gold transition">Manajemen Proyek</a></li>
                    <li><a href="#" class="hover:text-gold transition">General Supplier</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-white text-lg font-bold mb-4 border-b border-gray-700 pb-2 inline-block">Kantor Pusat</h3>
                <ul class="text-sm space-y-3">
                    <li class="flex items-start space-x-3">
                        <i class="fa-solid fa-location-dot mt-1 text-gold"></i>
                        <span>Jl. Merdeka No. 45, Jakarta Pusat,<br>Indonesia</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fa-solid fa-phone text-gold"></i>
                        <span>(021) 555-0199</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fa-solid fa-envelope text-gold"></i>
                        <span>info@syafagroup.com</span>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-white text-lg font-bold mb-4 border-b border-gray-700 pb-2 inline-block">Ikuti Kami</h3>
                <div class="flex space-x-4 mt-2">
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center hover:bg-gold hover:text-navy-dark transition">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center hover:bg-gold hover:text-navy-dark transition">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center hover:bg-gold hover:text-navy-dark transition">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center text-xs mt-12 border-t border-gray-800 pt-6">
            &copy; 2026 Syafa Group. All rights reserved.
        </div>
    </footer>

</body>
</html>