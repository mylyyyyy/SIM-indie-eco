<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Survey Bahan Material</h2>
            <p class="text-slate-500 font-medium">Catat perbandingan harga material dari berbagai toko/vendor.</p>
        </div>
        
        <button x-data @click="$dispatch('open-modal', 'add-survey-modal')" 
            class="bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-sky-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-plus-circle"></i> <span>Input Hasil Survey</span>
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
                        <th class="px-6 py-4">Material & Harga</th>
                        <th class="px-6 py-4">Toko / Vendor</th>
                        <th class="px-6 py-4 text-center">Lampiran Foto</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($surveys as $item)
                    <tr class="hover:bg-sky-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->tanggal_periode)->format('d M Y') }}</div>
                            <div class="text-[10px] text-sky-500 font-mono mt-1">{{ $item->kode_referensi }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-700">{{ $item->data_spesifik['nama_material'] ?? '-' }}</p>
                            <div class="text-emerald-600 font-black mt-1">
                                Rp {{ number_format($item->data_spesifik['harga_satuan'] ?? 0, 0, ',', '.') }} <span class="text-xs text-slate-400 font-normal">/ {{ $item->data_spesifik['satuan'] ?? '-' }}</span>
                            </div>
                            @if($item->keterangan_umum)
                                <p class="text-[10px] text-slate-500 mt-1 line-clamp-1 italic">Catatan: {{ $item->keterangan_umum }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-700"><i class="fas fa-store text-slate-400 mr-1 w-4"></i> {{ $item->data_spesifik['nama_toko'] ?? '-' }}</div>
                            <div class="text-xs text-slate-500 mt-1"><i class="fas fa-map-marker-alt text-slate-400 mr-1 w-4"></i> {{ $item->data_spesifik['lokasi_toko'] ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->dokumen_lampiran)
                                <a href="{{ $item->dokumen_lampiran }}" download="Survey_{{ $item->kode_referensi }}" class="w-8 h-8 mx-auto rounded-lg bg-sky-50 border border-sky-100 text-sky-600 hover:bg-sky-600 hover:text-white transition-all shadow-sm flex items-center justify-center group" title="Unduh Foto Bukti">
                                    <i class="fas fa-image group-hover:scale-110 transition-transform"></i>
                                </a>
                            @else
                                <span class="text-xs text-slate-400 italic">Kosong</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('indie.admin-lapangan.survey-bahan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data survey ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white transition-all shadow-sm mx-auto flex items-center justify-center">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium"><i class="fas fa-clipboard-list text-4xl mb-3 block"></i> Belum ada data survey bahan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH SURVEY --}}
    <x-modal name="add-survey-modal" focusable>
        <form action="{{ route('indie.admin-lapangan.survey-bahan.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
            @csrf
            <div class="bg-gradient-to-r from-sky-500 to-blue-600 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                <h3 class="text-white font-bold text-lg">Input Survey Harga Bahan</h3>
                <button type="button" x-on:click="$dispatch('close')" class="text-sky-100 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-5">
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal Survey</label>
                    <input type="date" name="tanggal_survey" value="{{ date('Y-m-d') }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Toko / Vendor</label>
                        <input type="text" name="nama_toko" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500" required placeholder="Contoh: TB. Makmur Jaya">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Lokasi / Alamat Singkat</label>
                        <input type="text" name="lokasi_toko" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500" required placeholder="Contoh: Jl. Ahmad Yani No 10">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Nama Material</label>
                        <input type="text" name="nama_material" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500" required placeholder="Contoh: Semen Gresik 40kg">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Harga Satuan (Rp)</label>
                        <input type="number" name="harga_satuan" value="0" min="0" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500 font-bold text-emerald-600" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Satuan</label>
                        <input type="text" name="satuan" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500" required placeholder="Cth: Sak / M3 / Pcs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Catatan Tambahan</label>
                    <textarea name="keterangan_umum" rows="2" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Ketersediaan stok, ongkir, dll..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Upload Foto Harga / Nota (Opsional)</label>
                    <input type="file" name="dokumen_lampiran" class="w-full border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100" accept=".pdf,.jpg,.jpeg,.png">
                </div>

            </div>
            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl border-t border-slate-100">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-sky-600 text-white rounded-xl text-sm font-bold hover:bg-sky-700">Simpan Survey</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>