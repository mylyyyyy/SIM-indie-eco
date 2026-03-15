<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Inventaris & Aset Kantor</h2>
            <p class="text-slate-500 font-medium">Laporan rekapitulasi kondisi aset kantor per bulan.</p>
        </div>
        
        <button x-data @click="$dispatch('open-modal', 'add-inv-modal')" 
            class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-plus-circle"></i> <span>Input Inventaris</span>
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
                        <th class="px-6 py-4 text-center">Kondisi Baik</th>
                        <th class="px-6 py-4 text-center">Kondisi Rusak</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Lampiran</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($inventaris as $item)
                    <tr class="hover:bg-indigo-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-indigo-600">{{ $item->kode_laporan }}</div>
                            <div class="text-xs text-slate-500 mt-1 font-semibold">{{ $item->periode_bulan }}</div>
                            @if($item->keterangan)
                                <div class="text-[10px] text-slate-400 mt-1 italic line-clamp-1" title="{{ $item->keterangan }}">"{{ $item->keterangan }}"</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-lg font-bold border border-emerald-100"><i class="fas fa-check-circle mr-1"></i> {{ $item->total_aset_baik }} Unit</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-red-50 text-red-600 px-3 py-1 rounded-lg font-bold border border-red-100"><i class="fas fa-times-circle mr-1"></i> {{ $item->total_aset_rusak }} Unit</span>
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
                                <a href="{{ $item->dokumen_lampiran }}" download="Inventaris_{{ $item->kode_laporan }}" class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm inline-flex items-center justify-center" title="Unduh File">
                                    <i class="fas fa-file-excel"></i>
                                </a>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('indie.kepala-kantor.inventaris.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus laporan inventaris ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white transition-all shadow-sm flex items-center justify-center mx-auto" {{ $item->status == 'Approved' ? 'disabled opacity-50 cursor-not-allowed' : '' }}>
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium"><i class="fas fa-boxes text-4xl mb-3 block"></i> Belum ada data inventaris.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <x-modal name="add-inv-modal" focusable>
        <form action="{{ route('indie.kepala-kantor.inventaris.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
            @csrf
            <div class="bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                <h3 class="text-white font-bold text-lg">Laporan Inventaris Bulanan</h3>
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
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Total Aset Baik</label>
                        <div class="relative">
                            <i class="fas fa-check-circle absolute left-4 top-3 text-emerald-500"></i>
                            <input type="number" name="total_aset_baik" value="0" min="0" class="w-full pl-10 pr-4 border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500 font-bold text-slate-700" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Total Aset Rusak</label>
                        <div class="relative">
                            <i class="fas fa-times-circle absolute left-4 top-3 text-red-500"></i>
                            <input type="number" name="total_aset_rusak" value="0" min="0" class="w-full pl-10 pr-4 border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500 font-bold text-slate-700" required>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Keterangan / Rincian Kerusakan</label>
                    <textarea name="keterangan" rows="2" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Opsional..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Upload Data Rincian Aset (Excel/PDF)</label>
                    <input type="file" name="dokumen_lampiran" class="w-full border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".pdf,.xls,.xlsx" required>
                </div>

            </div>
            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl border-t border-slate-100">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700">Submit Laporan</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>