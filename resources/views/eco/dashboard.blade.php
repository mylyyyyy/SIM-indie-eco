<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate__animated animate__fadeInDown">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Operasional Eco</h2>
            <p class="text-slate-500 font-medium">Manajemen Stok Beras, Selep, dan Distribusi Toko.</p>
        </div>
        
        {{-- TOMBOL-TOMBOL HEADER --}}
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('eco.news.index') }}" class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 px-4 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
                <i class="fas fa-pen-nib text-emerald-500"></i> Tulis Berita
            </a>
            <a href="{{ route('eco.portfolios.index') }}" class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 px-4 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
                <i class="fas fa-camera text-blue-500"></i> Update Portofolio
            </a>
            
            {{-- TOMBOL UNDUH LAPORAN (PILIHAN CSV & PDF) --}}
            <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200 shadow-sm ml-2">
                <a href="{{ route('eco.stock.export', 'csv') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-bold text-sm transition-all flex items-center gap-2" title="Unduh format Excel/CSV">
                    <i class="fas fa-file-csv"></i> CSV
                </a>
                <a href="{{ route('eco.reports.export') }}" target="_blank" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-bold text-sm transition-all flex items-center gap-2 ml-1" title="Buka format PDF/Print">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
        </div>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Card 1: Stok Gudang --}}
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-6 rounded-2xl shadow-lg text-white relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-2xl backdrop-blur-sm">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <span class="text-emerald-100 font-bold text-sm uppercase tracking-wide">Total Stok Beras</span>
                </div>
                <h3 class="text-4xl font-black">{{ number_format($stats['total_stock']) }} <span class="text-lg font-medium">Kg</span></h3>
                <p class="text-emerald-100 text-xs mt-2 flex items-center gap-1">
                    <i class="fas fa-arrow-up"></i> +{{ number_format($stats['milling_today']) }} Kg (Masuk Hari Ini)
                </p>
            </div>
        </div>

        {{-- Card 2: Mitra Toko --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase mb-1">Total Toko Mitra</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $stats['active_shops'] }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <i class="fas fa-store"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full bg-slate-100 rounded-full h-2 mb-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: 75%"></div>
                </div>
                <p class="text-xs text-slate-400">Target bulan ini: 60 Toko</p>
            </div>
        </div>

        {{-- Card 3: Kapasitas Selep --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase mb-1">Status Operasional</p>
                    <h3 class="text-xl font-black text-emerald-600">BEROPERASI</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl animate-pulse">
                    <i class="fas fa-cogs"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-xs font-medium text-slate-500">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Sistem Online
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- KOLOM KIRI: INPUT UPDATE (FORM) --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-edit text-emerald-500"></i> Update Stok
                </h3>
                
                <form action="{{ route('eco.stock.update') }}" method="POST">
                    @csrf
                    
                    {{-- Pilihan Cabang (Dynamic dari DB) --}}
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Lokasi / Cabang</label>
                        <div class="relative">
                            <i class="fas fa-map-marker-alt absolute left-3 top-3.5 text-slate-400"></i>
                            <select name="branch_id" class="w-full pl-10 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500 bg-slate-50" required>
                                <option value="" disabled selected>Pilih Lokasi...</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}">
                                        {{ $loc->name }} (Stok: {{ number_format($loc->current_stock) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Jenis Aktivitas --}}
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Aktivitas</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="in" class="peer sr-only" checked>
                                <div class="p-3 text-center border border-slate-200 rounded-xl peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 transition-all hover:bg-slate-50">
                                    <i class="fas fa-arrow-down mb-1 block"></i> Masuk 
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="out" class="peer sr-only">
                                <div class="p-3 text-center border border-slate-200 rounded-xl peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 transition-all hover:bg-slate-50">
                                    <i class="fas fa-arrow-up mb-1 block"></i> Keluar 
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Jumlah --}}
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jumlah (Kg)</label>
                        <input type="number" name="amount" min="1" class="w-full px-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="0" required>
                    </div>

                    {{-- Keterangan --}}
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Catatan</label>
                        <input type="text" name="note" class="w-full px-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="...">
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i> Simpan Data
                    </button>
                </form>
            </div>

           {{-- Riwayat Singkat --}}
            <div class="mt-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                
                {{-- PERUBAHAN DI SINI: Flexbox untuk Judul dan Tombol Download --}}
                <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-2">
                    <h4 class="text-xs font-bold text-slate-400 uppercase">Riwayat Terakhir</h4>
                    
                    
                </div>
                <div class="space-y-4">
                    @forelse($history as $log)
                    <div class="flex items-center justify-between pb-3 border-b border-slate-50 last:border-0 last:pb-0">
                        <div>
                            <p class="text-sm font-bold text-slate-700">{{ $log->description }}</p>
                            <p class="text-[10px] text-slate-400">
                                {{ $log->location->name }} • {{ $log->created_at->format('H:i') }}
                            </p>
                        </div>
                        <span class="text-xs font-bold {{ $log->type == 'in' ? 'text-emerald-600' : 'text-red-500' }}">
                            {{ $log->type == 'in' ? '+' : '-' }} {{ number_format($log->amount) }} Kg
                        </span>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 text-center">Belum ada riwayat transaksi.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: LIST DATA --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Daftar Gudang & Toko</h3>
                    <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-1 rounded font-bold">Real-time Data</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500">
                            <tr>
                                <th class="px-6 py-4">Nama Lokasi</th>
                                <th class="px-6 py-4">Tipe</th>
                                <th class="px-6 py-4">Stok (Kg)</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($locations as $loc)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-700">
                                    {{ $loc->name }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($loc->type == 'mill')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs font-bold"><i class="fas fa-industry"></i> Selep</span>
                                    @elseif($loc->type == 'warehouse')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-slate-100 text-slate-700 text-xs font-bold"><i class="fas fa-warehouse"></i> Gudang</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-blue-50 text-blue-600 text-xs font-bold"><i class="fas fa-store"></i> Toko</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-slate-600">
                                    {{ number_format($loc->current_stock) }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($loc->current_stock > 100)
                                        <span class="text-emerald-500 text-xs font-bold flex items-center gap-1"><i class="fas fa-check-circle"></i> Aman</span>
                                    @else
                                        <span class="text-orange-500 text-xs font-bold flex items-center gap-1 animate-pulse"><i class="fas fa-exclamation-circle"></i> Menipis</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start gap-3">
                <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                <div>
                    <h4 class="text-sm font-bold text-blue-800">Informasi Sistem</h4>
                    <p class="text-xs text-blue-600 mt-1">
                        Data stok diperbarui otomatis. Pilih lokasi "Selep" untuk input hasil panen, dan "Toko" untuk input penjualan/distribusi.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERT SUCCESS/ERROR --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
                });
                Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#ef4444'
                });
            });
        </script>
    @endif
</x-admin-layout>
