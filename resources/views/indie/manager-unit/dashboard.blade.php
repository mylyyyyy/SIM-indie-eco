<x-admin-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Dashboard Manager Unit</h2>
        <p class="text-slate-500 font-medium">Selamat datang, {{ Auth::user()->name }}. Divisi Indie - Cabang {{ Auth::user()->company_name ?? 'Pusat' }}.</p>
    </div>

    {{-- Statistik Card --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            <div class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl shadow-inner z-10">
                <i class="fas fa-users-cog"></i>
            </div>
            <div class="z-10">
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">Total LHKP</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $lhkp_count }} <span class="text-sm text-slate-500 font-medium">Dokumen</span></h3>
            </div>
        </div>

        {{-- Modul Placeholder (Akan aktif ketika modul LH/Mingguan dibuat) --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 relative overflow-hidden group opacity-75">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl shadow-inner z-10">
                <i class="fas fa-file-signature"></i>
            </div>
            <div class="z-10">
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">Lap. Harian</p>
                <h3 class="text-xl font-bold text-slate-600">Segera Hadir</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 relative overflow-hidden group opacity-75">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl shadow-inner z-10">
                <i class="fas fa-calendar-week"></i>
            </div>
            <div class="z-10">
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">Lap. Mingguan</p>
                <h3 class="text-xl font-bold text-slate-600">Segera Hadir</h3>
            </div>
        </div>
    </div>

    {{-- Tabel LHKP Terbaru --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 text-lg">Evaluasi Pegawai (LHKP) Terbaru</h3>
            <a href="{{ route('indie.manager-unit.lhkp.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-700">Lihat Semua <i class="fas fa-arrow-right ml-1"></i></a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Pegawai</th>
                        <th class="px-6 py-4">Progres Pekerjaan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recent_lhkps as $lhkp)
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        <td class="px-6 py-4 font-semibold text-slate-700">{{ \Carbon\Carbon::parse($lhkp->tanggal)->translatedFormat('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-indigo-600 block">{{ $lhkp->nama_pegawai }}</span>
                            <span class="text-xs text-slate-400">{{ $lhkp->divisi }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs line-clamp-2">{{ $lhkp->progres_pekerjaan }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-8 text-center text-slate-400">Belum ada evaluasi pegawai bulan ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>