<x-admin-layout>
    {{-- Load SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- WRAPPER UTAMA DENGAN ALPINE DATA --}}
    <div x-data="{ activeTab: 'pending' }">

        {{-- HEADER & TABS --}}
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4 animate__animated animate__fadeInDown">
            <div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">Monitoring Lapangan</h2>
                <p class="text-slate-500 font-medium">Pantau laporan harian proyek secara real-time.</p>
            </div>
            
            {{-- Tab Navigation --}}
            <div class="bg-slate-100 p-1.5 rounded-xl inline-flex">
                <button @click="activeTab = 'pending'" 
                    :class="activeTab === 'pending' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                    class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
                    <i class="fas fa-clock"></i> Perlu Tindakan
                    @if($pendingReports->count() > 0)
                        <span class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full ml-1">{{ $pendingReports->count() }}</span>
                    @endif
                </button>
                <button @click="activeTab = 'history'" 
                    :class="activeTab === 'history' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                    class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
                    <i class="fas fa-history"></i> Riwayat Laporan
                </button>
            </div>
        </div>

        {{-- SUCCESS ALERT --}}
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success', title: 'Berhasil', text: "{{ session('success') }}",
                        timer: 2000, showConfirmButton: false, toast: true, position: 'top-end'
                    });
                });
            </script>
        @endif

        {{-- ================= TAB 1: PENDING REPORTS (GRID CARD) ================= --}}
        <div x-show="activeTab === 'pending'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($pendingReports as $report)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-lg hover:border-blue-300 transition-all duration-300 group flex flex-col h-full">
                    
                    {{-- Card Header --}}
                    <div class="p-5 border-b border-slate-50 flex justify-between items-start">
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100 mb-2">
                                <i class="fas fa-building mr-1.5"></i> {{ $report->project->project_name }}
                            </span>
                            <h3 class="font-bold text-slate-800 text-lg leading-tight">{{ $report->user->name }}</h3>
                            <p class="text-xs text-slate-400 mt-1">{{ $report->user->company_name ?? 'Vendor Eksternal' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tanggal</span>
                            <span class="text-xs font-bold text-slate-600">{{ $report->created_at->format('d M') }}</span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-5 flex-1">
                        <div class="mb-4">
                            <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed">"{{ $report->work_description }}"</p>
                        </div>
                        <div class="mb-4">
                            <div class="flex justify-between items-end mb-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Progress</span>
                                <span class="text-sm font-black text-blue-600">{{ $report->progress_percentage }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $report->progress_percentage }}%"></div>
                            </div>
                        </div>
                        
                        {{-- Thumbnail --}}
                        <div class="relative w-full h-32 bg-slate-100 rounded-xl overflow-hidden group cursor-pointer border border-slate-200"
                             x-data @click="$dispatch('open-modal', 'detail-modal-{{ $report->id }}')">
                            
                            @if($report->documentation_path)
                                <img src="{{ $report->documentation_path }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="flex items-center justify-center h-full text-slate-400 text-xs flex-col gap-1">
                                    <i class="fas fa-image text-xl"></i>
                                    <span>No Image</span>
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white text-xs font-bold"><i class="fas fa-eye"></i> Detail</span>
                            </div>
                        </div>
                    </div>

                    {{-- Card Footer (Pending) --}}
                    <div class="p-4 bg-slate-50 border-t border-slate-100 rounded-b-2xl flex gap-3">
                        <form action="{{ route('subkon-pt.reports.status', $report->id) }}" method="POST" class="flex-1">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="button" onclick="confirmReject(this)" class="w-full py-2.5 rounded-xl border border-red-200 text-red-600 text-sm font-bold hover:bg-red-50 transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        </form>
                        <form action="{{ route('subkon-pt.reports.status', $report->id) }}" method="POST" class="flex-1">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button type="button" onclick="confirmApprove(this)" class="w-full py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition-all flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                                <i class="fas fa-check"></i> Setujui
                            </button>
                        </form>
                    </div>
                </div>
                
                @include('subkon-pt.partials.detail-modal', ['report' => $report, 'is_history' => false])

                @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3">
                    <div class="flex flex-col items-center justify-center text-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                        <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center text-blue-300 mb-4">
                            <i class="fas fa-check-double text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800">Semua Beres!</h3>
                        <p class="text-slate-500 max-w-sm mt-2">Tidak ada laporan yang menunggu verifikasi saat ini.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        {{-- ================= TAB 2: HISTORY REPORTS (TABLE LIST) ================= --}}
        <div x-show="activeTab === 'history'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Pelapor</th>
                                <th class="px-6 py-4">Proyek</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Penilaian & Catatan</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($processedReports as $report)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-medium">{{ $report->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800">{{ $report->user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $report->user->company_name ?? 'Vendor' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-bold">{{ $report->project->project_name }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($report->status == 'approved')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold border border-emerald-200">
                                            <i class="fas fa-check-circle"></i> Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold border border-red-200">
                                            <i class="fas fa-times-circle"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4">
                                    @if($report->status == 'approved')
                                        <div class="flex flex-col gap-1">
                                            @if($report->rating)
                                                <span class="text-lg font-black text-blue-600 flex items-center gap-1">
                                                    {{ $report->rating }}<span class="text-xs text-slate-400 font-normal">/100</span>
                                                </span>
                                            @else
                                                <span class="text-xs text-slate-400">-</span>
                                            @endif
                                            
                                            @if($report->admin_note)
                                                <span class="text-xs text-slate-500 italic max-w-[200px] truncate">"{{ $report->admin_note }}"</span>
                                            @endif
                                        </div>
                                    @elseif($report->status == 'rejected')
                                        <div class="flex flex-col gap-1">
                                            <span class="text-[10px] font-bold text-red-500 uppercase">Alasan:</span>
                                            <span class="text-xs text-slate-700 font-medium bg-red-50 p-1.5 rounded border border-red-100 max-w-[200px]">
                                                {{ $report->admin_note ?? 'Tidak ada alasan.' }}
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Tombol Detail --}}
                                        <button x-data @click="$dispatch('open-modal', 'detail-modal-{{ $report->id }}')" class="text-blue-600 hover:text-blue-800 font-bold text-xs border border-blue-200 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-colors">
                                            Lihat
                                        </button>

                                        {{-- TOMBOL EDIT (BARU) --}}
                                        <button type="button" 
                                            onclick="editDecision('{{ route('subkon-pt.reports.status', $report->id) }}', '{{ $report->status }}', '{{ $report->rating ?? '' }}', '{{ $report->admin_note ?? '' }}')" 
                                            class="text-orange-500 hover:text-orange-700 font-bold text-xs border border-orange-200 bg-orange-50 px-3 py-1.5 rounded-lg hover:bg-orange-100 transition-colors flex items-center gap-1">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            @include('subkon-pt.partials.detail-modal', ['report' => $report, 'is_history' => true])

                            @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada riwayat laporan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- SCRIPTS LENGKAP (PENDING & EDIT HISTORY) --}}
    <script>
        // === LOGIKA TAB PENDING === //
        
        // 1. Approve (Pending)
        function confirmApprove(btn) {
            askApproveDetails(function(values) {
                let form = btn.closest('form');
                addHiddenInput(form, 'rating', values.rating);
                addHiddenInput(form, 'admin_note', values.note);
                form.submit();
            });
        }

        // 2. Reject (Pending)
        function confirmReject(btn) {
            askRejectDetails(function(values) {
                let form = btn.closest('form');
                addHiddenInput(form, 'admin_note', values.note);
                form.submit();
            });
        }

        // === LOGIKA TAB HISTORY (EDIT) === //

        // 3. Fungsi Utama Edit (Munculkan Pilihan)
        function editDecision(url, currentStatus, currentRating, currentNote) {
            Swal.fire({
                title: 'Ubah Keputusan?',
                text: 'Silakan pilih tindakan baru untuk laporan ini:',
                icon: 'info',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-check"></i> Setujui / Ubah Nilai',
                denyButtonText: '<i class="fas fa-times"></i> Tolak Laporan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#059669', // Green
                denyButtonColor: '#ef4444', // Red
                cancelButtonColor: '#64748b',
                customClass: { popup: 'rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pilih Setujui -> Tanya Nilai
                    askApproveDetails(function(values) {
                        submitDynamicForm(url, 'approved', values.rating, values.note);
                    }, currentRating, currentNote);
                } else if (result.isDenied) {
                    // Jika pilih Tolak -> Tanya Alasan
                    askRejectDetails(function(values) {
                        submitDynamicForm(url, 'rejected', null, values.note);
                    }, currentNote);
                }
            });
        }

        // === HELPER FUNCTIONS (MODAL INPUT) === //

        // Helper: Modal Input Nilai (Re-usable)
        function askApproveDetails(callback, defaultRating = '', defaultNote = '') {
            Swal.fire({
                title: 'Setujui Laporan',
                html: `
                    <div class="text-left">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nilai (0-100) <span class="text-red-500">*</span></label>
                        <input type="number" id="swal-rating" class="w-full border-slate-300 rounded-lg text-sm mb-4 focus:ring-emerald-500" value="${defaultRating}" placeholder="85" min="0" max="100">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan (Opsional)</label>
                        <textarea id="swal-note" class="w-full border-slate-300 rounded-lg text-sm focus:ring-emerald-500 h-20">${defaultNote}</textarea>
                    </div>
                `,
                confirmButtonText: 'Simpan Keputusan',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                preConfirm: () => {
                    const rating = document.getElementById('swal-rating').value;
                    const note = document.getElementById('swal-note').value;
                    if (!rating || rating < 0 || rating > 100) {
                        Swal.showValidationMessage('Harap masukkan nilai valid (0-100)');
                        return false;
                    }
                    return { rating: rating, note: note }
                }
            }).then((result) => {
                if (result.isConfirmed) callback(result.value);
            });
        }

        // Helper: Modal Input Penolakan (Re-usable)
        function askRejectDetails(callback, defaultNote = '') {
            Swal.fire({
                title: 'Tolak Laporan',
                html: `
                    <div class="text-left">
                        <label class="block text-xs font-bold text-red-600 uppercase mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                        <textarea id="swal-reject-note" class="w-full border-red-200 bg-red-50 rounded-lg text-sm focus:ring-red-500 h-24 placeholder:text-red-300">${defaultNote}</textarea>
                    </div>
                `,
                confirmButtonText: 'Tolak Laporan',
                confirmButtonColor: '#ef4444',
                showCancelButton: true,
                preConfirm: () => {
                    const note = document.getElementById('swal-reject-note').value;
                    if (!note) {
                        Swal.showValidationMessage('Harap isi alasan penolakan!');
                        return false;
                    }
                    return { note: note }
                }
            }).then((result) => {
                if (result.isConfirmed) callback(result.value);
            });
        }

        // Helper: Submit Form Dinamis (Untuk Tab History yang tidak punya form bawaan)
        function submitDynamicForm(url, status, rating, note) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Tambah Input Wajib
            addHiddenInput(form, '_token', csrfToken);
            addHiddenInput(form, '_method', 'PATCH');
            addHiddenInput(form, 'status', status);
            
            // Tambah Input Opsional
            if (rating) addHiddenInput(form, 'rating', rating);
            if (note) addHiddenInput(form, 'admin_note', note);

            document.body.appendChild(form);
            form.submit();
        }

        // Helper: Tambah Input ke Form
        function addHiddenInput(form, name, value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            form.appendChild(input);
        }
    </script>
</x-admin-layout>