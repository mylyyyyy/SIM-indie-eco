<x-admin-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- HEADER PAGE --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Laporan Harian (LH)</h2>
            <p class="text-slate-500 font-medium">Input aktivitas pengerjaan proyek harian Anda di sini.</p>
        </div>
        <button x-data @click="$dispatch('open-modal', 'add-lh-modal')" 
            class="bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-sky-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-edit"></i> <span>Buat Laporan Baru</span>
        </button>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false, toast: true, position: 'top-end' });
            });
        </script>
    @endif

    {{-- TABLE CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Tanggal Laporan</th>
                        <th class="px-6 py-4">Rincian Kegiatan</th>
                        <th class="px-6 py-4 text-center">Dokumentasi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lhs as $item)
                        @php
                            $kegiatanList = is_string($item->rincian_kegiatan) ? json_decode($item->rincian_kegiatan, true) : $item->rincian_kegiatan;
                        @endphp
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800"><i class="far fa-calendar-alt text-sky-500 mr-2"></i>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <ul class="list-disc pl-4 space-y-1 text-slate-600">
                                @if(is_array($kegiatanList))
                                    @foreach(array_slice($kegiatanList, 0, 3) as $kegiatan)
                                        <li class="line-clamp-1">{{ $kegiatan }}</li>
                                    @endforeach
                                    @if(count($kegiatanList) > 3)
                                        <li class="text-xs text-sky-500 font-bold italic border-none list-none mt-1">+ {{ count($kegiatanList) - 3 }} kegiatan lainnya...</li>
                                    @endif
                                @endif
                            </ul>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->dokumentasi)
                                <a href="{{ asset('uploads/lh/'.$item->dokumentasi) }}" target="_blank" class="inline-block relative rounded-lg overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition-all group/img">
                                    <img src="{{ asset('uploads/lh/'.$item->dokumentasi) }}" class="h-12 w-16 object-cover">
                                    <div class="absolute inset-0 bg-slate-900/40 flex items-center justify-center opacity-0 group-hover/img:opacity-100 transition-opacity">
                                        <i class="fas fa-search-plus text-white text-xs"></i>
                                    </div>
                                </a>
                            @else
                                <span class="text-xs text-slate-400 italic bg-slate-100 px-2 py-1 rounded-md border border-slate-200">Tidak ada file</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <form action="{{ route('subkon-eks.lh.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan harian ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all shadow-sm flex items-center justify-center">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400 font-medium"><i class="fas fa-file-alt text-4xl mb-3 block"></i> Belum ada laporan harian.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH LH DENGAN DINAMIS INPUT ALPINE.JS --}}
    <x-modal name="add-lh-modal" focusable>
        <form action="{{ route('subkon-eks.lh.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl flex flex-col max-h-[90vh]">
            @csrf
            
            <div class="bg-gradient-to-r from-sky-500 to-blue-600 px-6 py-4 flex justify-between items-center rounded-t-2xl shrink-0">
                <h3 class="text-white font-bold text-lg"><i class="fas fa-file-signature mr-2"></i> Buat Laporan Harian</h3>
                <button type="button" x-on:click="$dispatch('close')" class="text-sky-100 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>

            <div class="p-6 overflow-y-auto custom-scrollbar space-y-6">
                
                {{-- Tanggal --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal Laporan <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full border-slate-200 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500" required>
                </div>

                {{-- Dynamic Input Kegiatan --}}
                <div x-data="{ kegiatans: [''] }">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 flex justify-between items-center">
                        <span>Rincian Kegiatan <span class="text-red-500">*</span></span>
                        <button type="button" @click="kegiatans.push('')" class="text-xs bg-sky-100 text-sky-600 px-2 py-1 rounded-md hover:bg-sky-200 transition-colors">+ Tambah Baris</button>
                    </label>
                    
                    <div class="space-y-3">
                        <template x-for="(kegiatan, index) in kegiatans" :key="index">
                            <div class="flex gap-2 animate__animated animate__fadeIn relative">
                                <div class="w-8 h-10 flex items-center justify-center bg-slate-50 border border-slate-200 rounded-lg text-slate-400 font-bold text-xs shrink-0" x-text="index + 1"></div>
                                <input type="text" name="kegiatan[]" x-model="kegiatans[index]" class="w-full border-slate-200 rounded-lg text-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Deskripsikan pekerjaan..." required>
                                
                                <button type="button" @click="kegiatans.splice(index, 1)" x-show="kegiatans.length > 1" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-500 border border-red-100 rounded-lg hover:bg-red-500 hover:text-white transition-colors shrink-0">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Upload File --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Dokumentasi Proyek (Opsional)</label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-sky-50 hover:border-sky-300 transition-colors group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-2xl text-slate-400 group-hover:text-sky-500 mb-2"></i>
                                <p class="text-sm text-slate-500"><span class="font-semibold text-sky-600">Klik untuk upload</span> foto kegiatan</p>
                                <p class="text-xs text-slate-400 mt-1">PNG, JPG, JPEG (Max. 2MB)</p>
                            </div>
                            <input type="file" name="dokumentasi" class="hidden" accept="image/*" />
                        </label>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 rounded-b-2xl border-t border-slate-100">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-100 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-sky-600 text-white rounded-xl text-sm font-bold hover:bg-sky-700 shadow-lg shadow-sky-500/30 transition-all transform hover:-translate-y-0.5"><i class="fas fa-paper-plane mr-2"></i>Kirim Laporan</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>