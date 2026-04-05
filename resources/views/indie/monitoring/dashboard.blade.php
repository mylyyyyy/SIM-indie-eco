<x-admin-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Dashboard Monitoring</h2>
        <p class="text-slate-500 font-medium">Selamat datang, {{ Auth::user()->name }}. Pantau pergerakan data dari seluruh cabang Indie.</p>
    </div>

    {{-- Widget Utama --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        
        {{-- Widget Tugas Approval --}}
        <a href="{{ route('indie.monitoring.evaluasi.index', ['status' => 'Submit']) }}" class="bg-gradient-to-br from-indigo-600 to-blue-700 p-6 rounded-3xl shadow-lg shadow-blue-500/30 flex items-center justify-between group hover:-translate-y-1 transition-transform cursor-pointer">
            <div>
                <p class="text-xs font-bold text-indigo-200 uppercase tracking-widest mb-1">Menunggu Persetujuan</p>
                <h3 class="text-4xl font-black text-white">{{ $pending_count }} <span class="text-sm text-indigo-200 font-medium">Dokumen</span></h3>
            </div>
            <div class="w-16 h-16 bg-white/10 text-white rounded-2xl flex items-center justify-center text-3xl backdrop-blur-sm border border-white/20 group-hover:scale-110 transition-transform">
                <i class="fas fa-check-double"></i>
            </div>
        </a>

        {{-- Shortcut Semua Laporan --}}
        <a href="{{ route('indie.monitoring.evaluasi.index') }}" class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center justify-between group hover:shadow-md transition-shadow cursor-pointer">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Arsip Terpadu</p>
                <h3 class="text-2xl font-black text-slate-800">Semua Laporan</h3>
                <p class="text-xs text-slate-500 mt-1">Cari, filter, dan unduh data cabang.</p>
            </div>
            <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-slate-100 transition-colors border border-slate-100">
                <i class="fas fa-folder-open"></i>
            </div>
        </a>

    </div>

    {{-- Feed Aktivitas Terbaru --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-800"><i class="fas fa-history mr-2 text-slate-400"></i>Aktivitas Laporan Terbaru</h3>
            <a href="{{ route('indie.monitoring.evaluasi.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
        </div>
        <div class="p-6">
            <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                @forelse($recent_activities as $act)
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-indigo-100 text-indigo-600 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                            @if($act->status == 'Approved') <i class="fas fa-check text-emerald-500"></i>
                            @elseif($act->status == 'Revisi') <i class="fas fa-times text-red-500"></i>
                            @else <i class="fas fa-file-upload text-blue-500"></i> @endif
                        </div>
                        <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-xl border border-slate-100 bg-white shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-slate-800 text-sm">{{ $act->user->name ?? 'Unknown' }}</span>
                                <time class="text-[10px] font-bold text-slate-400">{{ $act->created_at->diffForHumans() }}</time>
                            </div>
                            <p class="text-xs text-slate-500 mb-2"><i class="far fa-building mr-1"></i> {{ $act->user->company_name ?? 'Pusat' }}</p>
                            <div class="bg-slate-50 rounded p-2 text-xs border border-slate-100">
                                <span class="font-bold text-indigo-600 block mb-0.5">{{ $act->modul_laporan }} ({{ $act->kode_referensi }})</span>
                                <span class="text-slate-600 line-clamp-1">{{ $act->keterangan_umum ?? 'Tidak ada catatan.' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-sm text-slate-500 py-4">Belum ada aktivitas laporan terbaru.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>