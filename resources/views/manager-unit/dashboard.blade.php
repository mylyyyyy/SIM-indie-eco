<x-admin-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-800">Dashboard Manager Unit</h2>
        <p class="text-sm text-slate-500">Pusat kontrol dan unduhan laporan operasional cabang {{ Auth::user()->company_name ?? 'Pusat' }}.</p>
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
                        <th class="px-6 py-3 min-w-[200px]">Ringkasan Kegiatan</th>
                        <th class="px-6 py-3 min-w-[150px]">Dokumentasi</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lhs as $lh)
                    <tr class="hover:bg-amber-50/30">
                        <td class="px-6 py-4 font-medium align-top">{{ \Carbon\Carbon::parse($lh->tanggal)->format('d F Y') }}</td>
                        <td class="px-6 py-4 align-top">
                            @php 
                                // Cek dan decode JSON kegiatan dengan aman
                                $kegiatans = is_array($lh->rincian_kegiatan) 
                                             ? $lh->rincian_kegiatan 
                                             : (json_decode($lh->rincian_kegiatan, true) ?? []); 
                            @endphp
                            
                            @if(is_array($kegiatans) && count($kegiatans) > 0)
                                <ul class="list-disc list-inside text-xs space-y-1">
                                    <li>{{ Str::limit($kegiatans[0], 50) }}</li>
                                    @if(count($kegiatans) > 1) 
                                        <li class="text-slate-400 list-none text-[10px] mt-1 font-bold">+ {{ count($kegiatans)-1 }} rincian lainnya</li> 
                                    @endif
                                </ul>
                            @else
                                <span class="text-xs text-slate-400 italic">Tidak ada rincian.</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 align-top">
                            {{-- LOGIKA FOTO MULTIPLE BASE64 --}}
                            @if($lh->fotos && $lh->fotos->count() > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach($lh->fotos as $foto)
                                        {{-- $foto->path_foto berisi string data:image/png;base64,... --}}
                                        <a href="{{ $foto->path_foto }}" target="_blank" class="block relative group">
                                            <img src="{{ $foto->path_foto }}" class="w-10 h-10 object-cover rounded border border-slate-200 shadow-sm group-hover:scale-110 transition-transform" alt="Dokumentasi">
                                        </a>
                                    @endforeach
                                </div>
                                <div class="text-[10px] text-slate-400 mt-1.5 font-medium">{{ $lh->fotos->count() }} Foto terlampir</div>
                            @else
                                <span class="text-xs text-slate-400 italic bg-slate-50 px-2 py-1 rounded border border-slate-100">Tanpa Foto</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center align-top">
                            <a href="{{ route('manager_unit.lh.pdf', $lh->id) }}" class="inline-flex items-center gap-1 bg-red-100 text-red-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-red-200 transition-colors shadow-sm">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-slate-400">
                            <i class="fas fa-folder-open block mb-2 text-3xl text-slate-300"></i>Belum ada data Laporan Harian.
                        </td>
                    </tr>
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
                        <td class="px-6 py-4 align-top">
                            <div class="font-bold">{{ \Carbon\Carbon::parse($lhkp->tanggal)->format('d/m/Y') }}</div>
                            <div class="text-xs text-slate-500 mt-1">{{ $lhkp->tempat }}</div>
                        </td>
                        <td class="px-6 py-4 align-top">
                            <div class="font-bold text-slate-700">{{ $lhkp->nama_pegawai }}</div>
                            <div class="text-[10px] bg-slate-100 border border-slate-200 px-2 py-0.5 rounded inline-block mt-1 text-slate-500">{{ $lhkp->divisi }}</div>
                        </td>
                        <td class="px-6 py-4 text-xs leading-relaxed max-w-xs truncate align-top">
                            {{ $lhkp->progres_pekerjaan }}
                        </td>
                        <td class="px-6 py-4 text-center align-top">
                            <a href="{{ route('manager_unit.lhkp.pdf', $lhkp->id) }}" class="inline-flex items-center gap-1 bg-red-100 text-red-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-red-200 transition-colors shadow-sm">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-slate-400">
                            <i class="fas fa-users-slash block mb-2 text-3xl text-slate-300"></i>Belum ada data LHKP.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>