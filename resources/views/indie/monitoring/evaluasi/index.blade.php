<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Approval Laporan & Evaluasi</h2>
        <p class="text-slate-500 font-medium">Pusat verifikasi seluruh dokumen operasional cabang Indie.</p>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false, toast: true, position: 'top-end' });
            });
        </script>
    @endif

    {{-- FILTER BAR --}}
    <form method="GET" action="{{ route('indie.monitoring.evaluasi.index') }}" class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 mb-6 flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[150px]">
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Filter Modul</label>
            <select name="modul" class="w-full border-slate-200 rounded-lg text-sm">
                <option value="">Semua Modul</option>
                <option value="Survey Bahan" {{ request('modul') == 'Survey Bahan' ? 'selected' : '' }}>Survey Bahan</option>
                <option value="Progres Fisik" {{ request('modul') == 'Progres Fisik' ? 'selected' : '' }}>Progres Fisik</option>
                <option value="Absensi Proyek" {{ request('modul') == 'Absensi Proyek' ? 'selected' : '' }}>Absensi Proyek</option>
                <option value="Laporan Cuaca" {{ request('modul') == 'Laporan Cuaca' ? 'selected' : '' }}>Laporan Cuaca</option>
                <option value="Keuangan Lapangan" {{ request('modul') == 'Keuangan Lapangan' ? 'selected' : '' }}>Keuangan Lapangan</option>
            </select>
        </div>
        <div class="flex-1 min-w-[150px]">
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Filter Status</label>
            <select name="status" class="w-full border-slate-200 rounded-lg text-sm">
                <option value="">Semua Status</option>
                <option value="Submit" {{ request('status') == 'Submit' ? 'selected' : '' }}>Menunggu (Submit)</option>
                <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                <option value="Revisi" {{ request('status') == 'Revisi' ? 'selected' : '' }}>Revisi</option>
            </select>
        </div>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-bold text-sm shadow-sm transition-colors">
            Terapkan Filter
        </button>
        <a href="{{ route('indie.monitoring.evaluasi.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2 rounded-lg font-bold text-sm transition-colors">
            Reset
        </a>
    </form>

    {{-- TABLE CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Informasi Dokumen</th>
                        <th class="px-6 py-4">Cabang / Pengirim</th>
                        <th class="px-6 py-4">Keterangan Lapangan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi / Review</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($laporans as $item)
                    <tr class="hover:bg-indigo-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-slate-100 text-slate-700 text-[10px] font-bold rounded uppercase mb-1.5 inline-block">{{ $item->modul_laporan }}</span>
                            <div class="font-bold text-indigo-600">{{ $item->kode_referensi }}</div>
                            <div class="text-[10px] text-slate-500 mt-1">Periode: {{ \Carbon\Carbon::parse($item->tanggal_periode)->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800"><i class="far fa-building text-slate-400 mr-1"></i> {{ $item->user->company_name ?? 'Pusat' }}</div>
                            <div class="text-xs text-slate-500 mt-1"><i class="fas fa-user text-slate-400 mr-1"></i> {{ $item->user->name ?? 'Unknown' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-700 line-clamp-2">{{ $item->keterangan_umum ?? 'Tidak ada catatan khusus.' }}</p>
                            @if($item->catatan_evaluator)
                                <p class="text-[10px] font-bold text-red-500 mt-1 bg-red-50 p-1 rounded inline-block"><i class="fas fa-reply mr-1"></i> Catatan Revisi: {{ $item->catatan_evaluator }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status == 'Approved')
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-md uppercase border border-emerald-200">Approved</span>
                            @elseif($item->status == 'Revisi')
                                <span class="px-2.5 py-1 bg-red-100 text-red-700 text-[10px] font-bold rounded-md uppercase border border-red-200">Revisi</span>
                            @else
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-md uppercase border border-amber-200">Perlu Review</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                @if($item->dokumen_lampiran)
                                    <a href="{{ $item->dokumen_lampiran }}" download="{{ $item->modul_laporan }}_{{ $item->kode_referensi }}" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-all shadow-sm flex items-center justify-center" title="Unduh Lampiran">
                                        <i class="fas fa-paperclip text-xs"></i>
                                    </a>
                                @endif
                                
                                {{-- Tombol Buka Modal Evaluasi --}}
                                <button x-data @click="$dispatch('open-modal', 'review-modal-{{ $item->id }}')" class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-bold text-[10px] uppercase shadow-sm transition-colors">
                                    Review
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- MODAL REVIEW / APPROVAL --}}
                    <x-modal name="review-modal-{{ $item->id }}" focusable>
                        <form action="{{ route('indie.monitoring.evaluasi.update', $item->id) }}" method="POST" class="bg-white rounded-2xl flex flex-col">
                            @csrf @method('PUT')
                            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                                <h3 class="text-white font-bold text-lg">Review Laporan</h3>
                                <button type="button" x-on:click="$dispatch('close')" class="text-slate-400 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
                            </div>
                            
                            <div class="p-6 space-y-4">
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-sm">
                                    <div class="grid grid-cols-2 gap-2 mb-2">
                                        <span class="text-slate-500 font-bold">Modul:</span> <span class="text-slate-800 font-semibold">{{ $item->modul_laporan }}</span>
                                        <span class="text-slate-500 font-bold">Pengirim:</span> <span class="text-slate-800">{{ $item->user->name ?? '-' }} ({{ $item->user->company_name ?? '-' }})</span>
                                        <span class="text-slate-500 font-bold">Tanggal:</span> <span class="text-slate-800">{{ \Carbon\Carbon::parse($item->tanggal_periode)->format('d F Y') }}</span>
                                    </div>
                                    <hr class="my-2 border-slate-200">
                                    <span class="text-slate-500 font-bold block mb-1">Catatan Pengirim:</span>
                                    <p class="text-slate-700 italic border-l-2 border-indigo-400 pl-2">{{ $item->keterangan_umum ?? 'Tidak ada.' }}</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Keputusan Review</label>
                                    <select name="status" class="w-full border-slate-300 rounded-xl text-sm font-bold bg-slate-50" required>
                                        <option value="Approved" class="text-emerald-600" {{ $item->status == 'Approved' ? 'selected' : '' }}>Setujui Laporan (Approved)</option>
                                        <option value="Revisi" class="text-red-600" {{ $item->status == 'Revisi' ? 'selected' : '' }}>Kembalikan Untuk Revisi</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Catatan Evaluator (Wajib jika Revisi)</label>
                                    <textarea name="catatan_evaluator" rows="3" class="w-full border-slate-300 rounded-xl text-sm" placeholder="Berikan catatan perbaikan atau komentar persetujuan di sini...">{{ $item->catatan_evaluator }}</textarea>
                                </div>
                            </div>
                            
                            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl border-t border-slate-200">
                                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-50">Batal</button>
                                <button type="submit" class="px-5 py-2.5 bg-slate-800 text-white rounded-xl text-sm font-bold hover:bg-slate-900">Simpan Keputusan</button>
                            </div>
                        </form>
                    </x-modal>

                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium"><i class="fas fa-check-circle text-4xl mb-3 block text-emerald-200"></i> Semua laporan sudah direview atau belum ada data masuk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $laporans->links() }}
        </div>
    </div>
</x-admin-layout>