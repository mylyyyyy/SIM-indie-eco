<x-admin-layout>
    {{-- HEADER PAGE --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Unduh Laporan Harian (LH)</h2>
            <p class="text-slate-500 font-medium">Arsip laporan kegiatan pelaksana lapangan dari cabang yang sama.</p>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Tanggal Laporan</th>
                        <th class="px-6 py-4">Pelapor (User)</th>
                        <th class="px-6 py-4">Rincian Kegiatan</th>
                        <th class="px-6 py-4 text-center">Aksi / Unduh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lhs as $item)
                        @php
                            $kegiatanList = is_string($item->rincian_kegiatan) ? json_decode($item->rincian_kegiatan, true) : $item->rincian_kegiatan;
                        @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800"><i class="far fa-calendar-alt text-sky-500 mr-2"></i>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-blue-600">
                            {{ $item->user->name ?? 'User Terhapus' }}
                        </td>
                        <td class="px-6 py-4">
                            <ul class="list-disc pl-4 space-y-1 text-slate-600">
                                @if(is_array($kegiatanList))
                                    @foreach(array_slice($kegiatanList, 0, 2) as $kegiatan)
                                        <li class="line-clamp-1">{{ $kegiatan }}</li>
                                    @endforeach
                                    @if(count($kegiatanList) > 2)
                                        <li class="text-xs text-sky-500 font-bold italic list-none mt-1">...</li>
                                    @endif
                                @endif
                            </ul>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('subkon-pt.lh.download', $item->id) }}" class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-4 py-2 rounded-lg text-xs font-bold transition-all border border-blue-100 hover:border-blue-600">
                                <i class="fas fa-download"></i> Unduh PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400 font-medium"><i class="fas fa-file-alt text-4xl mb-3 block"></i> Belum ada laporan harian yang diunggah.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>