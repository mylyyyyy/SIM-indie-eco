<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Rekap Anggaran (Opex)</h2>
            <p class="text-slate-500 font-medium">Laporan penyerapan dana operasional bulanan cabang.</p>
        </div>
        
        <button x-data @click="$dispatch('open-modal', 'add-opex-modal')" 
            class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-plus-circle"></i> <span>Input Rekap Opex</span>
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
                        <th class="px-6 py-4">Rincian Anggaran (IDR)</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">LPJ</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($opex_reports as $item)
                        @php
                            $sisa = $item->total_anggaran - $item->total_pengeluaran;
                            $persentase = $item->total_anggaran > 0 ? round(($item->total_pengeluaran / $item->total_anggaran) * 100) : 0;
                        @endphp
                    <tr class="hover:bg-indigo-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-indigo-600">{{ $item->kode_laporan }}</div>
                            <div class="text-xs text-slate-500 mt-1 font-semibold">{{ $item->periode_bulan }}</div>
                            @if($item->keterangan)
                                <div class="text-[10px] text-slate-400 mt-1 italic line-clamp-1">"{{ $item->keterangan }}"</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Anggaran:</span> 
                                    <span class="font-bold text-slate-700">Rp {{ number_format($item->total_anggaran, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Terpakai:</span> 
                                    <span class="font-bold text-red-500">Rp {{ number_format($item->total_pengeluaran, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between pt-1 border-t border-slate-100 mt-1">
                                    <span class="text-slate-500 font-bold">Sisa:</span> 
                                    <span class="font-bold {{ $sisa < 0 ? 'text-red-500' : 'text-emerald-500' }}">Rp {{ number_format($sisa, 0, ',', '.') }}</span>
                                </div>
                                {{-- Progress Bar Penyerapan --}}
                                <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2">
                                    <div class="h-1.5 rounded-full {{ $persentase > 100 ? 'bg-red-500' : ($persentase > 80 ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width: {{ $persentase > 100 ? 100 : $persentase }}%"></div>
                                </div>
                                <span class="text-[9px] text-slate-400 text-right mt-0.5">Penyerapan: {{ $persentase }}%</span>
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
                                <a href="{{ $item->dokumen_lampiran }}" download="Opex_{{ $item->kode_laporan }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm" title="Unduh Bukti/LPJ">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </a>
                            @else
                                <span class="text-xs text-red-400 italic">No File</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('indie.kepala-kantor.opex.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus laporan opex ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white transition-all shadow-sm" {{ $item->status == 'Approved' ? 'disabled opacity-50 cursor-not-allowed' : '' }}>
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium"><i class="fas fa-wallet text-4xl mb-3 block"></i> Belum ada laporan anggaran (Opex).</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <x-modal name="add-opex-modal" focusable>
        <form action="{{ route('indie.kepala-kantor.opex.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
            @csrf
            <div class="bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                <h3 class="text-white font-bold text-lg">Input Rekap Anggaran (Opex)</h3>
                <button type="button" x-on:click="$dispatch('close')" class="text-indigo-200 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-5">
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Periode Laporan</label>
                    <select name="periode_bulan" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="{{ date('F Y') }}">Bulan Ini ({{ date('F Y') }})</option>
                        <option value="{{ date('F Y', strtotime('-1 month')) }}">Bulan Lalu ({{ date('F Y', strtotime('-1 month')) }})</option>
                    </select>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Total Anggaran (Rp)</label>
                        <input type="number" name="total_anggaran" value="0" min="0" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500 font-bold text-slate-700" required placeholder="Contoh: 15000000">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Total Pengeluaran (Rp)</label>
                        <input type="number" name="total_pengeluaran" value="0" min="0" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500 font-bold text-red-500" required placeholder="Contoh: 14500000">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Keterangan Penggunaan (Singkat)</label>
                    <textarea name="keterangan" rows="2" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Pembayaran listrik, internet, ATK..."></textarea>
                </div>

                <div class="bg-amber-50 border border-amber-100 p-4 rounded-xl">
                    <label class="block text-xs font-bold text-amber-700 uppercase mb-2"><i class="fas fa-paperclip mr-1"></i> Upload Rekap / LPJ (Wajib)</label>
                    <p class="text-[10px] text-amber-600 mb-2">Format: PDF, Excel, atau Foto Bukti Transaksi. Maksimal 2MB.</p>
                    <input type="file" name="dokumen_lampiran" class="w-full border border-amber-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200" accept=".pdf,.jpg,.png,.xls,.xlsx" required>
                </div>

            </div>
            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl border-t border-slate-100">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700">Submit Opex</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>