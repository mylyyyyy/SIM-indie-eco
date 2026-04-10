<x-admin-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Dashboard Kepala Kantor</h2>
        <p class="text-slate-500 font-medium">Selamat datang, {{ Auth::user()->name }}. Divisi Indie - Cabang {{ Auth::user()->company_name ?? 'Pusat' }}.</p>
    </div>

    {{-- Grid 7 Modul Kepala Kantor --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        {{-- 1. Evaluasi Kinerja (Sudah Ada Rutenya) --}}
        <a href="{{ route('indie.kepala-kantor.lhkp.index') }}" class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center text-center gap-4 hover:shadow-md hover:border-indigo-200 transition-all group">
            <div class="w-16 h-16 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-3xl shadow-inner group-hover:scale-110 transition-transform">
                <i class="fas fa-id-badge"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase mb-1">Evaluasi Kinerja SDM</h3>
                <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md">{{ $lhkp_count ?? 0 }} Dokumen</span>
            </div>
        </a>

        {{-- 2. Lap. Operasional --}}
        <a href="{{ route('indie.kepala-kantor.lap-operasional.index') }}" class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center text-center gap-4 hover:shadow-md hover:border-sky-200 transition-all group">
            <div class="w-16 h-16 rounded-2xl bg-sky-100 text-sky-600 flex items-center justify-center text-3xl shadow-inner group-hover:scale-110 transition-transform">
                <i class="fas fa-building"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase mb-1">Lap. Operasional</h3>
                <span class="text-[10px] font-bold text-sky-600 bg-sky-50 px-2 py-1 rounded-md transition-colors">Monitoring Harian</span>
            </div>
        </a>

        {{-- 3. Logbook Surat --}}
        <a href="{{ route('indie.kepala-kantor.logbook-surat.index') }}" class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center text-center gap-4 hover:shadow-md hover:border-emerald-200 transition-all group">
            <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl shadow-inner group-hover:scale-110 transition-transform">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase mb-1">Logbook Surat</h3>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md transition-colors">Arsip Dokumen</span>
            </div>
        </a>

        {{-- 4. Rekap Anggaran (Opex) --}}
        <a href="{{ route('indie.kepala-kantor.opex.index') }}" class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center text-center gap-4 hover:shadow-md hover:border-amber-200 transition-all group">
            <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-3xl shadow-inner group-hover:scale-110 transition-transform">
                <i class="fas fa-money-check-alt"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase mb-1">Rekap Anggaran</h3>
                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-md transition-colors">Manajemen Opex</span>
            </div>
        </a>

        {{-- 5. Laporan Presensi --}}
        <a href="{{ route('indie.kepala-kantor.lap-presensi.index') }}" class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center text-center gap-4 hover:shadow-md hover:border-rose-200 transition-all group">
            <div class="w-16 h-16 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-3xl shadow-inner group-hover:scale-110 transition-transform">
                <i class="fas fa-user-clock"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase mb-1">Presensi Pegawai</h3>
                <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded-md transition-colors">Rekap Kehadiran</span>
            </div>
        </a>

        {{-- 6. Inventaris & Aset --}}
        <a href="{{ route('indie.kepala-kantor.inventaris.index') }}" class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center text-center gap-4 hover:shadow-md hover:border-teal-200 transition-all group">
            <div class="w-16 h-16 rounded-2xl bg-teal-100 text-teal-600 flex items-center justify-center text-3xl shadow-inner group-hover:scale-110 transition-transform">
                <i class="fas fa-boxes"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase mb-1">Inventaris & Aset</h3>
                <span class="text-[10px] font-bold text-teal-600 bg-teal-50 px-2 py-1 rounded-md transition-colors">Kelola Barang</span>
            </div>
        </a>

        {{-- 7. Laporan Kepatuhan SOP --}}
        <a href="{{ route('indie.kepala-kantor.kepatuhan-sop.index') }}" class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col items-center text-center gap-4 hover:shadow-md hover:border-violet-200 transition-all group">
            <div class="w-16 h-16 rounded-2xl bg-violet-100 text-violet-600 flex items-center justify-center text-3xl shadow-inner group-hover:scale-110 transition-transform">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase mb-1">Kepatuhan SOP</h3>
                <span class="text-[10px] font-bold text-violet-600 bg-violet-50 px-2 py-1 rounded-md transition-colors">Audit & Evaluasi</span>
            </div>
        </a>

    </div>
</x-admin-layout>