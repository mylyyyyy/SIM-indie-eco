<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Progres Fisik Proyek</h2>
            <p class="text-slate-500 font-medium">Laporan kemajuan fisik pekerjaan lapangan (Target vs Realisasi).</p>
        </div>
        
        <button x-data @click="$dispatch('open-modal', 'add-progres-modal')" 
            class="bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-sky-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-plus-circle"></i> <span>Input Progres Baru</span>
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
                        <th class="px-6 py-4">Kode & Tanggal</th>
                        <th class="px-6 py-4">Pekerjaan & Kendala</th>
                        <th class="px-6 py-4 min-w-[200px]">Pencapaian (%)</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Dokumentasi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($progres as $item)
                        @php
                            $target = $item->data_spesifik['target_progres'] ?? 0;
                            $realisasi = $item->data_spesifik['realisasi_progres'] ?? 0;
                            $deviasi = $realisasi - $target;
                            $devColor = $deviasi >= 0 ? 'text-emerald-500' : 'text-red-500';
                            $devIcon = $deviasi >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                        @endphp
                    <tr class="hover:bg-sky-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->tanggal_periode)->format('d M Y') }}</div>
                            <div class="text-[10px] text-sky-500 font-mono mt-1">{{ $item->kode_referensi }}</div>
                            <div class="text-[10px] font-bold text-slate-400 mt-1 bg-slate-100 px-2 py-0.5 rounded inline-block">Minggu Ke-{{ $item->data_spesifik['minggu_ke'] ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-700 line-clamp-2">{{ $item->keterangan_umum }}</p>
                            @if(!empty($item->data_spesifik['kendala_utama']))
                                <p class="text-xs text-red-500 mt-1 bg-red-50 p-1.5 rounded border border-red-100"><i class="fas fa-exclamation-triangle mr-1"></i> {{ $item->data_spesifik['kendala_utama'] }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            {{-- Bar Target --}}
                            <div class="flex justify-between text-[10px] font-bold text-slate-400 mb-1">
                                <span>Target: {{ $target }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 mb-2">
                                <div class="bg-slate-300 h-1.5 rounded-full" style="width: {{ $target }}%"></div>
                            </div>
                            
                            {{-- Bar Realisasi --}}
                            <div class="flex justify-between text-[10px] font-bold text-slate-700 mb-1">
                                <span>Realisasi: {{ $realisasi }}%</span>
                                <span class="{{ $devColor }}"><i class="fas {{ $devIcon }}"></i> {{ abs($deviasi) }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="{{ $deviasi >= 0 ? 'bg-emerald-500' : 'bg-red-500' }} h-2 rounded-full" style="width: {{ $realisasi }}%"></div>
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
                                <a href="{{ $item->dokumen_lampiran }}" download="ProgresFisik_{{ $item->kode_referensi }}" class="w-8 h-8 mx-auto rounded-lg bg-sky-50 border border-sky-100 text-sky-600 hover:bg-sky-600 hover:text-white transition-all shadow-sm flex items-center justify-center group" title="Unduh Foto Progres">
                                    <i class="fas fa-camera group-hover:scale-110 transition-transform"></i>
                                </a>
                            @else
                                <span class="text-xs text-slate-400 italic">Kosong</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('indie.admin-lapangan.progres-fisik.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data progres ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white transition-all shadow-sm mx-auto flex items-center justify-center" {{ $item->status == 'Approved' ? 'disabled opacity-50 cursor-not-allowed' : '' }}>
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium"><i class="fas fa-hard-hat text-4xl mb-3 block"></i> Belum ada data progres fisik.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH PROGRES --}}
    <x-modal name="add-progres-modal" focusable>
        <form action="{{ route('indie.admin-lapangan.progres-fisik.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
            @csrf
            <div class="bg-gradient-to-r from-sky-500 to-blue-600 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                <h3 class="text-white font-bold text-lg">Input Progres Fisik Baru</h3>
                <button type="button" x-on:click="$dispatch('close')" class="text-sky-100 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-5">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal Laporan</label>
                        <input type="date" name="tanggal_laporan" value="{{ date('Y-m-d') }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Laporan Minggu Ke-</label>
                        <input type="number" name="minggu_ke" value="1" min="1" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500 font-bold" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Target Progres (%)</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="target_progres" value="0" min="0" max="100" class="w-full pr-8 border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500 font-bold text-slate-600" required>
                            <i class="fas fa-percent absolute right-4 top-3 text-slate-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Realisasi Progres (%)</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="realisasi_progres" value="0" min="0" max="100" class="w-full pr-8 border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500 font-black text-emerald-600" required>
                            <i class="fas fa-percent absolute right-4 top-3 text-emerald-400"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Deskripsi Pekerjaan Terlaksana</label>
                    <textarea name="keterangan_umum" rows="2" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500" required placeholder="Contoh: Pengecoran tiang pancang sektor barat..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-red-500 uppercase mb-2">Kendala Utama (Jika Ada)</label>
                    <textarea name="kendala_utama" rows="2" class="w-full border-red-200 rounded-xl text-sm focus:border-red-500 focus:ring-red-500 bg-red-50/30" placeholder="Keterlambatan material, faktor cuaca, dll..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Upload Dokumentasi Foto (PDF/JPG/PNG)</label>
                    <input type="file" name="dokumen_lampiran" class="w-full border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>

            </div>
            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl border-t border-slate-100">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-sky-600 text-white rounded-xl text-sm font-bold hover:bg-sky-700">Simpan Progres</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>