<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Keuangan Lapangan</h2>
            <p class="text-slate-500 font-medium">Laporan kas kecil (Petty Cash) operasional proyek per bulan.</p>
        </div>
        
        <button x-data @click="$dispatch('open-modal', 'add-keu-modal')" 
            class="bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-sky-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-plus-circle"></i> <span>Buat Laporan Kas</span>
        </button>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false, toast: true, position: 'top-end' });
            });
        </script>
    @endif
    @if($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 border border-red-100 font-medium text-sm">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
        </div>
    @endif

    {{-- TABLE CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Kode & Periode</th>
                        <th class="px-6 py-4">Rincian Arus Kas (Rp)</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">LPJ</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($laporans as $item)
                        @php
                            // Ambil dari JSON (dengan fallback 0 jika tidak ada)
                            $saldo_awal = $item->data_spesifik['saldo_awal'] ?? 0;
                            $pemasukan = $item->data_spesifik['total_pemasukan'] ?? ($item->data_spesifik['pemasukan'] ?? 0);
                            $pengeluaran = $item->data_spesifik['total_pengeluaran'] ?? ($item->data_spesifik['pengeluaran'] ?? 0);
                            
                            $saldo_akhir = ($saldo_awal + $pemasukan) - $pengeluaran;
                        @endphp
                    <tr class="hover:bg-sky-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-sky-600">{{ $item->kode_referensi }}</div>
                            <div class="text-xs text-slate-500 mt-1 font-semibold">{{ $item->data_spesifik['periode_bulan'] ?? \Carbon\Carbon::parse($item->tanggal_periode)->format('F Y') }}</div>
                            @if($item->keterangan_umum)
                                <div class="text-[10px] text-slate-400 mt-1 italic line-clamp-2">"{{ $item->keterangan_umum }}"</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Saldo Awal:</span> 
                                    <span class="font-semibold text-slate-700">Rp {{ number_format($saldo_awal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Pemasukan (Top Up):</span> 
                                    <span class="font-bold text-emerald-500">+ Rp {{ number_format($pemasukan, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between border-b border-slate-100 pb-1">
                                    <span class="text-slate-500">Pengeluaran:</span> 
                                    <span class="font-bold text-red-500">- Rp {{ number_format($pengeluaran, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between pt-1">
                                    <span class="text-slate-500 font-bold">Saldo Akhir:</span> 
                                    <span class="font-black {{ $saldo_akhir < 0 ? 'text-red-600' : 'text-sky-600' }}">Rp {{ number_format($saldo_akhir, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status == 'Approved')
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-md uppercase">Approved</span>
                            @elseif($item->status == 'Revisi')
                                <span class="px-2.5 py-1 bg-red-100 text-red-700 text-[10px] font-bold rounded-md uppercase" title="{{ $item->catatan_evaluator }}">Revisi <i class="fas fa-info-circle"></i></span>
                            @else
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-md uppercase">Submit</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->dokumen_lampiran)
                                <a href="{{ $item->dokumen_lampiran }}" download="LapKeu_{{ $item->kode_referensi }}" class="w-8 h-8 mx-auto rounded-lg bg-sky-50 border border-sky-100 text-sky-600 hover:bg-sky-600 hover:text-white transition-all shadow-sm flex items-center justify-center" title="Unduh File Rekap">
                                    <i class="fas fa-file-excel"></i>
                                </a>
                            @else
                                <span class="text-xs text-slate-400 italic">Kosong</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('indie.admin-lapangan.keuangan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus laporan keuangan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white transition-all shadow-sm mx-auto flex items-center justify-center" {{ $item->status == 'Approved' ? 'disabled opacity-50 cursor-not-allowed' : '' }}>
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium"><i class="fas fa-wallet text-4xl mb-3 block"></i> Belum ada Laporan Keuangan Lapangan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <x-modal name="add-keu-modal" focusable>
        <form action="{{ route('indie.admin-lapangan.keuangan.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
            @csrf
            <div class="bg-gradient-to-r from-sky-500 to-blue-600 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                <h3 class="text-white font-bold text-lg">Input Rekap Kas Lapangan</h3>
                <button type="button" x-on:click="$dispatch('close')" class="text-sky-100 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-5">
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Periode Laporan</label>
                    <select name="periode_bulan" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500" required>
                        <option value="{{ date('F Y') }}">Bulan Ini ({{ date('F Y') }})</option>
                        <option value="{{ date('F Y', strtotime('-1 month')) }}">Bulan Lalu ({{ date('F Y', strtotime('-1 month')) }})</option>
                    </select>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Saldo Awal</label>
                        <input type="number" name="saldo_awal" value="0" min="0" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500 font-bold" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Total Pemasukan</label>
                        <input type="number" name="total_pemasukan" value="0" min="0" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500 font-bold text-emerald-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Total Pengeluaran</label>
                        <input type="number" name="total_pengeluaran" value="0" min="0" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500 font-bold text-red-500" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Keterangan / Catatan</label>
                    <textarea name="keterangan_umum" rows="2" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Contoh: Laporan kas kecil proyek tahap 1..."></textarea>
                </div>

                <div class="bg-slate-50 border border-slate-200 p-4 rounded-xl">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2"><i class="fas fa-file-excel mr-1"></i> Upload Rekap BKK / LPJ (Wajib)</label>
                    <p class="text-[10px] text-slate-500 mb-2">Unggah rekapitulasi Excel atau PDF bukti pengeluaran.</p>
                    <input type="file" name="dokumen_lampiran" class="w-full border border-slate-300 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-sky-100 file:text-sky-700 hover:file:bg-sky-200" accept=".pdf,.xls,.xlsx,.jpg,.png" required>
                </div>

            </div>
            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl border-t border-slate-100">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-sky-600 text-white rounded-xl text-sm font-bold hover:bg-sky-700">Kirim Laporan</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>