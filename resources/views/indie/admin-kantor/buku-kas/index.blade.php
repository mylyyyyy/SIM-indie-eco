<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Buku Kas Harian</h2>
            <p class="text-slate-500 font-medium">Catatan pemasukan dan pengeluaran operasional cabang.</p>
        </div>
        
        <button x-data @click="$dispatch('open-modal', 'add-kas-modal')" 
            class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-plus-circle"></i> <span>Tambah Transaksi</span>
        </button>
    </div>

    {{-- KARTU SALDO (WIDGET KEUANGAN) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Pemasukan --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-emerald-100 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full z-0 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-emerald-500 uppercase tracking-widest mb-1">Total Pemasukan</p>
                    <h3 class="text-2xl font-black text-slate-800">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</h3>
                </div>
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                    <i class="fas fa-arrow-down"></i>
                </div>
            </div>
        </div>

        {{-- Pengeluaran --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-red-100 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full z-0 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-red-500 uppercase tracking-widest mb-1">Total Pengeluaran</p>
                    <h3 class="text-2xl font-black text-slate-800">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</h3>
                </div>
                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
        </div>

        {{-- Saldo Akhir --}}
        <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-3xl p-6 shadow-lg shadow-blue-500/30 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full z-0"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-indigo-200 uppercase tracking-widest mb-1">Saldo Kas Saat Ini</p>
                    <h3 class="text-3xl font-black text-white">Rp {{ number_format($saldo_akhir, 0, ',', '.') }}</h3>
                </div>
                <div class="w-12 h-12 bg-white/20 text-white rounded-2xl flex items-center justify-center text-xl backdrop-blur-sm border border-white/20">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
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
                        <th class="px-6 py-4">Tanggal & Kode</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4 text-right">Nominal (Rp)</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Bukti</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kas_items as $item)
                    <tr class="hover:bg-indigo-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</div>
                            <div class="text-[10px] text-indigo-500 font-mono mt-1">{{ $item->kode_transaksi }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-600 line-clamp-2">{{ $item->keterangan }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($item->jenis_transaksi == 'Pemasukan')
                                <span class="font-black text-emerald-600">+ {{ number_format($item->nominal, 0, ',', '.') }}</span>
                            @else
                                <span class="font-black text-red-600">- {{ number_format($item->nominal, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status == 'Approved')
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-md uppercase">Approved</span>
                            @elseif($item->status == 'Revisi')
                                <span class="px-2 py-1 bg-red-100 text-red-700 text-[10px] font-bold rounded-md uppercase">Revisi</span>
                            @else
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-md uppercase">Submit</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->dokumen_lampiran)
                                <a href="{{ $item->dokumen_lampiran }}" download="Bukti_{{ $item->kode_transaksi }}" class="w-8 h-8 mx-auto rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm flex items-center justify-center" title="Unduh Bukti">
                                    <i class="fas fa-receipt"></i>
                                </a>
                            @else
                                <span class="text-xs text-slate-400 italic">Kosong</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('indie.buku-kas.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white transition-all shadow-sm mx-auto flex items-center justify-center" {{ $item->status == 'Approved' ? 'disabled opacity-50 cursor-not-allowed' : '' }}>
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium"><i class="fas fa-book-open text-4xl mb-3 block"></i> Belum ada transaksi kas yang dicatat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH TRANSAKSI --}}
    <x-modal name="add-kas-modal" focusable>
        <form action="{{ route('indie.buku-kas.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
            @csrf
            <div class="bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                <h3 class="text-white font-bold text-lg">Catat Transaksi Kas</h3>
                <button type="button" x-on:click="$dispatch('close')" class="text-indigo-200 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-5">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jenis Transaksi</label>
                        <select name="jenis_transaksi" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500 font-bold" required>
                            <option value="Pemasukan" class="text-emerald-600">Pemasukan (Uang Masuk)</option>
                            <option value="Pengeluaran" class="text-red-600">Pengeluaran (Uang Keluar)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nominal (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-slate-400 font-bold">Rp</span>
                        <input type="number" name="nominal" value="0" min="1" class="w-full pl-10 pr-4 border-slate-200 rounded-xl text-lg focus:border-indigo-500 focus:ring-indigo-500 font-black text-slate-800" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Keterangan / Uraian Transaksi</label>
                    <textarea name="keterangan" rows="3" class="w-full border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Contoh: Pembelian tinta printer dan kertas HVS..."></textarea>
                </div>

                <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100">
                    <label class="block text-xs font-bold text-indigo-700 uppercase mb-2"><i class="fas fa-camera mr-1"></i> Upload Bukti Transaksi / Nota</label>
                    <input type="file" name="dokumen_lampiran" class="w-full border border-indigo-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-white file:text-indigo-700 hover:file:bg-indigo-50" accept=".pdf,.jpg,.jpeg,.png">
                </div>

            </div>
            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl border-t border-slate-100">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700">Simpan Transaksi</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>