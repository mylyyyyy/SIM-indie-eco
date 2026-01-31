<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Riwayat Klaim Pembayaran</h2>
            <p class="text-slate-500 font-medium">Pantau status pencairan dana proyek Anda.</p>
        </div>
        {{-- PERUBAHAN ROUTE DISINI --}}
        <a href="{{ route('subkon-eks.report-payments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg">
            <i class="fas fa-plus-circle"></i> Ajukan Klaim
        </a>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
            });
        </script>
    @endif

    {{-- Grid Card (Isinya sama seperti sebelumnya) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($reports as $report)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col hover:shadow-lg transition-all duration-300">
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <span class="text-xs font-bold text-slate-500">{{ \Carbon\Carbon::parse($report->report_date)->format('d M Y') }}</span>
                @if($report->status == 'pending')
                    <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-[10px] font-bold border border-orange-200">Menunggu</span>
                @elseif($report->status == 'approved')
                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold border border-emerald-200">Disetujui</span>
                @else
                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-[10px] font-bold border border-red-200">Ditolak</span>
                @endif
            </div>
            <div class="p-5 flex-1">
                <h3 class="font-bold text-slate-800 mb-1">{{ $report->project->project_name ?? 'Proyek...' }}</h3>
                <p class="text-xs text-slate-500 mb-4 line-clamp-2">{{ $report->work_description }}</p>
                @if($report->admin_note)
                    <div class="p-3 rounded-xl bg-yellow-50 border border-yellow-200 text-xs text-yellow-800 mt-2">
                        <strong>Catatan:</strong> {{ $report->admin_note }}
                    </div>
                @endif
            </div>
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50">
                <button onclick="showImage('{{ $report->documentation_path }}')" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                    <i class="fas fa-image"></i> Lihat Bukti Scan
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-slate-400">Belum ada pengajuan klaim.</div>
        @endforelse
    </div>

    <script>
        function showImage(src) {
            if(!src) { Swal.fire('Info', 'Tidak ada gambar', 'info'); return; }
            Swal.fire({ imageUrl: src, width: 600, showConfirmButton: false, showCloseButton: true });
        }
    </script>
</x-admin-layout>