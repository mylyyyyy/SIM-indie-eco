<x-admin-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-800">Laporan Mingguan Proyek</h2>
        <p class="text-sm text-slate-500">
            @if(Auth::user()->role == 'subkon_pt')
                Evaluasi progres kerja mingguan dan rencana kerja minggu depan.
            @else
                Pantau progres dan rencana kerja mingguan dari Subkon PT.
            @endif
        </p>
    </div>

    @if(session('success'))
        <script>Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });</script>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        @if(Auth::user()->role == 'subkon_pt')
        {{-- KOLOM KIRI: FORM INPUT --}}
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-fit sticky top-24">
            <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Buat Laporan Mingguan</h3>
            
            <form action="{{ route('subkon-pt.weekly-reports.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Proyek</label>
                    <input type="text" name="project_name" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-blue-500" required>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-1">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Minggu Ke</label>
                        <input type="number" min="1" name="minggu_ke" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-blue-500" value="1" required>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-blue-600 uppercase mb-1">Progres Minggu Ini (%)</label>
                        <input type="number" step="0.01" min="0" max="100" name="progress_minggu_ini" class="w-full px-3 py-2 border border-blue-300 rounded-lg text-sm font-bold text-blue-700 focus:ring-blue-500" value="0" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Periode Mulai</label>
                        <input type="date" name="periode_mulai" class="w-full px-2 py-2 border border-slate-200 rounded-lg text-xs" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Periode Selesai</label>
                        <input type="date" name="periode_selesai" class="w-full px-2 py-2 border border-slate-200 rounded-lg text-xs" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-emerald-600 uppercase mb-1">Pekerjaan Yang Diselesaikan</label>
                    <textarea name="pekerjaan_diselesaikan" rows="3" class="w-full px-3 py-2 border border-emerald-200 bg-emerald-50/50 rounded-lg text-sm focus:ring-emerald-500" placeholder="Rincian pekerjaan..." required></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-amber-600 uppercase mb-1">Rencana Minggu Depan</label>
                    <textarea name="rencana_minggu_depan" rows="2" class="w-full px-3 py-2 border border-amber-200 bg-amber-50/50 rounded-lg text-sm focus:ring-amber-500" placeholder="Target kerja minggu depan..." required></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kendala (Opsional)</label>
                    <textarea name="kendala" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" placeholder="Catatan kendala..."></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow mt-2">Simpan Laporan Mingguan</button>
            </form>
        </div>
        @endif

        {{-- KOLOM KANAN: TABEL DATA --}}
        <div class="{{ Auth::user()->role == 'subkon_pt' ? 'lg:col-span-2' : 'lg:col-span-3' }} bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold">
                        <tr>
                            <th class="px-4 py-3">Proyek & Subkon</th>
                            <th class="px-4 py-3 text-center">Periode (Minggu Ke)</th>
                            <th class="px-4 py-3">Detail Evaluasi</th>
                            @if(Auth::user()->role == 'subkon_pt')
                                <th class="px-4 py-3 text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($weeklyReports as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 align-top min-w-[150px]">
                                <div class="font-bold text-slate-800">{{ $item->project_name }}</div>
                                @if(Auth::user()->role != 'subkon_pt')
                                    <div class="text-[10px] text-slate-500 mt-1"><i class="fas fa-user mr-1"></i>{{ $item->user->name ?? '-' }}</div>
                                @endif
                                <div class="mt-3">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Progres Tambahan:</span>
                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded font-black text-xs">+ {{ $item->progress_minggu_ini }}%</span>
                                </div>
                            </td>
                            
                            <td class="px-4 py-3 text-center align-top min-w-[140px]">
                                <div class="font-black text-lg text-indigo-600">M-{{ $item->minggu_ke }}</div>
                                <div class="text-[9px] text-slate-500 mt-1 bg-slate-100 p-1 rounded border border-slate-200">
                                    {{ \Carbon\Carbon::parse($item->periode_mulai)->format('d/m/y') }} - {{ \Carbon\Carbon::parse($item->periode_selesai)->format('d/m/y') }}
                                </div>
                            </td>
                            
                            <td class="px-4 py-3 align-top">
                                <div class="mb-2">
                                    <span class="text-[10px] font-bold text-emerald-600 uppercase block mb-0.5"><i class="fas fa-check-circle mr-1"></i> Selesai Minggu Ini:</span>
                                    <p class="text-xs text-slate-700 whitespace-pre-line">{{ $item->pekerjaan_diselesaikan }}</p>
                                </div>
                                <div class="mb-2">
                                    <span class="text-[10px] font-bold text-amber-600 uppercase block mb-0.5"><i class="fas fa-bullseye mr-1"></i> Rencana Minggu Depan:</span>
                                    <p class="text-xs text-slate-700 whitespace-pre-line">{{ $item->rencana_minggu_depan }}</p>
                                </div>
                                @if($item->kendala)
                                    <div class="text-[10px] text-red-600 bg-red-50 border border-red-100 p-1.5 rounded mt-2">
                                        <span class="font-bold">Kendala:</span> {{ $item->kendala }}
                                    </div>
                                @endif
                            </td>
                            
                            @if(Auth::user()->role == 'subkon_pt')
                            <td class="px-4 py-3 text-center align-top">
                                <form action="{{ route('subkon-pt.weekly-reports.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data mingguan ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded transition-colors"><i class="fas fa-trash text-xs"></i></button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr><td colspan="{{ Auth::user()->role == 'subkon_pt' ? '4' : '3' }}" class="text-center py-8 text-slate-400">Belum ada data laporan mingguan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>