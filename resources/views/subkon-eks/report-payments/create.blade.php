<x-admin-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center gap-4">
            {{-- PERUBAHAN ROUTE BACK --}}
            <a href="{{ route('subkon-eks.report-payments.index') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:text-blue-600">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-black text-slate-800">Form Klaim Pembayaran</h2>
                <p class="text-sm text-slate-500">Upload bukti pekerjaan untuk proses pencairan.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            {{-- PERUBAHAN ACTION FORM --}}
            <form action="{{ route('subkon-eks.report-payments.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                
                {{-- (Isi Form sama seperti sebelumnya: Proyek, Tanggal, Progress, Deskripsi, Upload) --}}
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Proyek</label>
                    <select name="project_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm" required>
                        <option value="" disabled selected>-- Pilih Proyek --</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal</label>
                        <input type="date" name="report_date" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Progress (%)</label>
                        <input type="number" name="progress_percentage" min="0" max="100" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Uraian Tagihan</label>
                    <textarea name="work_description" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm" placeholder="Contoh: Termin 1 Pengecoran..." required></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Upload Scan Dokumen</label>
                    <input type="file" name="document" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>