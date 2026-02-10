<x-admin-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('admin.teams.index') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:text-blue-600">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-black text-slate-800">Tambah Anggota Tim</h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden p-8">
            <form action="{{ route('admin.teams.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    
                    {{-- Jabatan --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jabatan / Posisi <span class="text-red-500">*</span></label>
                        <input type="text" name="role" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- No HP --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">No. WhatsApp</label>
                        <input type="text" name="phone" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm" placeholder="08123456789">
                    </div>
                    
                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email</label>
                        <input type="email" name="email" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm">
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Alamat / Domisili</label>
                    <input type="text" name="address" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Foto --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Foto Profil (Max 2MB)</label>
                        <input type="file" name="photo" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>

                    {{-- Urutan --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Urutan Tampil</label>
                        <input type="number" name="urutan" value="0" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm">
                        <p class="text-[10px] text-slate-400 mt-1">Angka kecil tampil duluan (1, 2, 3...)</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>