<x-admin-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-800">Laporan Harian (LH)</h2>
        <p class="text-sm text-slate-500">Input aktivitas harian Kepala Kantor.</p>
    </div>

    @if(session('success'))
        <script>Swal.fire({ icon: 'success', title: 'Tersimpan', text: "{{ session('success') }}", showConfirmButton: false, timer: 1500 });</script>
    @endif

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <form action="{{ route('kepala_kantor.lh.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal Laporan</label>
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full md:w-1/3 px-4 py-2 border-slate-200 rounded-xl focus:ring-amber-500" required>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Rincian Kegiatan</label>
                <div id="kegiatan-wrapper" class="space-y-3">
                    {{-- Input Pertama --}}
                    <div class="flex gap-2">
                        <span class="bg-amber-100 text-amber-700 w-8 h-10 flex items-center justify-center rounded-lg font-bold">1</span>
                        <input type="text" name="kegiatan[]" class="w-full px-4 py-2 border-slate-200 rounded-xl focus:ring-amber-500" placeholder="Tuliskan kegiatan..." required>
                    </div>
                </div>
                
                <button type="button" onclick="tambahBaris()" class="mt-3 text-sm text-amber-600 font-bold hover:text-amber-800 flex items-center gap-1">
                    <i class="fas fa-plus-circle"></i> Tambah Baris Kegiatan
                </button>
            </div>

            <div class="mb-8">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Dokumentasi (Opsional)</label>
                <input type="file" name="dokumentasi" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
            </div>

            <button type="submit" class="bg-amber-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-amber-500/30 hover:bg-amber-700 transition-all w-full md:w-auto">
                Simpan Laporan Harian
            </button>
        </form>
    </div>

    {{-- Script Dynamic Input --}}
    <script>
        function tambahBaris() {
            const wrapper = document.getElementById('kegiatan-wrapper');
            const count = wrapper.children.length + 1;
            
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <span class="bg-amber-100 text-amber-700 w-8 h-10 flex items-center justify-center rounded-lg font-bold shrink-0">${count}</span>
                <input type="text" name="kegiatan[]" class="w-full px-4 py-2 border-slate-200 rounded-xl focus:ring-amber-500" placeholder="Tuliskan kegiatan..." required>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 px-2"><i class="fas fa-trash"></i></button>
            `;
            wrapper.appendChild(div);
        }
    </script>
</x-admin-layout>