<x-admin-layout>
    {{-- Load Libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    {{-- HEADER PAGE --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate__animated animate__fadeInDown">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Dashboard Utama</h2>
            <p class="text-slate-500 font-medium">Ringkasan aktivitas Syafa Group per {{ date('d F Y') }}</p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            {{-- Tombol Kelola Cabang --}}
            <a href="{{ route('admin.locations.index') }}" class="bg-white border border-slate-200 text-slate-600 hover:text-blue-600 hover:border-blue-200 px-5 py-2.5 rounded-xl font-bold transition-all flex items-center gap-2 shadow-sm">
                <i class="fas fa-warehouse"></i> Kelola Cabang
            </a>

            {{-- TOMBOL CETAK LAPORAN (Tidak Dihapus) --}}
            <a href="{{ route('admin.reports.export') }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                <i class="fas fa-print"></i> Cetak Laporan
            </a>
        </div>
    </div>

    {{-- ==================== BAGIAN 1: RINGKASAN UTAMA (4 GRID) ==================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        {{-- Card 1: Total Proyek --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-all group">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-layer-group"></i>
            </div>
            <div>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Total Proyek</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $totalProjects ?? 0 }}</h3>
                <span class="text-xs text-emerald-500 font-bold flex items-center gap-1">
                    <i class="fas fa-arrow-up"></i> Aktif
                </span>
            </div>
        </div>

        {{-- Card 2: SDM (Gabungan Manager + Subkon) --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-all group">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Total SDM</p>
                <h3 class="text-2xl font-black text-slate-800">{{ ($subkonPT ?? 0) + ($subkonEks ?? 0) }}</h3>
                <span class="text-xs text-slate-400">
                    {{ $subkonPT ?? 0 }} Internal • {{ $subkonEks ?? 0 }} Mitra
                </span>
            </div>
        </div>

        {{-- Card 3: Data Cabang (Lokasi) --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-all group">
            <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <div>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Total Cabang</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $totalLocations ?? 0 }}</h3>
                <span class="text-xs text-orange-500 font-bold">
                    {{ $activeLocations ?? 0 }} Beroperasi
                </span>
            </div>
        </div>

        {{-- Card 4: Total Stok Beras --}}
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-5 rounded-2xl shadow-lg shadow-emerald-500/20 text-white flex items-center gap-4 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 text-white/10 text-6xl">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-2xl">
                <i class="fas fa-cubes"></i>
            </div>
            <div class="relative z-10">
                <p class="text-white/80 text-[10px] font-bold uppercase tracking-wider">Total Stok Beras</p>
                <h3 class="text-2xl font-black">{{ number_format(($totalStock ?? 0) / 1000, 1, ',', '.') }}</h3>
                <span class="text-xs text-white/90 font-medium">Ton Tersedia</span>
            </div>
        </div>
    </div>

    {{-- ==================== BAGIAN 2: STATISTIK DIVISI & GRAFIK ==================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- KOLOM KIRI: BREAKDOWN DIVISI --}}
        <div class="space-y-6">
            
            {{-- Kartu Syafa Eco --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full transition-transform group-hover:scale-110"></div>
                
                <div class="flex items-center gap-3 mb-4 relative z-10">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800">Syafa Eco</h4>
                        <p class="text-xs text-slate-500">Pangan & Lingkungan</p>
                    </div>
                </div>

                <div class="space-y-3 relative z-10">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Proyek Berjalan</span>
                        <span class="font-bold text-slate-800">{{ $ecoProjects ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $totalProjects > 0 ? ($ecoProjects / $totalProjects) * 100 : 0 }}%"></div>
                    </div>
                    <div class="flex justify-between items-center text-xs text-slate-400 pt-1">
                        <span>Tim: {{ $teamEco ?? 0 }} Staff</span>
                        <span>{{ $totalProjects > 0 ? round(($ecoProjects / $totalProjects) * 100) : 0 }}% Kontribusi</span>
                    </div>
                </div>
            </div>

            {{-- Kartu Syafa Indie --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full transition-transform group-hover:scale-110"></div>
                
                <div class="flex items-center gap-3 mb-4 relative z-10">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-city"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800">Syafa Indie</h4>
                        <p class="text-xs text-slate-500">Infrastruktur & Gedung</p>
                    </div>
                </div>

                <div class="space-y-3 relative z-10">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Proyek Berjalan</span>
                        <span class="font-bold text-slate-800">{{ $indieProjects ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $totalProjects > 0 ? ($indieProjects / $totalProjects) * 100 : 0 }}%"></div>
                    </div>
                    <div class="flex justify-between items-center text-xs text-slate-400 pt-1">
                        <span>Tim: {{ $teamIndie ?? 0 }} Staff</span>
                        <span>{{ $totalProjects > 0 ? round(($indieProjects / $totalProjects) * 100) : 0 }}% Kontribusi</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN: GRAFIK AKTIVITAS (Lebar 2 Kolom) --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Tren Proyek Baru</h3>
                    <p class="text-xs text-slate-400">Statistik input proyek 7 hari terakhir</p>
                </div>
                {{-- Legend Custom --}}
                <div class="flex gap-4 text-xs font-bold">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Eco</div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500"></span> Indie</div>
                </div>
            </div>
            
            {{-- Tempat Render Chart --}}
            <div id="chart"></div>
        </div>

    </div>

    {{-- SCRIPT CHART DINAMIS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Data diambil dari Controller (Real DB)
            const labels = @json($chartLabels);
            const ecoData = @json($chartEco);
            const indieData = @json($chartIndie);

            var options = {
                series: [{
                    name: 'Syafa Eco',
                    data: ecoData
                }, {
                    name: 'Syafa Indie',
                    data: indieData
                }],
                chart: {
                    height: 320,
                    type: 'area',
                    fontFamily: 'Plus Jakarta Sans, sans-serif',
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                colors: ['#10b981', '#3b82f6'], // Hijau & Biru
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] }
                },
                xaxis: {
                    categories: labels,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#94a3b8', fontSize: '12px' } }
                },
                yaxis: {
                    labels: { style: { colors: '#94a3b8' }, formatter: (value) => { return value.toFixed(0) } } // Hilangkan desimal
                },
                grid: { show: true, borderColor: '#f1f5f9', strokeDashArray: 4 },
                tooltip: { 
                    theme: 'light',
                    y: { formatter: function (val) { return val + " Proyek" } }
                },
                legend: { show: false } // Kita pakai legend custom di atas
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });
    </script>
</x-admin-layout>