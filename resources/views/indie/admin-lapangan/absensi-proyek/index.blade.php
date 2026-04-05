<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Log Absensi Proyek</h2>
            <p class="text-slate-500 font-medium">Rekapitulasi harian tenaga kerja dan jam lembur (overtime).</p>
        </div>
        
        <button x-data @click="$dispatch('open-modal', 'add-absensi-modal')" 
            class="bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-sky-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-plus-circle"></i> <span>Input Absensi</span>
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
                        <th class="px-6 py-4">Tgl & Kode</th>
                        <th class="px-6 py-4">Total Pekerja (Naker)</th>
                        <th class="px-6 py-4">Status Kehadiran</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Lampiran</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($absensis as $item)
                        @php
                            $total = $item->data_spesifik['total_pekerja'] ?? 0;
                            $hadir = $item->data_spesifik['hadir'] ?? 0;
                            $lembur = $item->data_spesifik['lembur'] ?? 0;
                            $absen = $item->data_spesifik['absen'] ?? 0;
                        @endphp
                    <tr class="hover:bg-sky-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->tanggal_periode)->format('d M Y') }}</div>
                            <div class="text-[10px] text-sky-500 font-mono mt-1">{{ $item->kode_referensi }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-black text-2xl text-slate-700">{{ $total }}</span> <span class="text-xs font-bold text-slate-400">Orang</span>
                            @if($item->keterangan_umum)
                                <p class="text-[10px] text-slate-500 mt-1 line-clamp-1 italic" title="{{ $item->keterangan_umum }}">Ket: {{ $item->keterangan_umum }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <div class="bg-emerald-50 border border-emerald-100 px-2 py-1 rounded text-center min-w-[50px]">
                                    <span class="block text-[9px] font-bold text-emerald-600 uppercase">Hadir</span>
                                    <span class="font-black text-slate-700">{{ $hadir }}</span>
                                </div>
                                <div class="bg-amber-50 border border-amber-100 px-2 py-1 rounded text-center min-w-[50px]">
                                    <span class="block text-[9px] font-bold text-amber-600 uppercase">Lembur</span>
                                    <span class="font-black text-slate-700">{{ $lembur }}</span>
                                </div>
                                <div class="bg-red-50 border border-red-100 px-2 py-1 rounded text-center min-w-[50px]">
                                    <span class="block text-[9px] font-bold text-red-600 uppercase">Absen</span>
                                    <span class="font-black text-slate-700">{{ $absen }}</span>
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
                                <a href="{{ $item->dokumen_lampiran }}" download="Absen_{{ $item->kode_referensi }}" class="w-8 h-8 mx-auto rounded-lg bg-sky-50 border border-sky-100 text-sky-600 hover:bg-sky-600 hover:text-white transition-all shadow-sm flex items-center justify-center group" title="Unduh Log Absen">
                                    <i class="fas fa-file-download group-hover:scale-110 transition-transform"></i>
                                </a>
                            @else
                                <span class="text-xs text-slate-400 italic">Kosong</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('indie.admin-lapangan.absensi-proyek.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data absensi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white transition-all shadow-sm mx-auto flex items-center justify-center" {{ $item->status == 'Approved' ? 'disabled opacity-50 cursor-not-allowed' : '' }}>
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium"><i class="fas fa-user-friends text-4xl mb-3 block"></i> Belum ada data absensi pekerja.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH ABSENSI --}}
    <x-modal name="add-absensi-modal" focusable>
        <form action="{{ route('indie.admin-lapangan.absensi-proyek.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
            @csrf
            <div class="bg-gradient-to-r from-sky-500 to-blue-600 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                <h3 class="text-white font-bold text-lg">Input Log Absensi</h3>
                <button type="button" x-on:click="$dispatch('close')" class="text-sky-100 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-5">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal Absen</label>
                        <input type="date" name="tanggal_absen" value="{{ date('Y-m-d') }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Total Pekerja Terdaftar</label>
                        <input type="number" name="total_pekerja" value="0" min="1" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500 font-bold" required>
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-3">Rincian Kehadiran Naker</label>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <span class="block text-[10px] text-emerald-600 font-bold mb-1"><i class="fas fa-check-circle"></i> Hadir Normal</span>
                            <input type="number" name="hadir" value="0" min="0" class="w-full border-slate-200 rounded-lg text-sm text-center font-bold" required>
                        </div>
                        <div>
                            <span class="block text-[10px] text-amber-600 font-bold mb-1"><i class="fas fa-clock"></i> Ikut Lembur</span>
                            <input type="number" name="lembur" value="0" min="0" class="w-full border-slate-200 rounded-lg text-sm text-center font-bold" required>
                        </div>
                        <div>
                            <span class="block text-[10px] text-red-600 font-bold mb-1"><i class="fas fa-times-circle"></i> Absen / Izin</span>
                            <input type="number" name="absen" value="0" min="0" class="w-full border-slate-200 rounded-lg text-sm text-center font-bold" required>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Catatan Lapangan</label>
                    <textarea name="keterangan_umum" rows="2" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Pekerja shift malam, tukang izin, dll..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Upload Lampiran (Foto Absen/Excel Mandor)</label>
                    <input type="file" name="dokumen_lampiran" class="w-full border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100" accept=".pdf,.jpg,.jpeg,.png,.xls,.xlsx" required>
                </div>

            </div>
            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl border-t border-slate-100">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-sky-600 text-white rounded-xl text-sm font-bold hover:bg-sky-700">Simpan Log Absensi</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>