<x-admin-layout>
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate__animated animate__fadeInDown">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Input Laporan Baru</h2>
            <p class="text-slate-500 font-medium">Laporkan progress pekerjaan harian Anda di sini.</p>
        </div>
        
        <a href="{{ route('subkon-eks.dashboard') }}" class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    {{-- Form Card --}}
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate__animated animate__fadeInUp">
        
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6">
            <h3 class="text-white font-bold text-lg flex items-center gap-2">
                <i class="fas fa-file-contract text-blue-200"></i> Formulir Laporan Harian
            </h3>
            <p class="text-blue-100 text-sm mt-1">Pastikan data yang diisi sesuai dengan kondisi lapangan.</p>
        </div>

        <form action="{{ route('subkon-eks.reports.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- KOLOM KIRI: Data Proyek --}}
                <div class="space-y-6">
                    
                    {{-- Pilih Proyek --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Pilih Proyek</label>
                        <div class="relative group">
                            <i class="fas fa-building absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            <select name="project_id" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700 font-semibold bg-slate-50/50 transition-all appearance-none cursor-pointer">
                                <option value="" disabled selected>-- Pilih Proyek --</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->project_name }} - {{ $project->location }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                        </div>
                        <x-input-error :messages="$errors->get('project_id')" class="mt-2" />
                    </div>

                    {{-- Tanggal & Progress (Grid Kecil) --}}
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Tanggal --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Tanggal Laporan</label>
                            <div class="relative group">
                                <i class="fas fa-calendar-alt absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                <input type="date" name="report_date" value="{{ date('Y-m-d') }}" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 font-semibold text-slate-600 bg-slate-100 cursor-not-allowed" readonly>
                            </div>
                        </div>

                        {{-- Progress --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Progress (%)</label>
                            <div class="relative group">
                                <i class="fas fa-percentage absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                <input type="number" name="progress_percentage" value="{{ old('progress_percentage') }}" min="0" max="100" placeholder="0-100" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 font-bold text-slate-700 transition-all">
                            </div>
                            <x-input-error :messages="$errors->get('progress_percentage')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Deskripsi Pekerjaan --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Deskripsi Pekerjaan</label>
                        <div class="relative group">
                            <i class="fas fa-align-left absolute left-4 top-4 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            <textarea name="work_description" rows="5" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 text-slate-600 transition-all leading-relaxed" placeholder="Contoh: Pemasangan keramik lantai 2 selesai 50%, kendala hujan deras di sore hari...">{{ old('work_description') }}</textarea>
                        </div>
                        <x-input-error :messages="$errors->get('work_description')" class="mt-2" />
                    </div>

                </div>

                {{-- KOLOM KANAN: Upload Foto (Alpine JS Preview) --}}
                <div class="space-y-6">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Dokumentasi Lapangan</label>
                    
                    <div x-data="{ photoName: null, photoPreview: null }" class="col-span-6 ml-2 sm:col-span-4">
                        <input type="file" name="documentation" class="hidden" x-ref="photo"
                            x-on:change="
                                photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.photo.files[0]);
                            " />
        
                        <div class="mt-2" x-show="! photoPreview">
                            <div @click.prevent="$refs.photo.click()" class="w-full h-80 border-2 border-dashed border-slate-300 rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all group">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-camera text-2xl text-slate-400 group-hover:text-blue-500"></i>
                                </div>
                                <p class="text-sm font-bold text-slate-600">Klik untuk upload foto</p>
                                <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                            </div>
                        </div>
        
                        <div class="mt-2 relative group" x-show="photoPreview" style="display: none;">
                            <span class="block w-full h-80 rounded-2xl bg-cover bg-no-repeat bg-center shadow-md"
                                  x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                            </span>
                            
                            {{-- Tombol Ganti Foto --}}
                            <div @click.prevent="$refs.photo.click()" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer rounded-2xl">
                                <span class="bg-white/20 backdrop-blur-md border border-white/50 text-white px-4 py-2 rounded-lg font-bold text-sm">
                                    <i class="fas fa-sync-alt mr-2"></i> Ganti Foto
                                </span>
                            </div>
                        </div>
                        
                        <x-input-error :messages="$errors->get('documentation')" class="mt-2" />
                    </div>

                    {{-- Tips Box --}}
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 flex items-start gap-3">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                        <div class="text-xs text-blue-700 leading-relaxed">
                            <strong>Tips:</strong> Pastikan foto dokumentasi terlihat jelas dan mencakup area pekerjaan yang dilaporkan agar admin lebih cepat memverifikasi.
                        </div>
                    </div>
                </div>

            </div>

            {{-- Footer Buttons --}}
            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end gap-4">
                <a href="{{ route('subkon-eks.dashboard') }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-bold text-sm hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                    <i class="fas fa-paper-plane"></i> Kirim Laporan
                </button>
            </div>

        </form>
    </div>
</x-admin-layout>