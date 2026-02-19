<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Riwayat Klaim Pembayaran</h2>
            <p class="text-slate-500 font-medium">Pantau status pencairan dana proyek Anda.</p>
        </div>
        <a href="{{ route('subkon-eks.report-payments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-plus-circle"></i> Ajukan Klaim
        </a>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
            });
        </script>
    @endif

    {{-- Grid Card --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($payments as $payment)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col hover:shadow-lg transition-all duration-300">
            
            {{-- Card Header: Tanggal & Status --}}
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <span class="text-xs font-bold text-slate-500 flex items-center gap-1">
                    <i class="far fa-calendar-alt"></i> 
                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}
                </span>
                
                @if($payment->status == 'pending')
                    <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-[10px] font-bold border border-orange-200 uppercase tracking-wide">
                        <i class="fas fa-clock mr-1"></i> Menunggu
                    </span>
                @elseif($payment->status == 'paid')
                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold border border-emerald-200 uppercase tracking-wide">
                        <i class="fas fa-check-circle mr-1"></i> Dibayar
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-[10px] font-bold border border-red-200 uppercase tracking-wide">
                        <i class="fas fa-times-circle mr-1"></i> Ditolak
                    </span>
                @endif
            </div>

            {{-- Card Body --}}
            <div class="p-5 flex-1">
                <h3 class="font-bold text-slate-800 mb-2 text-sm uppercase tracking-wide">
                    {{ $payment->project->project_name ?? 'Proyek Tidak Ditemukan' }}
                </h3>
                
                {{-- Nominal Besar --}}
                <p class="text-2xl font-black text-blue-600 mb-3">
                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                </p>

                <p class="text-xs text-slate-500 mb-4 line-clamp-3 bg-slate-50 p-3 rounded-lg border border-slate-100 italic">
                    "{{ $payment->notes }}"
                </p>
            </div>

            {{-- Card Footer --}}
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex justify-between items-center">
                {{-- UBAH: Panggil fungsi showDocument --}}
                <button onclick="showDocument('{{ $payment->claim_document }}')" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1 transition-colors">
                    <i class="fas fa-file-pdf"></i> Lihat Dokumen
                </button>

                @if($payment->status == 'paid' && $payment->payment_proof)
                    <button onclick="showDocument('{{ $payment->payment_proof }}')" class="text-xs font-bold text-emerald-600 hover:text-emerald-800 flex items-center gap-1 transition-colors">
                        <i class="fas fa-receipt"></i> Bukti Transfer
                    </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-slate-400">
            <div class="bg-slate-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-folder-open text-2xl text-slate-300"></i>
            </div>
            <p>Belum ada pengajuan klaim pembayaran.</p>
        </div>
        @endforelse
    </div>

    {{-- Script Modal Dinamis (Mendeteksi PDF atau Gambar) --}}
    <script>
        function showDocument(src) {
            if(!src) { 
                Swal.fire({ icon: 'info', title: 'Tidak Ada File', text: 'Dokumen belum dilampirkan.', confirmButtonColor: '#3b82f6' }); 
                return; 
            }
            
            // CEK APAKAH FILE ADALAH PDF
            if (src.startsWith('data:application/pdf')) {
                Swal.fire({
                    title: 'Dokumen PDF',
                    html: '<iframe src="' + src + '" width="100%" height="500px" style="border:none; border-radius: 10px;"></iframe>',
                    width: 800,
                    showConfirmButton: false,
                    showCloseButton: true,
                    customClass: { popup: 'rounded-2xl' }
                });
            } 
            // JIKA BUKAN PDF, BERARTI GAMBAR
            else {
                Swal.fire({
                    imageUrl: src,
                    imageAlt: 'Bukti',
                    width: 600,
                    showConfirmButton: false,
                    showCloseButton: true,
                    customClass: { popup: 'rounded-2xl overflow-hidden' }
                });
            }
        }
    </script>
</x-admin-layout>