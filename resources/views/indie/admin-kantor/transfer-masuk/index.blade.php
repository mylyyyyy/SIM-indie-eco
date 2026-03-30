<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Rekap Transfer Masuk</h2>
            <p class="text-slate-500 font-medium">Catat bukti pengiriman dana masuk ke rekening cabang/proyek.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="bg-white border border-emerald-100 rounded-xl px-5 py-2.5 shadow-sm text-right">
                <span class="text-[10px] font-bold text-slate-400 uppercase block mb-0.5">Dana Terkonfirmasi</span>
                <span class="text-lg font-black text-emerald-600">Rp {{ number_format($total_dana_diterima, 0, ',', '.') }}</span>
            </div>

            <button x-data @click="$dispatch('open-modal', 'add-transfer-modal')" 
                class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white h-full px-5 py-3 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-hand-holding-usd"></i> <span class="hidden md:inline">Input Transfer Baru</span>
            </button>
        </div>
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
                        <th class="px-6 py-4">Pengirim & Tujuan</th>
                        <th class="px-6 py-4">Nominal (Rp) & Ket.</th>
                        <th class="px-6 py-4 text-center">Status Mutasi</th>
                        <th class="px-6 py-4 text-center">Bukti</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transfers as $item)
                    <tr class="hover:bg-indigo-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->tanggal_transfer)->format('d M Y') }}</div>
                            <div class="text-[10px] text-indigo-500 font-mono mt-1">{{ $item->kode_transfer }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-700"><i class="fas fa-user text-slate-400 mr-1 w-4"></i> {{ $item->nama_pengirim }}</p>
                            <div class="flex items-center gap-1 mt-1 text-xs text-slate-500">
                                <span class="bg-slate-100 px-1.5 py-0.5 rounded">{{ $item->bank_asal }}</span> 
                                <i class="fas fa-arrow-right text-[8px] text-slate-400"></i> 
                                <span class="bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded font-semibold">{{ $item->bank_tujuan }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-lg font-black text-emerald-600">Rp {{ number_format($item->nominal, 0, ',', '.') }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase mt-0.5">{{ $item->kategori_dana }}</div>
                            @if($item->keterangan)
                                <p class="text-xs text-slate-500 mt-1 line-clamp-1 italic">"{{ $item->keterangan }}"</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status == 'Diterima')
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-md uppercase">Diterima</span>
                            @elseif($item->status == 'Ditolak')
                                <span class="px-2.5 py-1 bg-red-100 text-red-700 text-[10px] font-bold rounded-md uppercase">Ditolak</span>
                            @else
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-md uppercase text-center block whitespace-nowrap"><i class="fas fa-clock mr-1"></i> Menunggu</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ $item->dokumen_lampiran }}" download="BuktiTF_{{ $item->nama_pengirim }}" class="w-8 h-8 mx-auto rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm flex items-center justify-center group" title="Unduh Struk">
                                <i class="fas fa-image group-hover:scale-110 transition-transform"></i>
                            </a>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('indie.transfer-masuk.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus rekap transfer ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white transition-all shadow-sm mx-auto flex items-center justify-center" {{ $item->status == 'Diterima' ? 'disabled opacity-50 cursor-not-allowed' : '' }}>
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium"><i class="fas fa-money-bill-wave text-4xl mb-3 block"></i> Belum ada data transfer uang masuk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH ARSIP --}}
    <x-modal name="add-transfer-modal" focusable>
        <form action="{{ route('indie.transfer-masuk.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
            @csrf
            <div class="bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                <h3 class="text-white font-bold text-lg">Input Bukti Transfer Masuk</h3>
                <button type="button" x-on:click="$dispatch('close')" class="text-indigo-200 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-5">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tgl Transfer</label>
                        <input type="date" name="tanggal_transfer" value="{{ date('Y-m-d') }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Kategori Dana</label>
                        <select name="kategori_dana" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500 font-bold" required>
                            <option value="Pelunasan Proyek">Pelunasan Proyek</option>
                            <option value="DP Proyek">DP / Termin Proyek</option>
                            <option value="Pengembalian Sisa Kas">Pengembalian Sisa Kas</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-3">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Pengirim (Vendor/Klien)</label>
                        <input type="text" name="nama_pengirim" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Contoh: Bpk. Budi Santoso">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Bank Asal</label>
                        <input type="text" name="bank_asal" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Cth: BCA">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Ke Rekening (Bank Tujuan)</label>
                        <input type="text" name="bank_tujuan" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Cth: Mandiri 1420xxxxxx">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nominal Transfer</label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-slate-400 font-bold text-lg">Rp</span>
                        <input type="number" name="nominal" value="0" min="1" class="w-full pl-12 pr-4 border-slate-200 rounded-xl text-lg focus:border-indigo-500 focus:ring-indigo-500 font-black text-emerald-600" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Keterangan / Berita Transfer</label>
                    <textarea name="keterangan" rows="2" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Opsional..."></textarea>
                </div>

                <div class="bg-emerald-50/50 p-4 rounded-xl border border-emerald-100">
                    <label class="block text-xs font-bold text-emerald-700 uppercase mb-2"><i class="fas fa-camera mr-1"></i> Upload Struk / Bukti Transfer (Wajib)</label>
                    <input type="file" name="dokumen_lampiran" class="w-full border border-emerald-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-white file:text-emerald-700 hover:file:bg-emerald-50" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>

            </div>
            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl border-t border-slate-100">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700">Simpan Catatan</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>