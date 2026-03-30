<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Arsip Bukti Transaksi</h2>
            <p class="text-slate-500 font-medium">Digitalisasi dan penyimpanan dokumen keuangan kantor cabang.</p>
        </div>
        
        <button x-data @click="$dispatch('open-modal', 'add-arsip-modal')" 
            class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-cloud-upload-alt"></i> <span>Unggah Arsip Baru</span>
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

    {{-- KARTU REKAP KATEGORI (Opsional, untuk mempercantik UI) --}}
    <div class="flex gap-4 overflow-x-auto pb-4 mb-4 custom-scrollbar">
        <div class="min-w-[150px] bg-white border border-slate-200 px-4 py-3 rounded-2xl shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center"><i class="fas fa-file-invoice"></i></div>
            <div><p class="text-[10px] font-bold text-slate-400 uppercase">Invoice</p><p class="font-black text-slate-700">{{ $arsips->where('kategori', 'Invoice')->count() }}</p></div>
        </div>
        <div class="min-w-[150px] bg-white border border-slate-200 px-4 py-3 rounded-2xl shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center"><i class="fas fa-receipt"></i></div>
            <div><p class="text-[10px] font-bold text-slate-400 uppercase">Kwitansi</p><p class="font-black text-slate-700">{{ $arsips->where('kategori', 'Kwitansi')->count() }}</p></div>
        </div>
        <div class="min-w-[150px] bg-white border border-slate-200 px-4 py-3 rounded-2xl shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center"><i class="fas fa-file-invoice-dollar"></i></div>
            <div><p class="text-[10px] font-bold text-slate-400 uppercase">Pajak</p><p class="font-black text-slate-700">{{ $arsips->where('kategori', 'Faktur Pajak')->count() }}</p></div>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Kode & Tanggal</th>
                        <th class="px-6 py-4">Kategori & Dokumen</th>
                        <th class="px-6 py-4">Pihak Terkait & Nominal</th>
                        <th class="px-6 py-4 text-center">File Arsip</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($arsips as $item)
                        @php
                            $catColor = match($item->kategori) {
                                'Invoice' => 'bg-blue-100 text-blue-700',
                                'Kwitansi' => 'bg-emerald-100 text-emerald-700',
                                'Faktur Pajak' => 'bg-amber-100 text-amber-700',
                                'Bukti Bayar' => 'bg-purple-100 text-purple-700',
                                default => 'bg-slate-100 text-slate-700',
                            };
                        @endphp
                    <tr class="hover:bg-indigo-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-indigo-600">{{ $item->kode_arsip }}</div>
                            <div class="text-xs text-slate-500 mt-1 font-semibold">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 {{ $catColor }} text-[10px] font-bold rounded-md uppercase mb-2 inline-block">{{ $item->kategori }}</span>
                            <p class="text-sm font-bold text-slate-700">{{ $item->nama_dokumen }}</p>
                            @if($item->keterangan)
                                <p class="text-xs text-slate-500 mt-1 line-clamp-1">Ket: {{ $item->keterangan }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-700"><i class="far fa-building text-slate-400 mr-1"></i> {{ $item->nama_pihak_terkait }}</div>
                            @if($item->nominal > 0)
                                <div class="text-sm font-black text-indigo-600 mt-1">Rp {{ number_format($item->nominal, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ $item->dokumen_lampiran }}" download="{{ $item->kategori }}_{{ $item->nama_pihak_terkait }}" class="w-10 h-10 mx-auto rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm flex flex-col items-center justify-center group" title="Unduh Arsip">
                                <i class="fas fa-file-download text-sm group-hover:-translate-y-0.5 transition-transform"></i>
                            </a>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('indie.arsip-transaksi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus arsip dokumen ini secara permanen?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white transition-all shadow-sm mx-auto flex items-center justify-center">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium"><i class="fas fa-folder-open text-4xl mb-3 block"></i> Belum ada dokumen yang diarsipkan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH ARSIP --}}
    <x-modal name="add-arsip-modal" focusable>
        <form action="{{ route('indie.arsip-transaksi.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
            @csrf
            <div class="bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                <h3 class="text-white font-bold text-lg">Unggah Arsip Dokumen</h3>
                <button type="button" x-on:click="$dispatch('close')" class="text-indigo-200 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-5">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Kategori Dokumen</label>
                        <select name="kategori" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500 font-bold" required>
                            <option value="Invoice">Invoice / Tagihan</option>
                            <option value="Kwitansi">Kwitansi Tanda Terima</option>
                            <option value="Faktur Pajak">Faktur Pajak</option>
                            <option value="Bukti Bayar">Bukti Transfer / Bayar</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal Dokumen</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama / Judul Dokumen</label>
                        <input type="text" name="nama_dokumen" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Contoh: Invoice Sewa Alat Berat Beko...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Vendor / Klien</label>
                        <input type="text" name="nama_pihak_terkait" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Contoh: PT. Maju Jaya">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nominal (Rp) <span class="text-[10px] font-normal italic text-slate-400">- Opsional</span></label>
                        <input type="number" name="nominal" value="0" min="0" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Keterangan Singkat</label>
                    <textarea name="keterangan" rows="2" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Catatan tambahan mengenai dokumen ini..."></textarea>
                </div>

                <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100">
                    <label class="block text-xs font-bold text-indigo-700 uppercase mb-2"><i class="fas fa-file-upload mr-1"></i> Pilih File Arsip (Wajib)</label>
                    <p class="text-[10px] text-indigo-500 mb-2">Format: PDF, JPG, atau PNG. Maksimal ukuran 3MB.</p>
                    <input type="file" name="dokumen_lampiran" class="w-full border border-indigo-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-white file:text-indigo-700 hover:file:bg-indigo-50" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>

            </div>
            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl border-t border-slate-100">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700">Simpan Ke Arsip</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>