<x-admin-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-800">Dashboard Monitoring Eco</h2>
        <p class="text-sm text-slate-500">Pusat pengawasan aktivitas lintas divisi (Read-Only).</p>
    </div>

    {{-- STATISTIK SINGKAT --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-l-blue-500">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Kunjungan Toko (Bulan Ini)</p>
            <h3 class="text-3xl font-black text-blue-700">{{ number_format($stats['total_kunjungan_bulan_ini']) }} <span class="text-sm font-medium text-slate-400">Kali</span></h3>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-l-emerald-500">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Beras Terjual (Bulan Ini)</p>
            <h3 class="text-3xl font-black text-emerald-700">{{ number_format($stats['total_beras_terjual_bulan_ini']) }} <span class="text-sm font-medium text-slate-400">Pack</span></h3>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-l-amber-500">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Laporan Harian (Bulan Ini)</p>
            <h3 class="text-3xl font-black text-amber-700">{{ number_format($stats['total_lh_bulan_ini']) }} <span class="text-sm font-medium text-slate-400">Laporan</span></h3>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        
        {{-- TABEL 1: AKTIVITAS OPERASIONAL ECO (VISIT & SOLD) --}}
        <div class="space-y-8">
            {{-- Kunjungan Toko --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-5 border-b border-slate-100 bg-blue-50 flex justify-between items-center">
                    <h3 class="font-bold text-blue-800"><i class="fas fa-store mr-2"></i>Histori Kunjungan Toko (Eco)</h3>
                    <span class="text-[10px] bg-blue-200 text-blue-800 px-2 py-1 rounded font-bold">10 Terbaru</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-bold">
                            <tr>
                                <th class="px-4 py-3">Waktu & Admin</th>
                                <th class="px-4 py-3">Toko</th>
                                <th class="px-4 py-3 text-center">Laku</th>
                                <th class="px-4 py-3 text-center">Return</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($visits as $visit)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($visit->tanggal)->format('d/m/Y') }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $visit->user->name ?? 'Sistem' }}</div>
                                </td>
                                <td class="px-4 py-3 font-medium">{{ $visit->nama_toko }}</td>
                                <td class="px-4 py-3 text-center font-bold text-emerald-600">{{ $visit->laku_pack }}</td>
                                <td class="px-4 py-3 text-center font-bold text-red-500">{{ $visit->return_pack > 0 ? $visit->return_pack : '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-slate-400">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Beras Terjual --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-5 border-b border-slate-100 bg-emerald-50 flex justify-between items-center">
                    <h3 class="font-bold text-emerald-800"><i class="fas fa-box mr-2"></i>Histori Beras Terjual (Eco)</h3>
                    <span class="text-[10px] bg-emerald-200 text-emerald-800 px-2 py-1 rounded font-bold">10 Terbaru</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-bold">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Toko</th>
                                <th class="px-4 py-3 text-center">Ukuran</th>
                                <th class="px-4 py-3 text-center">Terjual</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($soldRices as $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 font-medium">{{ $item->nama_toko }}</td>
                                <td class="px-4 py-3 text-center"><span class="bg-slate-100 px-2 py-1 rounded text-xs">{{ $item->ukuran }}</span></td>
                                <td class="px-4 py-3 text-center font-black text-emerald-600">{{ $item->jumlah_pack }} Pck</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-slate-400">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TABEL 2: AKTIVITAS KEPALA KANTOR & MANAGER --}}
        <div class="space-y-8">
            {{-- Laporan Harian (Kepala Kantor) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-5 border-b border-slate-100 bg-amber-50 flex justify-between items-center">
                    <h3 class="font-bold text-amber-800"><i class="fas fa-user-tie mr-2"></i>Histori LH (Kepala Kantor)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-bold">
                            <tr>
                                <th class="px-4 py-3">Tgl & Pelapor</th>
                                <th class="px-4 py-3">Rincian</th>
                                <th class="px-4 py-3 text-center">Dokumentasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($lhs as $lh)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 align-top">
                                    <div class="font-bold">{{ \Carbon\Carbon::parse($lh->tanggal)->format('d M Y') }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $lh->user->name ?? 'Unknown' }} ({{ $lh->nama_cabang }})</div>
                                </td>
                                <td class="px-4 py-3 align-top text-xs">
                                    @php $kegiatans = is_array($lh->rincian_kegiatan) ? $lh->rincian_kegiatan : (json_decode($lh->rincian_kegiatan, true) ?? []); @endphp
                                    @if(count($kegiatans) > 0)
                                        {{ Str::limit($kegiatans[0], 40) }}
                                        @if(count($kegiatans) > 1) <span class="text-[9px] text-slate-400 block mt-0.5">+{{ count($kegiatans)-1 }} lainnya</span> @endif
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center align-top">
                                    @if($lh->fotos && $lh->fotos->count() > 0)
                                        <span class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-[10px] font-bold">{{ $lh->fotos->count() }} Foto</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-4 text-slate-400">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- LHKP (Manager Unit) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-5 border-b border-slate-100 bg-indigo-50 flex justify-between items-center">
                    <h3 class="font-bold text-indigo-800"><i class="fas fa-users mr-2"></i>Histori LHKP (Manager Unit)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-bold">
                            <tr>
                                <th class="px-4 py-3">Tgl & Pegawai</th>
                                <th class="px-4 py-3">Divisi</th>
                                <th class="px-4 py-3">Progres</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($lhkps as $lhkp)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 align-top">
                                    <div class="font-bold">{{ \Carbon\Carbon::parse($lhkp->tanggal)->format('d/m/Y') }}</div>
                                    <div class="text-xs text-slate-700 font-medium mt-0.5">{{ $lhkp->nama_pegawai }}</div>
                                </td>
                                <td class="px-4 py-3 align-top text-xs">{{ $lhkp->divisi }}</td>
                                <td class="px-4 py-3 align-top text-xs truncate max-w-[150px]" title="{{ $lhkp->progres_pekerjaan }}">{{ $lhkp->progres_pekerjaan }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-4 text-slate-400">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>