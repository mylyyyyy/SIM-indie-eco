<x-admin-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Dashboard Kepala Kantor</h2>
        <p class="text-slate-500 font-medium">Selamat datang, {{ Auth::user()->name }}. Divisi Indie - Cabang {{ Auth::user()->company_name ?? 'Pusat' }}.</p>
    </div>

    {{-- Grid 7 Modul Kepala Kantor --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        {{-- 1. Evaluasi Kinerja (Aktif) --}}
        <a href="{{ route('indie.kepala-kantor.lhkp.index') }}" class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center text-center gap-4 hover:shadow-md hover:border-indigo-200 transition-all group">
            <div class="w-16 h-16 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-3xl shadow-inner group-hover:scale-110 transition-transform">
                <i class="fas fa-id-badge"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase mb-1">Evaluasi Kinerja SDM</h3>
                <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md">{{ $lhkp_count }} Dokumen</span>
            </div>
        </a>

        {{-- 2. Lap. Operasional --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center text-center gap-4 opacity-75 cursor-not-allowed">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-3xl shadow-inner">
                <i class="fas fa-building"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase mb-1">Lap. Operasional</h3>
                <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-md">Segera Hadir</span>
            </div>
        </div>

        {{-- 3. Logbook Surat --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center text-center gap-4 opacity-75 cursor-not-allowed">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-3xl shadow-inner">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase mb-1">Logbook Surat</h3>
                <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-md">Segera Hadir</span>
            </div>
        </div>

        {{-- 4. Rekap Anggaran (Opex) --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center text-center gap-4 opacity-75 cursor-not-allowed">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-3xl shadow-inner">
                <i class="fas fa-money-check-alt"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase mb-1">Rekap Anggaran</h3>
                <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-md">Segera Hadir</span>
            </div>
        </div>

        {{-- 5. Laporan Presensi --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center text-center gap-4 opacity-75 cursor-not-allowed">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-3xl shadow-inner">
                <i class="fas fa-user-clock"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase mb-1">Presensi Pegawai</h3>
                <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-md">Segera Hadir</span>
            </div>
        </div>

        {{-- 6. Inventaris & Aset --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center text-center gap-4 opacity-75 cursor-not-allowed">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-3xl shadow-inner">
                <i class="fas fa-boxes"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase mb-1">Inventaris & Aset</h3>
                <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-md">Segera Hadir</span>
            </div>
        </div>

        {{-- 7. Laporan Kepatuhan SOP --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center text-center gap-4 opacity-75 cursor-not-allowed">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-3xl shadow-inner">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase mb-1">Kepatuhan SOP</h3>
                <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-md">Segera Hadir</span>
            </div>
        </div>

    </div>
</x-admin-layout>