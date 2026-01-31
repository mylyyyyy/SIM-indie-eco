<x-admin-layout>
    <div class="max-w-4xl mx-auto">
        {{-- Header & Back Button --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.locations.index') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-all shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-black text-slate-800">Edit Data Cabang</h2>
                <p class="text-sm text-slate-500">Perbarui informasi detail lokasi.</p>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-50/50 px-8 py-4 border-b border-slate-100 flex items-center gap-2">
                <i class="fas fa-edit text-blue-600"></i>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Formulir Perubahan Data</span>
            </div>

            <form action="{{ route('admin.locations.update', $location->id) }}" method="POST" class="p-8">
                @csrf @method('PUT')

                <div class="space-y-6">
                    {{-- Nama Cabang --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Cabang <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ $location->name }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 transition-all font-semibold text-slate-700" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Tipe --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tipe Lokasi</label>
                            <div class="relative">
                                <select name="type" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 appearance-none bg-white">
                                    <option value="shop" {{ $location->type == 'shop' ? 'selected' : '' }}>Toko (Shop)</option>
                                    <option value="warehouse" {{ $location->type == 'warehouse' ? 'selected' : '' }}>Gudang</option>
                                    <option value="mill" {{ $location->type == 'mill' ? 'selected' : '' }}>Pabrik (Mill)</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-4 top-4 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        {{-- Stok --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Stok Saat Ini (Kg)</label>
                            <input type="number" name="current_stock" value="{{ $location->current_stock }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 font-mono" required>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Status Operasional</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="active" class="peer sr-only" {{ $location->status == 'active' ? 'checked' : '' }}>
                                <div class="px-4 py-3 rounded-xl border-2 border-slate-100 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 flex items-center gap-3 transition-all">
                                    <div class="w-4 h-4 rounded-full border border-slate-300 peer-checked:bg-emerald-500 peer-checked:border-emerald-500"></div>
                                    <span class="text-sm font-bold text-slate-600 peer-checked:text-emerald-700">Aktif</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="inactive" class="peer sr-only" {{ $location->status == 'inactive' ? 'checked' : '' }}>
                                <div class="px-4 py-3 rounded-xl border-2 border-slate-100 peer-checked:border-red-500 peer-checked:bg-red-50 flex items-center gap-3 transition-all">
                                    <div class="w-4 h-4 rounded-full border border-slate-300 peer-checked:bg-red-500 peer-checked:border-red-500"></div>
                                    <span class="text-sm font-bold text-slate-600 peer-checked:text-red-700">Non-Aktif</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('admin.locations.index') }}" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>