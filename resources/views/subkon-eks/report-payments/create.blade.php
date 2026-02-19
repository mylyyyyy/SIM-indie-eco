<x-admin-layout>
    <div class="max-w-3xl mx-auto">
        
        {{-- AREA PESAN ERROR --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl shadow-sm animate__animated animate__shakeX">
                <div class="flex items-center gap-2 font-bold mb-2">
                    <i class="fas fa-exclamation-circle"></i> Gagal Menyimpan!
                </div>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Header --}}
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('subkon-eks.report-payments.index') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:text-blue-600 hover:border-blue-300 transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-black text-slate-800">Form Klaim Pembayaran</h2>
                <p class="text-sm text-slate-500">Ajukan nominal pencairan dana proyek.</p>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <form action="{{ route('subkon-eks.report-payments.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                
                {{-- Input Proyek --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Proyek <span class="text-red-500">*</span></label>
                    <select name="project_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="" disabled selected>-- Pilih Proyek --</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Input Tanggal --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal Pengajuan <span class="text-red-500">*</span></label>
                        <input type="date" name="payment_date" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    
                    {{-- Input Nominal --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nominal Pengajuan (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" min="0" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: 5000000" required>
                    </div>
                </div>
                
                {{-- Input Keterangan --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Uraian Tagihan / Keterangan <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Pembayaran Termin 1 Pengecoran..." required></textarea>
                </div>

                {{-- Input File (Diubah menjadi PDF) --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Upload Invoice / Bukti Tagihan <span class="text-red-500">*</span></label>
                    {{-- Tambahkan accept=".pdf" --}}
                    <input type="file" name="document" accept=".pdf" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" required>
                    <p class="text-[10px] text-slate-400 mt-2">*Format wajib: PDF. Max Ukuran: 5MB</p>
                </div>

                {{-- Tombol Submit --}}
                <div class="pt-4 flex justify-end border-t border-slate-100">
                    <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>