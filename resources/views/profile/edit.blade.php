<x-admin-layout>
    {{-- Load SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate__animated animate__fadeInDown">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Keamanan Akun</h2>
            <p class="text-slate-500 font-medium">Perbarui kata sandi Anda secara berkala untuk keamanan.</p>
        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if (session('status') === 'password-updated')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Password berhasil diperbarui.',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            });
        </script>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        {{-- KOLOM KIRI: GANTI PASSWORD (UTAMA) --}}
        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate__animated animate__fadeInUp">
                <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas fa-key"></i> Ganti Password
                    </h3>
                </div>

                <div class="p-8">
                    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('put')

                        {{-- Password Lama --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Password Saat Ini</label>
                            <div class="relative group">
                                <i class="fas fa-lock absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                <input type="password" name="current_password" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-slate-500 focus:border-slate-500 text-slate-700 bg-slate-50/50 transition-all" placeholder="Masukkan password lama Anda" required>
                            </div>
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Password Baru --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Password Baru</label>
                                <div class="relative group">
                                    <i class="fas fa-key absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                    <input type="password" name="password" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700 bg-slate-50/50 transition-all" placeholder="Minimal 8 karakter" required>
                                </div>
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Konfirmasi Password</label>
                                <div class="relative group">
                                    <i class="fas fa-check-circle absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                    <input type="password" name="password_confirmation" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700 bg-slate-50/50 transition-all" placeholder="Ulangi password baru" required>
                                </div>
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 mt-2 flex justify-end">
                            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <i class="fas fa-shield-alt"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: INFO DATA DIRI (READ ONLY) --}}
        <div class="xl:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate__animated animate__fadeInUp animate__delay-1s">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-id-card text-slate-400"></i> Detail Akun
                    </h3>
                </div>
                
                <div class="p-6 space-y-5">
                    {{-- Nama --}}
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nama Lengkap</label>
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <span class="text-sm font-bold text-slate-700">{{ $user->name }}</span>
                        </div>
                    </div>

                    {{-- NIP/NIB (DISESUAIKAN) --}}
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">NIP / NIB (Username)</label>
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <i class="fas fa-user-tag text-slate-400 ml-1"></i>
                            <span class="text-sm font-mono font-medium text-slate-600">{{ $user->email }}</span>
                        </div>
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Role Akses</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wide">
                            {{ str_replace('_', ' ', $user->role) }}
                        </span>
                    </div>

                    {{-- Perusahaan --}}
                    @if($user->company_name)
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Perusahaan</label>
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <i class="fas fa-building text-slate-400 ml-1"></i>
                            <span class="text-sm font-medium text-slate-600">{{ $user->company_name }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="pt-4 mt-4 border-t border-slate-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full py-2.5 text-sm text-red-500 font-bold hover:bg-red-50 rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>