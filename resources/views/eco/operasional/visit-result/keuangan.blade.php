<x-admin-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-800">Laporan Keuangan: Hasil Kunjungan</h2>
        <p class="text-sm text-slate-500">Rekapitulasi pendapatan dan stok kunjungan toko.</p>
    </div>

    {{-- FILTER & DOWNLOAD SECTION --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-6">
        <form action="{{ route('keuangan_eco.visit-results.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 items-end">
            
            {{-- Input Tanggal Mulai --}}
            <div class="w-full lg:w-auto">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-teal-500 text-sm">
            </div>

            {{-- Input Tanggal Sampai --}}
            <div class="w-full lg:w-auto">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-teal-500 text-sm">
            </div>

            {{-- Tombol Filter (Lihat di Web) --}}
            <button type="submit" class="bg-slate-800 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-700 transition-all flex items-center gap-2">
                <i class="fas fa-filter"></i> Tampilkan
            </button>

            {{-- Tombol Reset --}}
            @if(request('start_date'))
                <a href="{{ route('keuangan_eco.visit-results.index') }}" class="text-red-500 font-bold text-sm hover:underline px-2">Reset</a>
            @endif

            <div class="flex-1"></div>

            {{-- TOMBOL DOWNLOAD EXCEL --}}
            {{-- Kita buat form terpisah (atau link) yang membawa parameter tanggal --}}
            <a href="{{ route('keuangan_eco.visit-results.export', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" 
               target="_blank"
               class="bg-teal-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-teal-700 transition-all shadow-lg shadow-teal-500/30 flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Download Excel
            </a>
        </form>
    </div>

    {{-- TABEL PREVIEW DATA --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Toko</th>
                        <th class="px-4 py-3 text-right">Setoran (Rp)</th>
                        <th class="px-4 py-3 text-center">Laku</th>
                        <th class="px-4 py-3 text-center">Sisa</th>
                        <th class="px-4 py-3 text-center">Total</th>
                        <th class="px-4 py-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($results as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $item->nama_toko }}</td>
                        <td class="px-4 py-3 text-right font-bold text-slate-700">Rp {{ number_format($item->harga_rp, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center text-emerald-600 font-bold">{{ $item->laku_pack }}</td>
                        <td class="px-4 py-3 text-center">{{ $item->sisa_pack }}</td>
                        <td class="px-4 py-3 text-center text-blue-600 font-bold">{{ $item->total_pack }}</td>
                        <td class="px-4 py-3 text-xs">{{ $item->keterangan_bayar }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-8 text-slate-400">Tidak ada data pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>