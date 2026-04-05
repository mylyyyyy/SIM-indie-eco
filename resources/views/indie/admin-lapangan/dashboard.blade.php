<x-admin-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Dashboard Admin Lapangan</h2>
        <p class="text-slate-500 font-medium">Selamat datang, {{ Auth::user()->name }}. Proyek: {{ Auth::user()->company_name ?? 'Pusat' }}.</p>
    </div>

    {{-- Statistik Card --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        {{-- Keuangan Lapangan --}}
        <a href="{{ route('indie.admin-lapangan.keuangan.index') }}" class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 relative overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-sky-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            <div class="w-14 h-14 rounded-2xl bg-sky-100 text-sky-600 flex items-center justify-center text-2xl shadow-inner z-10">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="z-10">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Keuangan Lapangan</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $total_keuangan }} <span class="text-xs text-slate-500 font-medium">Laporan</span></h3>
            </div>
        </a>

        {{-- Survey Bahan --}}
        <a href="{{ route('indie.admin-lapangan.survey-bahan.index') }}" class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 relative overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-sky-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            <div class="w-14 h-14 rounded-2xl bg-sky-100 text-sky-600 flex items-center justify-center text-2xl shadow-inner z-10">
                <i class="fas fa-truck-loading"></i>
            </div>
            <div class="z-10">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Survey Material</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $total_survey }} <span class="text-xs text-slate-500 font-medium">Data</span></h3>
            </div>
        </a>

        {{-- Progres Fisik (Coming Soon) --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 relative overflow-hidden group opacity-60">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl shadow-inner z-10">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="z-10">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Progres Fisik</p>
                <h3 class="text-lg font-bold text-slate-600">Segera Hadir</h3>
            </div>
        </div>

        {{-- Log Absensi (Coming Soon) --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 relative overflow-hidden group opacity-60">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl shadow-inner z-10">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="z-10">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Log Absensi</p>
                <h3 class="text-lg font-bold text-slate-600">Segera Hadir</h3>
            </div>
        </div>
    </div>
</x-admin-layout>