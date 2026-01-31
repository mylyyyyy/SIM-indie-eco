<x-admin-layout>
    {{-- Load Library --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate__animated animate__fadeInDown">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Dashboard Overview</h2>
            <p class="text-slate-500 font-medium">Ringkasan aktivitas proyek hari ini, {{ date('d M Y') }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.reports.export') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                <i class="fas fa-print"></i> Cetak Laporan
            </a>
        </div>
    </div>

    {{-- ==================== STATISTIK UTAMA (SYAFA GROUP) ==================== --}}
    <div class="mb-8">
        <h3 class="text-lg font-bold text-slate-700 mb-4 flex items-center gap-2">
            <i class="fas fa-building text-blue-600"></i> Statistik Keseluruhan (Syafa Group)
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Manager Internal --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div>
                    <p class="text-slate-500 text-xs font-bold uppercase">Manager Internal</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $subkonPT ?? 0 }}</h3>
                    <span class="text-xs text-slate-400">Aktif Bekerja</span>
                </div>
            </div>

            {{-- Subkon Eksternal --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl">
                    <i class="fas fa-helmet-safety"></i>
                </div>
                <div>
                    <p class="text-slate-500 text-xs font-bold uppercase">Subkon Eksternal</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $subkonEks ?? 0 }}</h3>
                    <span class="text-xs text-slate-400">Mitra Terdaftar</span>
                </div>
            </div>

            {{-- Total Proyek --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-slate-800 text-white flex items-center justify-center text-2xl">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <p class="text-slate-500 text-xs font-bold uppercase">Total Proyek</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $totalProjects ?? 0 }}</h3>
                    <span class="text-xs text-slate-400">Semua Divisi</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== STATISTIK PER DIVISI (ECO & INDIE) ==================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        
        {{-- KARTU SYAFA ECO --}}
        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 p-6 rounded-3xl border border-emerald-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-200/20 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-xl shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-leaf"></i>
                </div>
                <div>
                    <h4 class="font-black text-emerald-800 text-xl">Syafa Eco</h4>
                    <p class="text-xs text-emerald-600 font-medium">Divisi Hunian & Lingkungan</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white/60 p-4 rounded-2xl backdrop-blur-sm border border-emerald-100">
                    <span class="text-xs font-bold text-emerald-600 uppercase block mb-1">Proyek Aktif</span>
                    <span class="text-3xl font-black text-slate-800">{{ $ecoProjects ?? 0 }}</span>
                </div>
                <div class="bg-white/60 p-4 rounded-2xl backdrop-blur-sm border border-emerald-100">
                    <span class="text-xs font-bold text-emerald-600 uppercase block mb-1">Tim Inti</span>
                    <span class="text-3xl font-black text-slate-800">{{ $teamEco ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- KARTU SYAFA INDIE --}}
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-3xl border border-blue-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-200/20 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xl shadow-lg shadow-blue-500/30">
                    <i class="fas fa-city"></i>
                </div>
                <div>
                    <h4 class="font-black text-blue-800 text-xl">Syafa Indie</h4>
                    <p class="text-xs text-blue-600 font-medium">Divisi Infrastruktur & Komersial</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white/60 p-4 rounded-2xl backdrop-blur-sm border border-blue-100">
                    <span class="text-xs font-bold text-blue-600 uppercase block mb-1">Proyek Aktif</span>
                    <span class="text-3xl font-black text-slate-800">{{ $indieProjects ?? 0 }}</span>
                </div>
                <div class="bg-white/60 p-4 rounded-2xl backdrop-blur-sm border border-blue-100">
                    <span class="text-xs font-bold text-blue-600 uppercase block mb-1">Tim Inti</span>
                    <span class="text-3xl font-black text-slate-800">{{ $teamIndie ?? 0 }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- CHART SECTION --}}
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-slate-800 text-lg">Tren Aktivitas Proyek</h3>
        </div>
        <div id="chart"></div>
    </div>

    {{-- SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ApexCharts
            var options = {
                series: [{
                    name: 'Syafa Eco',
                    data: [2, 3, 1, 4, 2, 5, 3] // Data Dummy Eco
                }, {
                    name: 'Syafa Indie',
                    data: [1, 2, 4, 2, 5, 3, 6] // Data Dummy Indie
                }],
                chart: {
                    height: 300,
                    type: 'area',
                    fontFamily: 'Plus Jakarta Sans, sans-serif',
                    toolbar: { show: false }
                },
                colors: ['#10b981', '#3b82f6'], // Hijau (Eco), Biru (Indie)
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] }
                },
                xaxis: {
                    categories: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                grid: { show: true, borderColor: '#f1f5f9', strokeDashArray: 4 },
                legend: { position: 'top' }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });
    </script>
</x-admin-layout>