<x-admin-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-800">Dashboard Manager Unit</h2>
        <p class="text-sm text-slate-500">Pusat kontrol dan unduhan laporan operasional.</p>
    </div>

    {{-- TABEL 1: LAPORAN HARIAN (LH) KEPALA KANTOR --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-amber-50">
            <h3 class="font-bold text-amber-800 flex items-center gap-2">
                <i class="fas fa-user-tie"></i> Laporan Harian (LH) - Kepala Kantor
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold">
                    <tr>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Ringkasan Kegiatan</th>
                        <th class="px-6 py-3">Dokumentasi</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lhs as $lh)
                    <tr class="hover:bg-amber-50/30">
                        <td class="px-6 py-3 font-medium">{{ \Carbon\Carbon::parse($lh->tanggal)->format('d F Y') }}</td>
                        <td class="px-6 py-3">
    @php 
        // Cek apakah masih string JSON, jika iya decode, jika sudah array langsung gunakan
        $keg = is_string($lh->rincian_kegiatan) ? json_decode($lh->rincian_kegiatan, true) : $lh->rincian_kegiatan;
        
        // Pastikan $keg selalu array untuk menghindari error count()
        if (!is_array($keg)) $keg = [];
    @endphp
    <ul class="list-disc list-inside text-xs">
        <li>{{ Str::limit($keg[0] ?? '-', 50) }}</li>
        @if(count($keg) > 1) <li class="text-slate-400">+ {{ count($keg)-1 }} lainnya</li> @endif
    </ul>
</td>
                        <td class="px-6 py-3">
                            @if($lh->dokumentasi)
                                <a href="{{ asset('uploads/lh/'.$lh->dokumentasi) }}" target="_blank" class="text-blue-600 underline text-xs">Lihat Foto</a>
                            @else
                                <span class="text-slate-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-center">
                            <a href="{{ route('manager_unit.lh.pdf', $lh->id) }}" class="inline-flex items-center gap-1 bg-red-100 text-red-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-red-200 transition-colors">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4 text-slate-400">Belum ada data LH.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TABEL 2: LHKP PEGAWAI --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-emerald-50">
            <h3 class="font-bold text-emerald-800 flex items-center gap-2">
                <i class="fas fa-users"></i> Laporan Kinerja Pegawai (LHKP)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold">
                    <tr>
                        <th class="px-6 py-3">Tanggal & Tempat</th>
                        <th class="px-6 py-3">Pegawai</th>
                        <th class="px-6 py-3">Progres Pekerjaan</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lhkps as $lhkp)
                    <tr class="hover:bg-emerald-50/30">
                        <td class="px-6 py-3">
                            <div class="font-bold">{{ \Carbon\Carbon::parse($lhkp->tanggal)->format('d/m/Y') }}</div>
                            <div class="text-xs text-slate-500">{{ $lhkp->tempat }}</div>
                        </td>
                        <td class="px-6 py-3">
                            <div class="font-bold text-slate-700">{{ $lhkp->nama_pegawai }}</div>
                            <div class="text-xs text-slate-400">{{ $lhkp->divisi }}</div>
                        </td>
                        <td class="px-6 py-3 text-xs leading-relaxed max-w-xs truncate">
                            {{ $lhkp->progres_pekerjaan }}
                        </td>
                        <td class="px-6 py-3 text-center">
                            <a href="{{ route('manager_unit.lhkp.pdf', $lhkp->id) }}" class="inline-flex items-center gap-1 bg-red-100 text-red-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-red-200 transition-colors">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4 text-slate-400">Belum ada data LHKP.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>