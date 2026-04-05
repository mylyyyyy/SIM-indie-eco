<x-admin-layout>
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Hasil Kunjungan Toko</h2>
            <p class="text-sm text-slate-500">Input data setoran dan rincian kunjungan operasional.</p>
        </div>
        
        <div>
            <a href="{{ route('eco.visit-results.export') }}" target="_blank" 
               class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-teal-500/30 transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-file-excel"></i> Download Excel
            </a>
        </div>
    </div>

    @if(session('success'))
        <script>Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });</script>
    @endif
    
    @if($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 border border-red-100 text-sm"><i class="fas fa-exclamation-triangle mr-2"></i> {{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- KOLOM KIRI: Form Input --}}
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-fit sticky top-24">
            <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Input Hasil Kunjungan</h3>
            
            <form action="{{ route('eco.visit-results.store') }}" method="POST" class="space-y-3">
                @csrf
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Hari</label>
                        <select name="hari" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white" required>
                            <option value="Senin">Senin</option><option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option><option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option><option value="Sabtu">Sabtu</option><option value="Minggu">Minggu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Toko</label>
                    <input type="text" name="nama_toko" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" placeholder="Ketik nama toko..." required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Alamat</label>
                    <textarea name="alamat" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" placeholder="Alamat toko..." required></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Sisa Awal</label>
                        <input type="number" min="0" name="titip_sisa_awal_pack" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" value="0" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Harga (Rp)</label>
                        <input type="number" min="0" name="harga_rp" id="harga_rp" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" value="0" oninput="calculateNominal()" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Laku</label>
                        <input type="number" min="0" name="laku_pack" id="laku_pack" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" value="0" oninput="calculateNominal()" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Sisa</label>
                        <input type="number" min="0" name="sisa_pack" id="sisa_pack" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" value="0" oninput="calculateTotal()" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tambah</label>
                        <input type="number" min="0" name="tambah_pack" id="tambah_pack" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" value="0" oninput="calculateTotal()" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Total Pack</label>
                        <input type="number" min="0" name="total_pack" id="total_pack" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-slate-100 font-bold" value="0" readonly required>
                    </div>
                </div>

                {{-- KOLOM RETURN --}}
                <div class="bg-red-50 p-3 rounded-lg border border-red-100">
                    <label class="block text-xs font-bold text-red-600 uppercase mb-1">Jumlah Beras Return</label>
                    <input type="number" min="0" name="return_pack" class="w-full px-3 py-2 border border-red-200 rounded-lg text-sm focus:ring-red-500 font-bold text-red-700" value="0" required>
                </div>

                {{-- KOLOM NOMINAL (OTOMATIS) --}}
                <div class="bg-amber-50 p-3 rounded-lg border border-amber-100">
                    <label class="block text-xs font-bold text-amber-700 uppercase mb-1">Nominal Pembayaran</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 font-bold text-amber-700">Rp</span>
                        <input type="text" id="nominal_tampil" class="w-full pl-9 pr-3 py-2 border border-amber-200 rounded-lg text-sm bg-amber-100/50 font-black text-amber-800" value="0" readonly>
                    </div>
                    <p class="text-[10px] text-amber-600 mt-1 italic">*Dihitung otomatis (Harga x Laku)</p>
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg shadow mt-2">Simpan Data</button>
            </form>
            
            <script>
                function calculateTotal() {
                    let sisa = parseInt(document.getElementById('sisa_pack').value) || 0;
                    let tambah = parseInt(document.getElementById('tambah_pack').value) || 0;
                    document.getElementById('total_pack').value = sisa + tambah;
                }
                function calculateNominal() {
                    let harga = parseInt(document.getElementById('harga_rp').value) || 0;
                    let laku = parseInt(document.getElementById('laku_pack').value) || 0;
                    let nominal = harga * laku;
                    document.getElementById('nominal_tampil').value = new Intl.NumberFormat('id-ID').format(nominal);
                }

                // Untuk Modal Edit
                function calculateTotalEdit(id) {
                    let sisa = parseInt(document.getElementById('edit_sisa_pack_' + id).value) || 0;
                    let tambah = parseInt(document.getElementById('edit_tambah_pack_' + id).value) || 0;
                    document.getElementById('edit_total_pack_' + id).value = sisa + tambah;
                }
                function calculateNominalEdit(id) {
                    let harga = parseInt(document.getElementById('edit_harga_rp_' + id).value) || 0;
                    let laku = parseInt(document.getElementById('edit_laku_pack_' + id).value) || 0;
                    let nominal = harga * laku;
                    document.getElementById('edit_nominal_tampil_' + id).value = new Intl.NumberFormat('id-ID').format(nominal);
                }
            </script>
        </div>

        {{-- KOLOM KANAN: Tabel Data --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Toko & Alamat</th>
                            <th class="px-4 py-3 text-center">Data Pack</th>
                            <th class="px-4 py-3 text-center">Nominal & Return</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($results as $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 align-top">
                                <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</div>
                                <div class="text-[10px] text-slate-500 uppercase">{{ $item->hari }}</div>
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="font-bold text-slate-700">{{ $item->nama_toko }}</div>
                                <div class="text-[10px] text-slate-400 truncate max-w-[150px]" title="{{ $item->alamat }}">{{ $item->alamat }}</div>
                            </td>
                            <td class="px-4 py-3 text-center align-top">
                                <div class="text-xs text-slate-500 mb-1">Laku: <span class="font-bold text-emerald-600">{{ $item->laku_pack }}</span></div>
                                <div class="text-[10px] text-slate-400">Total: {{ $item->total_pack }}</div>
                            </td>
                            <td class="px-4 py-3 text-center align-top">
                                <div class="font-bold text-amber-600 mb-1">Rp {{ number_format($item->nominal_pembayaran, 0, ',', '.') }}</div>
                                @if($item->return_pack > 0)
                                    <div class="bg-red-50 text-red-600 font-bold px-2 py-0.5 rounded text-[10px] inline-block">Return: {{ $item->return_pack }} Pack</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center align-top">
                                <div class="flex items-center justify-center gap-1">
                                    <button x-data @click="$dispatch('open-modal', 'edit-visit-{{ $item->id }}')" class="text-amber-500 hover:bg-amber-50 p-1.5 rounded" title="Edit Data"><i class="fas fa-edit text-xs"></i></button>

                                    <form action="{{ route('eco.visit-results.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:bg-red-50 p-1.5 rounded transition-colors"><i class="fas fa-trash text-xs"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL EDIT DATA KUNJUNGAN --}}
                        <x-modal name="edit-visit-{{ $item->id }}" focusable>
                            <form action="{{ route('eco.visit-results.update', $item->id) }}" method="POST" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
                                @csrf @method('PUT')
                                <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                                    <h3 class="text-white font-bold text-lg">Edit Data Kunjungan</h3>
                                    <button type="button" x-on:click="$dispatch('close')" class="text-emerald-100 hover:text-white"><i class="fas fa-times text-xl"></i></button>
                                </div>
                                <div class="p-6 overflow-y-auto custom-scrollbar space-y-4 text-left">
                                    
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Hari</label>
                                            <select name="hari" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white" required>
                                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                                                    <option value="{{ $h }}" {{ $item->hari == $h ? 'selected' : '' }}>{{ $h }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                                            <input type="date" name="tanggal" value="{{ $item->tanggal }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" required>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Toko</label>
                                        <input type="text" name="nama_toko" value="{{ $item->nama_toko }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" required>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Alamat</label>
                                        <textarea name="alamat" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" required>{{ $item->alamat }}</textarea>
                                    </div>

                                    <div class="grid grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Sisa Awal</label>
                                            <input type="number" min="0" name="titip_sisa_awal_pack" value="{{ $item->titip_sisa_awal_pack }}" class="w-full px-2 py-2 border border-slate-200 rounded text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Harga (Rp)</label>
                                            <input type="number" min="0" name="harga_rp" id="edit_harga_rp_{{ $item->id }}" value="{{ $item->harga_rp }}" oninput="calculateNominalEdit({{ $item->id }})" class="w-full px-2 py-2 border border-slate-200 rounded text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Laku</label>
                                            <input type="number" min="0" name="laku_pack" id="edit_laku_pack_{{ $item->id }}" value="{{ $item->laku_pack }}" oninput="calculateNominalEdit({{ $item->id }})" class="w-full px-2 py-2 border border-slate-200 rounded text-sm text-emerald-600 font-bold">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Sisa</label>
                                            <input type="number" min="0" name="sisa_pack" id="edit_sisa_pack_{{ $item->id }}" value="{{ $item->sisa_pack }}" oninput="calculateTotalEdit({{ $item->id }})" class="w-full px-2 py-2 border border-slate-200 rounded text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Tambah</label>
                                            <input type="number" min="0" name="tambah_pack" id="edit_tambah_pack_{{ $item->id }}" value="{{ $item->tambah_pack }}" oninput="calculateTotalEdit({{ $item->id }})" class="w-full px-2 py-2 border border-slate-200 rounded text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Total</label>
                                            <input type="number" min="0" name="total_pack" id="edit_total_pack_{{ $item->id }}" value="{{ $item->total_pack }}" class="w-full px-2 py-2 border border-slate-200 rounded text-sm bg-slate-100 font-bold" readonly>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="bg-red-50 p-2 rounded border border-red-100">
                                            <label class="block text-[10px] font-bold text-red-600 uppercase mb-1">Return</label>
                                            <input type="number" min="0" name="return_pack" value="{{ $item->return_pack }}" class="w-full px-2 py-1.5 border border-red-200 rounded text-sm text-red-700 font-bold">
                                        </div>
                                        <div class="bg-amber-50 p-2 rounded border border-amber-100">
                                            <label class="block text-[10px] font-bold text-amber-700 uppercase mb-1">Nominal (Otomatis)</label>
                                            <input type="text" id="edit_nominal_tampil_{{ $item->id }}" value="{{ number_format($item->nominal_pembayaran, 0, ',', '.') }}" class="w-full px-2 py-1.5 border border-amber-200 rounded text-sm bg-amber-100/50 font-bold text-amber-800" readonly>
                                        </div>
                                    </div>

                                </div>
                                <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl border-t border-slate-100">
                                    <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-bold">Batal</button>
                                    <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-lg text-sm font-bold">Simpan</button>
                                </div>
                            </form>
                        </x-modal>

                        @empty
                        <tr><td colspan="5" class="text-center py-8 text-slate-400">Belum ada data kunjungan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>