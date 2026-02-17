<x-admin-layout>
    {{-- Load Libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    {{-- HEADER PAGE --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate__animated animate__fadeInDown">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Syafa Indie Dashboard</h2>
            <p class="text-slate-500 font-medium">Divisi Infrastruktur, Gedung & Komersial.</p>
        </div>
        
        {{-- TOMBOL-TOMBOL HEADER --}}
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('indie.portfolios.index') }}" class="bg-white border border-slate-200 text-slate-600 hover:text-indigo-600 hover:border-indigo-200 px-5 py-2.5 rounded-xl font-bold transition-all flex items-center gap-2 shadow-sm">
                <i class="fas fa-images"></i> Portofolio
            </a>
            <a href="{{ route('indie.news.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                <i class="fas fa-newspaper"></i> Tulis Berita
            </a>
            {{-- TOMBOL BARU: CETAK LAPORAN PROYEK --}}
            <a href="{{ route('indie.projects.export') }}" target="_blank" class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-red-500/30 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                <i class="fas fa-print"></i> Cetak Laporan
            </a>
        </div>
        
    </div>

    {{-- ==================== STATISTIK UTAMA (4 CARDS) ==================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        {{-- Card 1: Total Proyek --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-all group border-l-4 border-l-indigo-500">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                <i class="fas fa-city"></i>
            </div>
            <div>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Total Proyek</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $totalProjects }}</h3>
            </div>
        </div>

        {{-- Card 2: Proyek Aktif --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-all group">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                <i class="fas fa-hard-hat"></i>
            </div>
            <div>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Sedang Berjalan</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $activeProjects }}</h3>
            </div>
        </div>

        {{-- Card 3: Portofolio --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-all group">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                <i class="fas fa-images"></i>
            </div>
            <div>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Item Portofolio</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $totalPortfolios }}</h3>
            </div>
        </div>

        {{-- Card 4: Selesai --}}
        <div class="bg-gradient-to-br from-indigo-600 to-blue-700 p-6 rounded-2xl shadow-lg shadow-indigo-500/20 text-white flex items-center gap-4 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 text-white/10 text-6xl">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="relative z-10">
                <p class="text-white/80 text-[10px] font-bold uppercase tracking-wider">Proyek Selesai</p>
                <h3 class="text-3xl font-black">{{ $completedProjects }}</h3>
                <span class="text-xs text-white/90 font-medium">Unit Terdeliveri</span>
            </div>
        </div>
    </div>

    {{-- ==================== CHART & RECENT PROJECTS ==================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- KOLOM KIRI: CHART (2/3 Lebar) --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Pertumbuhan Proyek Indie</h3>
                    <p class="text-xs text-slate-400">Statistik 6 bulan terakhir</p>
                </div>
                <span class="bg-indigo-50 text-indigo-600 text-xs px-3 py-1 rounded-full font-bold border border-indigo-100">
                    <i class="fas fa-chart-line mr-1"></i> Tren
                </span>
            </div>
            <div id="chartIndie"></div>
        </div>

        {{-- KOLOM KANAN: PROJECT LIST (1/3 Lebar) --}}
        <div class="lg:col-span-1 bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col">
            <h3 class="font-bold text-slate-800 text-lg mb-4">Proyek Terbaru</h3>
            
            <div class="space-y-4 flex-1">
                @forelse($recentProjects as $project)
                    <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 font-bold text-sm">
                            {{ substr($project->project_name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-700 text-sm truncate" title="{{ $project->project_name }}">
                                {{ $project->project_name }}
                            </h4>
                            <p class="text-xs text-slate-400 truncate">
                                {{ $project->location ?? 'Lokasi tidak ada' }}
                            </p>
                        </div>
                        <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-1 rounded-full font-bold">
                            {{ $project->created_at->format('d M') }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-sm">
                        Belum ada data proyek.
                    </div>
                @endforelse
            </div>

            {{-- FUNGSI LIHAT SEMUA PROYEK SUDAH DIHAPUS DARI SINI --}}
        </div>

    </div>

    {{-- SCRIPT CHART --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const labels = @json($chartLabels);
            const data = @json($chartData);

            var options = {
                series: [{
                    name: 'Proyek Baru',
                    data: data
                }],
                chart: {
                    height: 300,
                    type: 'bar',
                    fontFamily: 'Plus Jakarta Sans, sans-serif',
                    toolbar: { show: false }
                },
                colors: ['#4f46e5'], // Warna Indigo
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        columnWidth: '40%',
                    }
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: labels,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#94a3b8', fontSize: '12px' } }
                },
                yaxis: {
                    labels: { style: { colors: '#94a3b8' }, formatter: (val) => val.toFixed(0) }
                },
                grid: { show: true, borderColor: '#f1f5f9', strokeDashArray: 4 },
                tooltip: { 
                    theme: 'light',
                    y: { formatter: function (val) { return val + " Proyek" } }
                }
            };

            var chart = new ApexCharts(document.querySelector("#chartIndie"), options);
            chart.render();
        });
    </script>
</x-admin-layout>