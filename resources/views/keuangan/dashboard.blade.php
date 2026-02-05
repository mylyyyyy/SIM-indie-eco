<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- HEADER --}}
    <div class="mb-8 animate__animated animate__fadeInDown">
        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Dashboard Keuangan</h2>
        <p class="text-slate-500 font-medium">Verifikasi klaim subkon dan kelola arus kas.</p>
    </div>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Pending --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase mb-1">Perlu Verifikasi</p>
                <h3 class="text-3xl font-black text-orange-500">{{ $stats['pending'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-clock"></i></div>
        </div>
        {{-- Paid Today --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase mb-1">Keluar Hari Ini</p>
                <h3 class="text-2xl font-black text-emerald-600">Rp {{ number_format($stats['paid_today'], 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-wallet"></i></div>
        </div>
        {{-- Total --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase mb-1">Total Disbursed</p>
                <h3 class="text-2xl font-black text-slate-700">Rp {{ number_format($stats['total_disbursed'], 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>

    {{-- TABS SECTION --}}
    <div x-data="{ activeTab: 'pending' }" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden min-h-[500px]">
        
        {{-- Tab Navigation --}}
        <div class="flex border-b border-slate-100">
            <button @click="activeTab = 'pending'" 
                :class="activeTab === 'pending' ? 'border-b-2 border-blue-600 text-blue-600 bg-blue-50/50' : 'text-slate-500 hover:bg-slate-50'"
                class="flex-1 py-4 text-sm font-bold transition-all flex justify-center items-center gap-2">
                <i class="fas fa-inbox"></i> Menunggu Persetujuan
                @if($stats['pending'] > 0) <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full">{{ $stats['pending'] }}</span> @endif
            </button>
            <button @click="activeTab = 'history'" 
                :class="activeTab === 'history' ? 'border-b-2 border-blue-600 text-blue-600 bg-blue-50/50' : 'text-slate-500 hover:bg-slate-50'"
                class="flex-1 py-4 text-sm font-bold transition-all flex justify-center items-center gap-2">
                <i class="fas fa-history"></i> Riwayat Transaksi
            </button>
        </div>

        {{-- TAB 1: PENDING (Action Needed) --}}
        <div x-show="activeTab === 'pending'" class="p-6" x-transition.opacity>
            @forelse($pendingPayments as $item)
            <div class="mb-4 bg-white border border-slate-200 rounded-xl p-5 hover:shadow-md transition-shadow flex flex-col md:flex-row gap-6">
                
                {{-- Kiri: Bukti Tagihan Subkon --}}
                <div class="w-full md:w-48 shrink-0">
                    <p class="text-xs font-bold text-slate-400 mb-2 uppercase">Dokumen Tagihan</p>
                    <div class="aspect-video bg-slate-100 rounded-lg overflow-hidden border border-slate-200 relative group cursor-pointer"
                         onclick="showImage('{{ $item->claim_document }}')">
                        @if($item->claim_document)
                            <img src="{{ $item->claim_document }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white text-xs font-bold"><i class="fas fa-search-plus"></i> Lihat</span>
                            </div>
                        @else
                            <div class="flex items-center justify-center h-full text-slate-400 text-xs">No File</div>
                        @endif
                    </div>
                </div>

                {{-- Tengah: Informasi --}}
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-2">
                        <span class="px-2 py-1 rounded bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wide">
                            {{ $item->project->project_name ?? 'Proyek Tidak Ditemukan' }}
                        </span>
                        <span class="text-xs text-slate-400 font-medium">
                            <i class="far fa-calendar-alt mr-1"></i> Diajukan: {{ \Carbon\Carbon::parse($item->payment_date)->format('d M Y') }}
                        </span>
                    </div>
                    
                    <div class="flex items-center gap-2 mb-1">
                        <h4 class="font-black text-slate-800 text-xl">Rp {{ number_format($item->amount, 0, ',', '.') }}</h4>
                    </div>
                    
                    <p class="text-sm text-slate-500 mb-2">
                        Oleh: <span class="font-bold text-slate-700">{{ $item->requestor->name }}</span>
                    </p>
                    <p class="text-xs text-slate-500 bg-slate-50 p-2 rounded border border-slate-100 italic">
                        "{{ $item->notes }}"
                    </p>
                </div>

                {{-- Kanan: Tombol Aksi --}}
                <div class="w-full md:w-48 flex flex-col justify-center gap-2 border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-6">
                    <button x-data @click="$dispatch('open-modal', 'verify-modal-{{ $item->id }}')" 
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-lg text-sm shadow-lg shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-money-bill-wave mr-1"></i> Bayar
                    </button>
                </div>
            </div>

            {{-- MODAL VERIFIKASI (Dynamic ID) --}}
            <x-modal name="verify-modal-{{ $item->id }}" focusable>
                <div class="p-6 bg-white" x-data="{ action: 'approve' }">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-lg font-bold text-slate-800">Proses Pembayaran</h3>
                        <button x-on:click="$dispatch('close')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
                    </div>
                    
                    <form action="{{ route('keuangan.reports.verify', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        {{-- Switch Approve/Reject --}}
                        <div class="flex gap-3 mb-6 bg-slate-50 p-1 rounded-xl">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="action" value="approve" x-model="action" class="peer sr-only">
                                <div class="text-center py-2 rounded-lg peer-checked:bg-white peer-checked:shadow-sm peer-checked:text-emerald-600 font-bold text-sm text-slate-400 transition-all">
                                    <i class="fas fa-check mr-1"></i> Setujui
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="action" value="reject" x-model="action" class="peer sr-only">
                                <div class="text-center py-2 rounded-lg peer-checked:bg-white peer-checked:shadow-sm peer-checked:text-red-600 font-bold text-sm text-slate-400 transition-all">
                                    <i class="fas fa-times mr-1"></i> Tolak
                                </div>
                            </label>
                        </div>

                        {{-- Field Jika Approve --}}
                        <div x-show="action === 'approve'" class="space-y-4 animate__animated animate__fadeIn">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nominal Transfer (Rp)</label>
                                <input type="number" name="amount" value="{{ $item->amount }}" class="w-full border-slate-200 rounded-xl font-mono font-bold text-slate-700 bg-slate-50" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Metode</label>
                                    <select name="payment_method" class="w-full border-slate-200 rounded-xl text-sm">
                                        <option value="Transfer BCA">Transfer BCA</option>
                                        <option value="Transfer Mandiri">Transfer Mandiri</option>
                                        <option value="Tunai">Tunai</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">No. Ref / Invoice</label>
                                    <input type="text" name="invoice_number" class="w-full border-slate-200 rounded-xl text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Upload Bukti Transfer <span class="text-red-500">*</span></label>
                                <input type="file" name="payment_proof" class="w-full text-sm text-slate-500 file:py-2 file:px-4 file:rounded-full file:bg-emerald-50 file:text-emerald-700 file:border-0 hover:file:bg-emerald-100" required>
                            </div>
                        </div>

                        {{-- Field Jika Reject --}}
                        <div x-show="action === 'reject'" class="animate__animated animate__fadeIn" style="display: none;">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Alasan Penolakan</label>
                            <textarea name="admin_note" rows="3" class="w-full border-red-200 rounded-xl text-sm focus:border-red-500 focus:ring-red-500" placeholder="Contoh: Nominal tidak sesuai progress..."></textarea>
                        </div>

                        {{-- Footer Modal --}}
                        <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-slate-800 text-white rounded-xl font-bold text-sm hover:bg-slate-900 transition-all">
                                <span x-text="action === 'approve' ? 'Konfirmasi Bayar' : 'Kirim Penolakan'"></span>
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
                <p class="text-slate-400 font-medium">Semua tagihan sudah diproses.</p>
            </div>
            @endforelse
        </div>

        {{-- TAB 2: HISTORY (Read Only) --}}
        <div x-show="activeTab === 'history'" class="p-0" x-transition.opacity style="display: none;">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold">
                        <tr>
                            <th class="px-6 py-4">Tgl Proses</th>
                            <th class="px-6 py-4">Penerima</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Nominal</th>
                            <th class="px-6 py-4 text-center">Bukti</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($historyPayments as $history)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                {{ $history->updated_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-700">{{ $history->requestor->name }}</div>
                                <div class="text-xs text-slate-400">{{ $history->project->project_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($history->status == 'paid')
                                    <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 px-2 py-1 rounded text-xs font-bold border border-emerald-200">
                                        <i class="fas fa-check-circle"></i> Paid
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold border border-red-200">
                                        <i class="fas fa-ban"></i> Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-slate-700">
                                Rp {{ number_format($history->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($history->payment_proof)
                                    <button onclick="showImage('{{ $history->payment_proof }}')" class="text-blue-600 hover:text-blue-800 text-xs font-bold bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 transition-colors">
                                        Lihat
                                    </button>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Script Modal Image --}}
    <script>
        function showImage(src) {
            Swal.fire({
                imageUrl: src,
                imageAlt: 'Preview',
                width: 600,
                showConfirmButton: false,
                showCloseButton: true,
                background: '#fff',
                customClass: { popup: 'rounded-2xl overflow-hidden' }
            });
        }

        // Alert Handler
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') }}" });
        @endif
    </script>
</x-admin-layout>