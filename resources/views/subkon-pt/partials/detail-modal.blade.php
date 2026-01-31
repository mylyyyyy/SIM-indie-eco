<x-modal name="detail-modal-{{ $report->id }}" focusable>
    <div class="bg-white rounded-2xl overflow-hidden flex flex-col max-h-[90vh]">
        {{-- Header --}}
        <div class="bg-slate-900 px-6 py-4 flex justify-between items-center shrink-0">
            <h3 class="text-white font-bold text-lg">Detail Laporan #{{ $report->id }}</h3>
            <button x-on:click="$dispatch('close')" class="text-slate-400 hover:text-white"><i class="fas fa-times"></i></button>
        </div>

        
        {{-- Content --}}
        <div class="p-6 overflow-y-auto">
            <div class="mb-6">
                @if($report->documentation_path)
                    {{-- PERBAIKAN: Menggunakan Base64 langsung (tanpa asset/storage) --}}
                    <img src="{{ $report->documentation_path }}" class="w-full rounded-xl shadow-md border border-slate-200" alt="Bukti Lapangan">
                @else
                    <div class="w-full h-48 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400">
                        <i class="fas fa-image text-4xl mb-2"></i><br>Tidak ada dokumentasi
                    </div>
                @endif
            </div>
            
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div class="col-span-2">
                    <span class="block text-xs font-bold text-slate-400 uppercase mb-1">Deskripsi</span>
                    <p class="text-slate-700 font-medium bg-slate-50 p-3 rounded-lg border border-slate-100 leading-relaxed">{{ $report->work_description }}</p>
                </div>
                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase mb-1">Pelapor</span>
                    <p class="text-slate-800 font-bold">{{ $report->user->name }}</p>
                </div>
                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase mb-1">Waktu</span>
                    <p class="text-slate-800 font-bold">{{ $report->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase mb-1">Status</span>
                    @if($report->status == 'approved') 
                        <span class="text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded">Disetujui</span>
                    @elseif($report->status == 'rejected') 
                        <span class="text-red-600 font-bold bg-red-50 px-2 py-0.5 rounded">Ditolak</span>
                    @else 
                        <span class="text-orange-500 font-bold bg-orange-50 px-2 py-0.5 rounded">Pending</span> 
                    @endif
                </div>
                <div>
                     <span class="block text-xs font-bold text-slate-400 uppercase mb-1">Progress Klaim</span>
                     <span class="text-blue-600 font-bold">{{ $report->progress_percentage }}%</span>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3 shrink-0">
            <button x-on:click="$dispatch('close')" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50">Tutup</button>
            
            {{-- Tombol Aksi Cepat hanya muncul jika BUKAN history (alias masih pending) --}}
            @if(!$is_history)
                <form action="{{ route('subkon-pt.reports.status', $report->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 shadow-lg flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> Langsung Setujui
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-modal>