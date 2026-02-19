<x-admin-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Rekap Data Mitra Toko</h2>
            <p class="text-sm text-slate-500">Kelola daftar toko mitra untuk keperluan distribusi.</p>
        </div>
        <div>
            <a href="{{ route('eco.store-partners.export') }}" target="_blank" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl font-bold text-sm shadow-md transition-all flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> Unduh PDF Laporan
            </a>
        </div>
    </div>

    @if(session('success'))
        <script>
            Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false });
        </script>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Form Input (1/3 Lebar) --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Tambah Mitra Baru</h3>
                
                {{-- PERHATIKAN: enctype="multipart/form-data" wajib ada untuk upload file/foto --}}
                <form action="{{ route('eco.store-partners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal Update</label>
                        <input type="date" name="tanggal_update" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" required>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kode Toko</label>
                            <input type="text" name="kode_toko" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" placeholder="Contoh: TK-001" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kantor Cabang</label>
                            <input type="text" name="kantor_cabang" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" placeholder="Contoh: Sidoarjo" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Toko</label>
                        <input type="text" name="nama_toko" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Pemilik</label>
                        <input type="text" name="nama_pemilik" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">No. Telepon</label>
                        <input type="text" name="no_telp" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500" placeholder="08...">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Status Toko</label>
                        <select name="catatan_status" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 bg-white" required>
                            <option value="aktif">Aktif</option>
                            <option value="tidak aktif">Tidak Aktif</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Foto Toko (Opsional)</label>
                        <input type="file" name="foto_toko" accept="image/*" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg shadow transition-all mt-2">
                        Simpan Data
                    </button>
                </form>
            </div>
        </div>

        {{-- Tabel Data (2/3 Lebar) --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold">
                            <tr>
                                <th class="px-4 py-3">Info Toko</th>
                                <th class="px-4 py-3">Pemilik & Kontak</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($partners as $partner)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 flex items-center gap-3">
                                    {{-- Tampilkan Foto jika ada, jika tidak pakai icon --}}
                                    @if($partner->foto_toko)
                                        <img src="{{ asset('uploads/stores/' . $partner->foto_toko) }}" alt="Foto" class="w-12 h-12 rounded-lg object-cover border border-slate-200 shadow-sm">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 border border-slate-200 shadow-sm">
                                            <i class="fas fa-store"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $partner->nama_toko }}</div>
                                        <div class="text-[10px] text-slate-500">Kode: {{ $partner->kode_toko }} • Cabang: {{ $partner->kantor_cabang }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-700">{{ $partner->nama_pemilik }}</div>
                                    <div class="text-xs text-slate-400"><i class="fas fa-phone mr-1"></i> {{ $partner->no_telp ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($partner->catatan_status == 'aktif')
                                        <span class="bg-emerald-50 text-emerald-600 px-2 py-1 rounded font-bold text-xs">Aktif</span>
                                    @else
                                        <span class="bg-red-50 text-red-600 px-2 py-1 rounded font-bold text-xs">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('eco.store-partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Hapus toko mitra ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-slate-400">Belum ada data toko mitra.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>