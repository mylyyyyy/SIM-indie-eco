<x-admin-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-800">Data Hasil Kunjungan Toko</h2>
        <p class="text-sm text-slate-500">Filter dan unduh data kunjungan untuk verifikasi keuangan (Format Excel).</p>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6 flex flex-col md:flex-row gap-4 items-end">
        <form action="{{ route('keuangan_eco.visit-results.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 flex-1 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Mulai Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-teal-500">
            </div>
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow transition-all">
                <i class="fas fa-filter mr-1"></i> Filter Data
            </button>
            @if(request('start_date'))
                <a href="{{ route('keuangan_eco.visit-results.index') }}" class="text-slate-500 hover:text-red-500 text-sm font-bold px-2">Reset</a>
            @endif
        </form>

        {{-- TOMBOL EXPORT EXCEL --}}
        <form action="{{ route('keuangan_eco.visit-results.export') }}" method="GET">
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-teal-500/30 transition-all flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Unduh Excel
            </button>
        </form>
    </div>

    {{-- Tabel Preview --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Nama Toko</th>
                        <th class="px-4 py-3 text-center">Harga</th>
                        <th class="px-4 py-3 text-center">Laku</th>
                        <th class="px-4 py-3 text-center">Sisa</th>
                        <th class="px-4 py-3 text-center">Total</th>
                        <th class="px-4 py-3">Pembayaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($results as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $item->nama_toko }}</td>
                        <td class="px-4 py-3 text-center">Rp {{ number_format($item->harga_rp, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center font-bold text-emerald-600">{{ $item->laku_pack }}</td>
                        <td class="px-4 py-3 text-center text-orange-500">{{ $item->sisa_pack }}</td>
                        <td class="px-4 py-3 text-center font-bold text-blue-600">{{ $item->total_pack }}</td>
                        <td class="px-4 py-3 text-xs">{{ $item->keterangan_bayar ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-8 text-slate-400">Belum ada data untuk periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>