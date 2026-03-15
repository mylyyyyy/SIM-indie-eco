<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Laporan Presensi Pegawai</h2>
            <p class="text-slate-500 font-medium">Rekapitulasi tingkat kehadiran pegawai per bulan.</p>
        </div>
        
        <button x-data @click="$dispatch('open-modal', 'add-presensi-modal')" 
            class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-plus-circle"></i> <span>Input Presensi</span>
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
                        <th class="px-6 py-4">Total Pegawai</th>
                        <th class="px-6 py-4">Rincian Kehadiran (Hari)</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($presensis as $item)
                    <tr class="hover:bg-indigo-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-indigo-600">{{ $item->kode_laporan }}</div>
                            <div class="text-xs text-slate-500 mt-1 font-semibold">{{ $item->periode_bulan }}</div>
                            @if($item->keterangan)
                                <div class="text-[10px] text-slate-400 mt-1 italic line-clamp-1" title="{{ $item->keterangan }}">"{{ $item->keterangan }}"</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800 text-lg">{{ $item->total_pegawai }}</span> <span class="text-xs text-slate-500">Orang</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-3 text-xs">
                                <div class="flex flex-col items-center bg-emerald-50 px-2 py-1 rounded border border-emerald-100">
                                    <span class="text-slate-400 text-[10px] uppercase">Hadir</span>
                                    <span class="font-bold text-emerald-600">{{ $item->hadir }}</span>
                                </div>
                                <div class="flex flex-col items-center bg-amber-50 px-2 py-1 rounded border border-amber-100">
                                    <span class="text-slate-400 text-[10px] uppercase">Sakit</span>
                                    <span class="font-bold text-amber-600">{{ $item->sakit }}</span>
                                </div>
                                <div class="flex flex-col items-center bg-blue-50 px-2 py-1 rounded border border-blue-100">
                                    <span class="text-slate-400 text-[10px] uppercase">Izin</span>
                                    <span class="font-bold text-blue-600">{{ $item->izin }}</span>
                                </div>
                                <div class="flex flex-col items-center bg-red-50 px-2 py-1 rounded border border-red-100">
                                    <span class="text-slate-400 text-[10px] uppercase">Alpa</span>
                                    <span class="font-bold text-red-600">{{ $item->alpa }}</span>
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
                            <div class="flex justify-center gap-2">
                                @if($item->dokumen_lampiran)
                                    <a href="{{ $item->dokumen_lampiran }}" download="Presensi_{{ $item->kode_laporan }}" class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm flex items-center justify-center" title="Unduh File">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @endif
                                <form action="{{ route('indie.kepala-kantor.lap-presensi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus laporan presensi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white transition-all shadow-sm flex items-center justify-center" {{ $item->status == 'Approved' ? 'disabled opacity-50 cursor-not-allowed' : '' }}>
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium"><i class="fas fa-user-clock text-4xl mb-3 block"></i> Belum ada data presensi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <x-modal name="add-presensi-modal" focusable>
        <form action="{{ route('indie.kepala-kantor.lap-presensi.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
            @csrf
            <div class="bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                <h3 class="text-white font-bold text-lg">Input Laporan Presensi</h3>
                <button type="button" x-on:click="$dispatch('close')" class="text-indigo-200 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-5">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Periode Laporan</label>
                        <select name="periode_bulan" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="{{ date('F Y') }}">Bulan Ini ({{ date('F Y') }})</option>
                            <option value="{{ date('F Y', strtotime('-1 month')) }}">Bulan Lalu ({{ date('F Y', strtotime('-1 month')) }})</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Total Pegawai</label>
                        <input type="number" name="total_pegawai" value="0" min="1" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500 font-bold" required>
                    </div>
                </div>
                
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-3">Rincian Kehadiran (Total Hari/Pegawai)</label>
                    <div class="grid grid-cols-4 gap-3">
                        <div>
                            <span class="block text-[10px] text-emerald-600 font-bold mb-1"><i class="fas fa-check-circle"></i> Hadir</span>
                            <input type="number" name="hadir" value="0" min="0" class="w-full border-slate-200 rounded-lg text-sm text-center" required>
                        </div>
                        <div>
                            <span class="block text-[10px] text-amber-600 font-bold mb-1"><i class="fas fa-notes-medical"></i> Sakit</span>
                            <input type="number" name="sakit" value="0" min="0" class="w-full border-slate-200 rounded-lg text-sm text-center" required>
                        </div>
                        <div>
                            <span class="block text-[10px] text-blue-600 font-bold mb-1"><i class="fas fa-envelope"></i> Izin</span>
                            <input type="number" name="izin" value="0" min="0" class="w-full border-slate-200 rounded-lg text-sm text-center" required>
                        </div>
                        <div>
                            <span class="block text-[10px] text-red-600 font-bold mb-1"><i class="fas fa-times-circle"></i> Alpa</span>
                            <input type="number" name="alpa" value="0" min="0" class="w-full border-slate-200 rounded-lg text-sm text-center" required>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Catatan Tambahan</label>
                    <textarea name="keterangan" rows="2" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Opsional..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Upload Data Absensi Mesin (Excel/PDF)</label>
                    <input type="file" name="dokumen_lampiran" class="w-full border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".pdf,.xls,.xlsx" required>
                </div>

            </div>
            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl border-t border-slate-100">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700">Submit Presensi</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>