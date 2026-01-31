<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- HEADER --}}
    <div class="mb-8 animate__animated animate__fadeInDown">
        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Dashboard Keuangan</h2>
        <p class="text-slate-500 font-medium">Verifikasi klaim subkon dan kelola arus kas proyek.</p>
    </div>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Card 1: Pending --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase mb-1">Perlu Verifikasi</p>
                <h3 class="text-3xl font-black text-orange-500">{{ $stats['pending'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center text-xl">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        {{-- Card 2: Keluar Hari Ini --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase mb-1">Dibayar Hari Ini</p>
                <h3 class="text-2xl font-black text-emerald-600">Rp {{ number_format($stats['paid_today'], 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center text-xl">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
        {{-- Card 3: Total --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase mb-1">Total Pengeluaran</p>
                <h3 class="text-2xl font-black text-slate-700">Rp {{ number_format($stats['total_disbursed'], 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-xl">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT (TABS) --}}
    <div x-data="{ activeTab: 'pending' }" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden min-h-[500px]">
        
        {{-- Tab Headers --}}
        <div class="flex border-b border-slate-100">
            <button @click="activeTab = 'pending'" 
                :class="activeTab === 'pending' ? 'border-b-2 border-blue-600 text-blue-600 bg-blue-50/50' : 'text-slate-500 hover:bg-slate-50'"
                class="flex-1 py-4 text-sm font-bold transition-all flex justify-center items-center gap-2">
                <i class="fas fa-inbox"></i> Menunggu Verifikasi 
                @if($stats['pending'] > 0)
                    <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full">{{ $stats['pending'] }}</span>
                @endif
            </button>
            <button @click="activeTab = 'history'" 
                :class="activeTab === 'history' ? 'border-b-2 border-blue-600 text-blue-600 bg-blue-50/50' : 'text-slate-500 hover:bg-slate-50'"
                class="flex-1 py-4 text-sm font-bold transition-all flex justify-center items-center gap-2">
                <i class="fas fa-history"></i> Riwayat Pembayaran
            </button>
        </div>

        {{-- TAB 1: PENDING --}}
        <div x-show="activeTab === 'pending'" class="p-6" x-transition.opacity>
            @forelse($pendingReports as $report)
            <div class="mb-4 bg-white border border-slate-200 rounded-xl p-5 hover:shadow-md transition-shadow flex flex-col md:flex-row gap-6">
                
                {{-- Kiri: Bukti Scan --}}
                <div class="w-full md:w-48 shrink-0">
                    <div class="aspect-video bg-slate-100 rounded-lg overflow-hidden border border-slate-200 relative group cursor-pointer"
                         onclick="showImage('{{ $report->documentation_path }}')">
                        @if($report->documentation_path)
                            <img src="{{ $report->documentation_path }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white text-xs font-bold"><i class="fas fa-search-plus mr-1"></i> Perbesar</span>
                            </div>
                        @else
                            <div class="flex items-center justify-center h-full text-slate-400 text-xs">No Image</div>
                        @endif
                    </div>
                </div>

                {{-- Tengah: Info --}}
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-2">
                        <span class="px-2 py-1 rounded bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wide">
                            {{ $report->project->project_name }}
                        </span>
                        <span class="text-xs text-slate-400 font-medium">
                            <i class="far fa-clock mr-1"></i> {{ $report->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <h4 class="font-bold text-slate-800 text-lg mb-1">Klaim: {{ $report->work_description }}</h4>
                    <p class="text-sm text-slate-500 mb-3">
                        Oleh: <span class="font-semibold text-slate-700">{{ $report->user->name }}</span> • 
                        Progress: <span class="font-semibold text-blue-600">{{ $report->progress_percentage }}%</span>
                    </p>
                </div>

                {{-- Kanan: Aksi --}}
                <div class="w-full md:w-48 flex flex-col justify-center gap-2 border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-6">
                    <button x-data @click="$dispatch('open-modal', 'verify-modal-{{ $report->id }}')" 
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-lg text-sm shadow-lg shadow-emerald-500/20 transition-all">
                        <i class="fas fa-check-circle mr-1"></i> Proses
                    </button>
                </div>
            </div>

            {{-- MODAL VERIFIKASI (Looping untuk setiap item) --}}
            <x-modal name="verify-modal-{{ $report->id }}" focusable>
                <div class="p-6 bg-white" x-data="{ action: 'approve' }">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Verifikasi Pembayaran</h3>
                    
                    <form action="{{ route('keuangan.reports.verify', $report->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        {{-- Pilihan Aksi --}}
                        <div class="flex gap-4 mb-6">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="action" value="approve" x-model="action" class="peer sr-only">
                                <div class="text-center py-3 border-2 rounded-xl peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 font-bold text-sm text-slate-400 transition-all">
                                    <i class="fas fa-check mb-1"></i><br>SETUJUI & BAYAR
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="action" value="reject" x-model="action" class="peer sr-only">
                                <div class="text-center py-3 border-2 rounded-xl peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 font-bold text-sm text-slate-400 transition-all">
                                    <i class="fas fa-times mb-1"></i><br>TOLAK / REVISI
                                </div>
                            </label>
                        </div>

                        {{-- Form Jika Setuju --}}
                        <div x-show="action === 'approve'" class="space-y-4 animate__animated animate__fadeIn">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nominal Pembayaran (Rp)</label>
                                <input type="number" name="amount" class="w-full border-slate-200 rounded-xl font-mono font-bold text-slate-700" placeholder="Contoh: 15000000">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Metode Bayar</label>
                                    <select name="payment_method" class="w-full border-slate-200 rounded-xl text-sm">
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="cash">Tunai</option>
                                        <option value="cheque">Cek / Giro</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">No. Invoice (Opsional)</label>
                                    <input type="text" name="invoice_number" class="w-full border-slate-200 rounded-xl text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Upload Bukti Transfer</label>
                                <input type="file" name="payment_proof" class="w-full text-sm text-slate-500 file:py-2 file:px-4 file:rounded-full file:bg-emerald-50 file:text-emerald-700 file:border-0 hover:file:bg-emerald-100">
                            </div>
                        </div>

                        {{-- Form Jika Tolak --}}
                        <div x-show="action === 'reject'" class="animate__animated animate__fadeIn" style="display: none;">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Alasan Penolakan / Catatan Revisi</label>
                            <textarea name="admin_note" rows="3" class="w-full border-red-200 rounded-xl text-sm focus:border-red-500 focus:ring-red-500" placeholder="Jelaskan kenapa ditolak..."></textarea>
                        </div>

                        {{-- Catatan (Muncul di kedua kondisi) --}}
                        <div x-show="action === 'approve'" class="mt-4">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Catatan Tambahan (Opsional)</label>
                            <textarea name="admin_note" rows="2" class="w-full border-slate-200 rounded-xl text-sm"></textarea>
                        </div>

                        <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 text-slate-500 font-bold text-sm">Batal</button>
                            <button type="submit" class="px-6 py-2 bg-slate-800 text-white rounded-xl font-bold text-sm hover:bg-slate-900 transition-colors">
                                Simpan Keputusan
                            </button>
                        </div>
                    </form>
                </div>
            </x-modal>

            @empty
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                    <i class="fas fa-check-double text-2xl"></i>
                </div>
                <p class="text-slate-400 font-medium">Tidak ada laporan pending.</p>
            </div>
            @endforelse
        </div>

        {{-- TAB 2: HISTORY --}}
        <div x-show="activeTab === 'history'" class="p-0" x-transition.opacity style="display: none;">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Tanggal Lapor</th>
                            <th class="px-6 py-4">Proyek & Subkon</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Nominal</th>
                            <th class="px-6 py-4 text-center">Bukti Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($historyReports as $history)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($history->report_date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-700">{{ $history->project->project_name }}</div>
                                <div class="text-xs text-slate-400">{{ $history->user->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($history->status == 'approved')
                                    <span class="text-emerald-600 font-bold text-xs"><i class="fas fa-check-circle"></i> Selesai</span>
                                @else
                                    <span class="text-red-500 font-bold text-xs"><i class="fas fa-times-circle"></i> Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-slate-700">
                                @if($history->payment)
                                    Rp {{ number_format($history->payment->amount, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($history->payment && $history->payment->payment_proof)
                                    <button onclick="showImage('{{ $history->payment->payment_proof }}')" class="text-blue-500 hover:text-blue-700 text-xs font-bold border border-blue-200 px-2 py-1 rounded">
                                        Lihat
                                    </button>
                                @else
                                    <span class="text-xs text-slate-300">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Script Global Show Image --}}
    <script>
        function showImage(src) {
            Swal.fire({
                imageUrl: src,
                imageAlt: 'Bukti',
                width: 500,
                showConfirmButton: false,
                showCloseButton: true,
                customClass: { popup: 'rounded-2xl overflow-hidden' }
            });
        }

        // Tampilkan Alert Sukses/Gagal
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') }}" });
        @endif
    </script>
</x-admin-layout>