<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Laporan Operasional Kantor</h2>
            <p class="text-slate-500 font-medium">Rekapitulasi ringkasan kegiatan operasional cabang Anda.</p>
        </div>
        
        <button x-data @click="$dispatch('open-modal', 'add-lap-modal')" 
            class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-plus-circle"></i> <span>Buat Laporan</span>
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
                        <th class="px-6 py-4">Ringkasan Kegiatan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Lampiran</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($laporans as $item)
                    <tr class="hover:bg-indigo-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-indigo-600">{{ $item->kode_laporan }}</div>
                            <div class="text-xs text-slate-500 mt-1 font-semibold">{{ $item->periode_bulan }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">Input: {{ $item->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-600 line-clamp-2">{{ $item->ringkasan_kegiatan }}</p>
                            @if($item->catatan_evaluator)
                                <div class="mt-2 text-xs bg-amber-50 text-amber-700 p-2 rounded border border-amber-100">
                                    <strong>Catatan Atasan:</strong> {{ $item->catatan_evaluator }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status == 'Approved')
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-md uppercase">Approved</span>
                            @elseif($item->status == 'Revisi')
                                <span class="px-2.5 py-1 bg-red-100 text-red-700 text-[10px] font-bold rounded-md uppercase">Revisi</span>
                            @else
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-md uppercase">Submit</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->dokumen_lampiran)
                                {{-- Gunakan atribut "download" agar Base64 terunduh otomatis sebagai file --}}
                                <a href="{{ $item->dokumen_lampiran }}" download="Lampiran_{{ $item->kode_laporan }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm" title="Unduh Lampiran">
                                    <i class="fas fa-file-download"></i>
                                </a>
                            @else
                                <span class="text-xs text-slate-400 italic">Kosong</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('indie.kepala-kantor.lap-operasional.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus laporan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white transition-all shadow-sm" {{ $item->status == 'Approved' ? 'disabled' : '' }}>
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium"><i class="fas fa-folder-open text-4xl mb-3 block"></i> Belum ada data laporan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <x-modal name="add-lap-modal" focusable>
        <form action="{{ route('indie.kepala-kantor.lap-operasional.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
            @csrf
            <div class="bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                <h3 class="text-white font-bold text-lg">Buat Laporan Operasional</h3>
                <button type="button" x-on:click="$dispatch('close')" class="text-indigo-200 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-5">
                
                {{-- Data Otomatis Sesuai Standar --}}
                <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <div><span class="block text-[10px] text-slate-400 font-bold uppercase">Nama Pegawai</span><span class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</span></div>
                    <div><span class="block text-[10px] text-slate-400 font-bold uppercase">Jabatan / Role</span><span class="text-sm font-semibold text-slate-700">Kepala Kantor</span></div>
                    <div class="col-span-2"><span class="block text-[10px] text-slate-400 font-bold uppercase">Cabang / Unit</span><span class="text-sm font-semibold text-slate-700">{{ Auth::user()->company_name ?? 'Pusat' }}</span></div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Periode Laporan</label>
                    <select name="periode_bulan" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="{{ date('F Y') }}">Bulan Ini ({{ date('F Y') }})</option>
                        <option value="{{ date('F Y', strtotime('-1 month')) }}">Bulan Lalu ({{ date('F Y', strtotime('-1 month')) }})</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Ringkasan Kegiatan</label>
                    <textarea name="ringkasan_kegiatan" rows="4" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Tuliskan ringkasan operasional bulan ini..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Kendala (Opsional)</label>
                    <textarea name="kendala_hambatan" rows="2" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Hambatan yang terjadi di lapangan..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Lampiran Dokumen (PDF/JPG/PNG max 2MB)</label>
                    <input type="file" name="dokumen_lampiran" class="w-full border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

            </div>
            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl border-t border-slate-100">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700">Kirim Laporan</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>