<x-admin-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Laporan Selep</h2>
            <p class="text-sm text-slate-500">Pencatatan pengambilan beras dan jumlah pack hasil selep.</p>
        </div>
        <div>
            <a href="{{ route('eco.milling-reports.export') }}" target="_blank" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl font-bold text-sm shadow-md transition-all flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> Unduh PDF Laporan
            </a>
        </div>
    </div>

    @if(session('success'))
        <script>
            Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false });
        </script>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Form Input (1/3 Lebar) --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Input Laporan Selep</h3>
                
                <form action="{{ route('eco.milling-reports.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Bulan Laporan</label>
                        <select name="bulan" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 bg-white" required>
                            @php
                                $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            @endphp
                            @foreach($months as $month)
                                <option value="{{ $month }}" {{ date('n') == $loop->iteration ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ambil Beras (Kg)</label>
                        <div class="relative">
                            <input type="number" step="0.01" min="0" name="ambil_beras_kg" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" placeholder="0" required>
                            <span class="absolute right-3 top-2 text-slate-400 text-sm font-bold">Kg</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Jumlah (Pack)</label>
                        <div class="relative">
                            <input type="number" min="0" name="jumlah_pack" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" placeholder="0" required>
                            <span class="absolute right-3 top-2 text-slate-400 text-sm font-bold">Pack</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg shadow transition-all mt-2">
                        Simpan Data
                    </button>
                </form>
            </div>
        </div>

        {{-- Tabel Data (2/3 Lebar) --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold">
                            <tr>
                                <th class="px-4 py-3">Tanggal & Bulan</th>
                                <th class="px-4 py-3 text-right">Ambil Beras (Kg)</th>
                                <th class="px-4 py-3 text-right">Jumlah (Pack)</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($reports as $report)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($report->tanggal)->format('d/m/Y') }}</div>
                                    <div class="text-[10px] text-slate-500 uppercase">{{ $report->bulan }}</div>
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-slate-700">
                                    {{ number_format($report->ambil_beras_kg, 2, ',', '.') }} Kg
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded font-bold text-xs">
                                        {{ number_format($report->jumlah_pack, 0, ',', '.') }} Pack
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('eco.milling-reports.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Hapus laporan selep ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-slate-400">Belum ada data laporan selep.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>