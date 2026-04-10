<x-admin-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-800">Dashboard Keuangan Eco</h2>
        <p class="text-sm text-slate-500">Akses laporan pemasukan dari seluruh cabang.</p>
    </div>

    {{-- FILTERING --}}
    
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 mb-6">
        <form action="{{ route('keuangan.dashboard') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Filter Cabang</label>
                <select name="cabang" class="px-3 py-2 border border-slate-200 rounded-lg text-sm min-w-[200px]">
                    <option value="">-- Semua Cabang --</option>
                    @foreach($cabangs as $cab)
                        <option value="{{ $cab }}" {{ request('cabang') == $cab ? 'selected' : '' }}>{{ $cab }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-bold">Filter</button>
                <a href="{{ route('keuangan.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-lg text-sm font-bold">Reset</a>
            </div>
        </form>
    </div>

    {{-- TABEL PEMASUKAN --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
        <div class="p-5 border-b border-slate-100 bg-emerald-50 flex justify-between items-center">
            <h3 class="font-bold text-emerald-800"><i class="fas fa-money-bill-wave mr-2"></i>Data Pemasukan (Penjualan Plastik)</h3>
            {{-- TOMBOL DOWNLOAD EXCEL --}}
            <button class="text-[10px] bg-emerald-600 text-white px-3 py-1.5 rounded-lg font-bold">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Tgl & Cabang</th>
                        <th class="px-4 py-3">Toko</th>
                        <th class="px-4 py-3 text-center">Qty (2.5/5)</th>
                        <th class="px-4 py-3 text-right">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $totalSemua = 0; @endphp
                    @forelse($incomes as $index => $item)
                    @php $totalSemua += $item->total_harga_jual; @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-center font-bold">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 align-top">
                            <div class="font-bold">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</div>
                            <div class="text-[10px] text-blue-600 font-bold uppercase">{{ $item->nama_cabang }}</div>
                        </td>
                        <td class="px-4 py-3 font-bold">{{ $item->nama_toko }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs bg-slate-100 px-2 py-1 rounded">{{ $item->jumlah_plastik_2_5kg }}</span> / 
                            <span class="text-xs bg-slate-100 px-2 py-1 rounded">{{ $item->jumlah_plastik_5kg }}</span>
                        </td>
                        <td class="px-4 py-3 text-right font-black text-emerald-600">Rp {{ number_format($item->total_harga_jual, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-8 text-slate-400">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
                @if($incomes->count() > 0)
                <tfoot class="bg-slate-100">
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-right font-black">TOTAL KESELURUHAN:</td>
                        <td class="px-4 py-3 text-right font-black text-emerald-700">Rp {{ number_format($totalSemua, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</x-admin-layout>