<x-admin-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-800">Laporan Progres Proyek</h2>
        <p class="text-sm text-slate-500">
            @if(Auth::user()->role == 'subkon_pt')
                Input dan kelola laporan progres mingguan dan bulanan proyek Anda.
            @else
                Pantau laporan progres mingguan dan bulanan dari Subkon PT.
            @endif
        </p>
    </div>

    @if(session('success'))
        <script>Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });</script>
    @endif
    @if($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 border border-red-100 text-sm"><i class="fas fa-exclamation-triangle mr-2"></i> {{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- ========================================================= --}}
        {{-- KOLOM KIRI: FORM INPUT (HANYA DITAMPILKAN UNTUK SUBKON PT)--}}
        {{-- ========================================================= --}}
        @if(Auth::user()->role == 'subkon_pt')
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-fit sticky top-24">
            <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Buat Laporan Baru</h3>
            
            <form action="{{ route('subkon-pt.reports.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Proyek</label>
                    <input type="text" name="project_name" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-blue-500" placeholder="Contoh: Pembangunan Ruko A..." required>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Jenis Laporan</label>
                        <select name="report_type" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white" required>
                            <option value="mingguan">Mingguan</option>
                            <option value="bulanan">Bulanan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal Buat</label>
                        <input type="date" name="tanggal_laporan" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Keterangan Periode</label>
                    <input type="text" name="periode" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-blue-500" placeholder="Contoh: Minggu ke-2, Januari 2026" required>
                </div>

                <div class="p-3 bg-blue-50 border border-blue-100 rounded-lg">
                    <label class="block text-xs font-bold text-blue-800 uppercase mb-2">Persentase Progres (%)</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Target Plan</label>
                            <input type="number" step="0.01" min="0" max="100" name="progress_target" class="w-full px-3 py-2 border border-slate-300 rounded text-sm" value="0" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Aktual/Realisasi</label>
                            <input type="number" step="0.01" min="0" max="100" name="progress_actual" class="w-full px-3 py-2 border border-blue-300 rounded text-sm font-bold text-blue-700" value="0" required>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Catatan / Kendala</label>
                    <textarea name="keterangan" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" placeholder="Tuliskan kendala di lapangan jika ada..."></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow mt-2">Simpan Laporan</button>
            </form>
        </div>
        @endif

        {{-- ========================================================= --}}
        {{-- KOLOM KANAN: TABEL DATA (LEBAR PENUH JIKA BUKAN SUBKON PT)--}}
        {{-- ========================================================= --}}
        <div class="{{ Auth::user()->role == 'subkon_pt' ? 'lg:col-span-2' : 'lg:col-span-3' }} bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold">
                        <tr>
                            <th class="px-4 py-3">Tanggal & Proyek</th>
                            
                            {{-- Tambahan Kolom Pembuat Laporan Khusus Untuk Manager --}}
                            @if(Auth::user()->role != 'subkon_pt')
                                <th class="px-4 py-3">Subkon PT</th>
                            @endif
                            
                            <th class="px-4 py-3 text-center">Jenis & Periode</th>
                            <th class="px-4 py-3 text-center min-w-[200px]">Progres (%)</th>
                            
                            {{-- Kolom Aksi Hanya Untuk Subkon PT --}}
                            @if(Auth::user()->role == 'subkon_pt')
                                <th class="px-4 py-3 text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($reports as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 align-top">
                                <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d/m/Y') }}</div>
                                <div class="text-xs text-blue-600 font-bold mt-1">{{ $item->project_name }}</div>
                            </td>
                            
                            {{-- Info Subkon Pembuat Laporan (Muncul Untuk Manager) --}}
                            @if(Auth::user()->role != 'subkon_pt')
                            <td class="px-4 py-3 align-top">
                                <div class="font-bold text-slate-700">{{ $item->user->name ?? 'Unknown' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $item->user->company_name ?? '-' }}</div>
                            </td>
                            @endif
                            
                            <td class="px-4 py-3 text-center align-top">
                                <span class="bg-slate-100 px-2 py-1 rounded text-xs font-bold uppercase border border-slate-200">{{ $item->report_type }}</span>
                                <div class="text-[10px] text-slate-500 mt-2 font-medium">{{ $item->periode }}</div>
                            </td>
                            
                            <td class="px-4 py-3 align-top">
                                <div class="flex justify-between text-xs mb-1.5 px-1">
                                    <span class="text-slate-500 font-medium">Plan: {{ $item->progress_target }}%</span>
                                    <span class="font-bold text-blue-600">Act: {{ $item->progress_actual }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    {{-- Mengubah warna progress bar menjadi merah jika aktual lebih kecil dari target --}}
                                    @php
                                        $barColor = ($item->progress_actual >= $item->progress_target) ? 'bg-blue-600' : 'bg-red-500';
                                    @endphp
                                    <div class="{{ $barColor }} h-2 rounded-full" style="width: {{ $item->progress_actual }}%"></div>
                                </div>
                                @if($item->keterangan)
                                    <div class="mt-2 text-[10px] text-slate-500 bg-slate-50 p-1.5 rounded italic">
                                        Catatan: {{ $item->keterangan }}
                                    </div>
                                @endif
                            </td>
                            
                            {{-- Tombol Hapus Hanya Untuk Subkon PT --}}
                            @if(Auth::user()->role == 'subkon_pt')
                            <td class="px-4 py-3 text-center align-top">
                                <form action="{{ route('subkon-pt.reports.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus laporan ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded transition-colors" title="Hapus Laporan">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ Auth::user()->role == 'subkon_pt' ? '4' : '4' }}" class="text-center py-12 text-slate-400">
                                <i class="fas fa-folder-open text-4xl mb-3 opacity-20"></i>
                                <p>Belum ada laporan progres yang tersedia.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>