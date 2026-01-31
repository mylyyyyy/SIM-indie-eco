<x-admin-layout>
    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate__animated animate__fadeInDown">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Dashboard Subkon</h2>
            <p class="text-slate-500 font-medium">Selamat datang, {{ Auth::user()->name }}. Pantau kinerja Anda di sini.</p>
        </div>
        
        <a href="{{ route('subkon-eks.reports.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
            <i class="fas fa-plus-circle"></i> Buat Laporan Baru
        </a>
    </div>

    {{-- SUCCESS ALERT --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success', title: 'Berhasil', text: "{{ session('success') }}",
                    timer: 2000, showConfirmButton: false,
                    toast: true, position: 'top-end'
                });
            });
        </script>
    @endif

    {{-- STATS GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl"><i class="fas fa-file-alt"></i></div>
            <div><p class="text-slate-400 text-xs font-bold uppercase">Total</p><h3 class="text-2xl font-black text-slate-800">{{ $stats['total'] }}</h3></div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl"><i class="fas fa-check-circle"></i></div>
            <div><p class="text-slate-400 text-xs font-bold uppercase">Disetujui</p><h3 class="text-2xl font-black text-slate-800">{{ $stats['approved'] }}</h3></div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl"><i class="fas fa-clock"></i></div>
            <div><p class="text-slate-400 text-xs font-bold uppercase">Menunggu</p><h3 class="text-2xl font-black text-slate-800">{{ $stats['pending'] }}</h3></div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl"><i class="fas fa-times-circle"></i></div>
            <div><p class="text-slate-400 text-xs font-bold uppercase">Ditolak</p><h3 class="text-2xl font-black text-slate-800">{{ $stats['rejected'] }}</h3></div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate__animated animate__fadeInUp">
        <div class="px-6 py-5 border-b border-slate-100"><h3 class="font-bold text-slate-800">Riwayat Laporan</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Proyek</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Hasil Review</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentReports as $report)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4 font-medium">{{ $report->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $report->project->project_name }}</td>
                        <td class="px-6 py-4">
                            @if($report->status == 'approved')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold border border-emerald-200"><i class="fas fa-check"></i> Disetujui</span>
                            @elseif($report->status == 'rejected')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold border border-red-200"><i class="fas fa-times"></i> Ditolak</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-bold border border-orange-200"><i class="fas fa-clock"></i> Pending</span>
                            @endif
                        </td>
                        
                        {{-- HASIL REVIEW --}}
                        <td class="px-6 py-4">
                            @if($report->status == 'approved')
                                <div class="flex flex-col">
                                    <span class="text-emerald-600 font-black text-lg">{{ $report->rating }}<span class="text-xs text-slate-400 font-normal">/100</span></span>
                                    <span class="text-[10px] text-slate-500 italic truncate max-w-[150px]">"{{ $report->admin_note ?? 'Bagus' }}"</span>
                                </div>
                            @elseif($report->status == 'rejected')
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-red-500 uppercase">Perbaikan:</span>
                                    <span class="text-xs text-slate-600 italic bg-red-50 p-1 rounded border border-red-100 max-w-[180px]">{{ $report->admin_note ?? '-' }}</span>
                                </div>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Tombol Detail --}}
                                <button x-data @click="$dispatch('open-modal', 'detail-modal-{{ $report->id }}')" 
                                    class="w-8 h-8 rounded-lg border border-slate-200 text-slate-500 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all flex items-center justify-center" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>

                                {{-- Tombol Print (Hanya jika Approved) --}}
                                @if($report->status == 'approved')
                                    <a href="{{ route('subkon-eks.reports.print', $report->id) }}" target="_blank"
                                       class="w-8 h-8 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 shadow-md shadow-emerald-500/30 transition-all flex items-center justify-center" title="Cetak PDF">
                                        <i class="fas fa-print"></i>
                                    </a>
                                @endif

                                {{-- Tombol Delete (Baru) --}}
                                <form action="{{ route('subkon-eks.reports.destroy', $report->id) }}" method="POST" class="inline-block">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this)"
                                        class="w-8 h-8 rounded-lg bg-red-50 border border-red-100 text-red-500 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all flex items-center justify-center" title="Hapus Laporan">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- MODAL DETAIL --}}
                    <x-modal name="detail-modal-{{ $report->id }}" focusable>
                        <div class="bg-white rounded-2xl overflow-hidden flex flex-col max-h-[90vh]">
                            <div class="bg-slate-900 px-6 py-4 flex justify-between items-center shrink-0">
                                <h3 class="text-white font-bold text-lg">Detail Laporan #{{ $report->id }}</h3>
                                <button x-on:click="$dispatch('close')" class="text-slate-400 hover:text-white"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="p-6 overflow-y-auto">
                                @if($report->status != 'pending')
                                    <div class="mb-6 p-4 rounded-xl {{ $report->status == 'approved' ? 'bg-emerald-50 border border-emerald-100' : 'bg-red-50 border border-red-100' }}">
                                        <h4 class="text-sm font-bold uppercase {{ $report->status == 'approved' ? 'text-emerald-700' : 'text-red-700' }} mb-2">
                                            {{ $report->status == 'approved' ? 'Hasil Penilaian' : 'Alasan Penolakan' }}
                                        </h4>
                                        <div class="flex items-start gap-4">
                                            @if($report->status == 'approved')
                                                <div class="bg-white p-3 rounded-lg border border-emerald-100 text-center min-w-[80px]">
                                                    <span class="block text-2xl font-black text-emerald-600">{{ $report->rating }}</span>
                                                    <span class="text-[10px] text-emerald-400 font-bold uppercase">Nilai</span>
                                                </div>
                                            @endif
                                            <div class="flex-1">
                                                <p class="text-sm {{ $report->status == 'approved' ? 'text-emerald-800' : 'text-red-800' }}">
                                                    "{{ $report->admin_note ?? 'Tidak ada catatan khusus.' }}"
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="mb-6">
                                    @if($report->documentation_path)
                                        <img src="{{ $report->documentation_path }}" class="w-full rounded-xl shadow-md border border-slate-200">
                                    @else
                                        <div class="w-full h-48 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400"><i class="fas fa-image text-4xl mb-2"></i><br>Tidak ada foto</div>
                                    @endif
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div class="col-span-2"><span class="block text-xs font-bold text-slate-400 uppercase">Deskripsi Pekerjaan</span><p class="font-medium text-slate-800 bg-slate-50 p-3 rounded-lg mt-1 border border-slate-100">{{ $report->work_description }}</p></div>
                                    <div><span class="block text-xs font-bold text-slate-400 uppercase">Proyek</span><p class="font-bold text-slate-800">{{ $report->project->project_name }}</p></div>
                                    <div><span class="block text-xs font-bold text-slate-400 uppercase">Progress</span><p class="font-bold text-blue-600">{{ $report->progress_percentage }}%</p></div>
                                </div>
                            </div>
                            <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                                <button x-on:click="$dispatch('close')" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50">Tutup</button>
                            </div>
                        </div>
                    </x-modal>

                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Belum ada laporan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SCRIPT DELETE --}}
    <script>
        function confirmDelete(btn) {
            Swal.fire({
                title: 'Hapus Laporan?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.closest('form').submit();
                    
                }
            })
        }
    </script>
</x-admin-layout>