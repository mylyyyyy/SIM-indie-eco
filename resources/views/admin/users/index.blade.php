<x-admin-layout>
    {{-- Load SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate__animated animate__fadeInDown">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Pengguna</h2>
            <p class="text-slate-500 font-medium">Kelola akun administrator, tim proyek, dan divisi lainnya.</p>
        </div>
        
        <div class="flex gap-3 w-full md:w-auto">
            {{-- Search Bar --}}
            <div class="relative w-full md:w-64 group">
                <input type="text" id="searchInput" placeholder="Cari nama atau NIP..." 
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm transition-all group-hover:border-blue-300">
                <i class="fas fa-search absolute left-3 top-3 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
            </div>

            <button x-data @click="$dispatch('open-modal', 'add-user-modal')" 
                class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                <i class="fas fa-plus-circle"></i> <span class="hidden md:inline">User Baru</span>
            </button>
        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            });
        </script>
    @endif

    {{-- TABEL USER --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate__animated animate__fadeInUp">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600" id="userTable">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Pengguna</th>
                        <th class="px-6 py-4">Role & Perusahaan</th>
                        <th class="px-6 py-4">Kontak</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-blue-50/50 transition-colors group">
                        
                        {{-- Kolom 1: Avatar & Nama --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                {{-- UPDATE: Logika Warna Avatar untuk Semua Role --}}
                                @php
                                    $bgClass = match($user->role) {
                                        'admin'          => 'from-purple-500 to-indigo-600',
                                        'kepala_kantor'  => 'from-amber-500 to-red-500',
                                        'manager_unit'   => 'from-indigo-400 to-purple-600',
                                        'eco'            => 'from-emerald-500 to-teal-600',
                                        'indie'          => 'from-pink-500 to-rose-600',
                                        'keuangan_eco'   => 'from-teal-400 to-cyan-600',
                                        'keuangan_indie' => 'from-blue-400 to-sky-600',
                                       
                                        'subkon_pt'      => 'from-blue-500 to-blue-600',
                                        default          => 'from-orange-400 to-orange-500', // subkon_eks
                                    };
                                @endphp
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $bgClass }} text-white flex items-center justify-center font-bold text-lg shadow-md ring-2 ring-white">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="font-bold text-slate-800 block">{{ $user->name }}</span>
                                    <span class="text-xs text-slate-400">{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Kolom 2: Role --}}
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                {{-- UPDATE: Badge Role Lengkap --}}
                                @if($user->role == 'admin')
                                    <span class="inline-flex items-center w-fit px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-purple-100 text-purple-700 border border-purple-200 uppercase tracking-wide">Administrator</span>
                                @elseif($user->role == 'kepala_kantor')
                                    <span class="inline-flex items-center w-fit px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200 uppercase tracking-wide">Kepala Kantor</span>
                                @elseif($user->role == 'manager_unit')
                                    <span class="inline-flex items-center w-fit px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-indigo-100 text-indigo-700 border border-indigo-200 uppercase tracking-wide">Manager Unit</span>
                                @elseif($user->role == 'eco')
                                    <span class="inline-flex items-center w-fit px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase tracking-wide">Admin Kantor (Eco)</span>
                                @elseif($user->role == 'indie')
                                    <span class="inline-flex items-center w-fit px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-pink-100 text-pink-700 border border-pink-200 uppercase tracking-wide">Syafa Indie</span>
                                @elseif($user->role == 'keuangan_eco')
                                    <span class="inline-flex items-center w-fit px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-teal-100 text-teal-700 border border-teal-200 uppercase tracking-wide">Keuangan Eco</span>
                                @elseif($user->role == 'keuangan_indie' || $user->role == 'keuangan')
                                    <span class="inline-flex items-center w-fit px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200 uppercase tracking-wide">Keuangan Indie</span>
                                @elseif($user->role == 'subkon_pt')
                                    <span class="inline-flex items-center w-fit px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200 uppercase tracking-wide">Manager Proyek</span>
                                @else
                                    <span class="inline-flex items-center w-fit px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-orange-100 text-orange-700 border border-orange-200 uppercase tracking-wide">Subkon (EKS)</span>
                                @endif

                                <span class="text-xs font-medium text-slate-500 mt-1 flex items-center gap-1">
                                    <i class="far fa-building"></i> {{ $user->company_name ?? '-' }}
                                </span>
                            </div>
                        </td>

                        {{-- Kolom 3: Kontak --}}
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs flex items-center gap-2"><i class="fas fa-phone w-4 text-slate-400"></i> {{ $user->phone ?? '-' }}</span>
                                <span class="text-xs flex items-center gap-2"><i class="fas fa-tools w-4 text-slate-400"></i> {{ $user->specialization ?? '-' }}</span>
                            </div>
                        </td>

                        {{-- Kolom 4: Status --}}
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Aktif
                            </span>
                        </td>

                        {{-- Kolom 5: Aksi --}}
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <button x-data @click="$dispatch('open-modal', 'edit-user-modal-{{ $user->id }}')" 
                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-blue-600 hover:text-white hover:border-blue-600 flex items-center justify-center transition-all shadow-sm" title="Edit">
                                    <i class="fas fa-pen text-xs"></i>
                                </button>
                                
                                @if(Auth::id() !== $user->id)
                                    <button type="button" onclick="confirmDelete('{{ $user->id }}')"
                                        class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-red-600 hover:text-white hover:border-red-600 flex items-center justify-center transition-all shadow-sm" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    {{-- ================= MODAL EDIT ================= --}}
                    <x-modal name="edit-user-modal-{{ $user->id }}" focusable>
                        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="bg-white rounded-2xl flex flex-col max-h-[90vh] shadow-2xl">
                            @csrf @method('PUT')

                            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-8 py-5 flex justify-between items-center shrink-0 rounded-t-2xl">
                                <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                    <i class="fas fa-user-edit text-blue-400"></i> Edit Data Pengguna
                                </h2>
                                <button type="button" x-on:click="$dispatch('close')" class="text-slate-400 hover:text-white transition-colors focus:outline-none">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>

                            <div class="p-8 overflow-y-auto custom-scrollbar flex-1">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Nama --}}
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Nama Lengkap</label>
                                        <div class="relative group">
                                            <i class="fas fa-user absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                            <input type="text" name="name" value="{{ $user->name }}" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-semibold text-slate-700 bg-slate-50/50 transition-all" required>
                                        </div>
                                    </div>

                                    {{-- Email/NIP --}}
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">NIB/NIP</label>
                                        <div class="relative group">
                                            <i class="fas fa-envelope absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                            <input type="text" name="email" value="{{ $user->email }}" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-600 bg-slate-50/50 transition-all" required>
                                        </div>
                                    </div>

                                    {{-- Password (Optional) --}}
                                    <div class="col-span-2 bg-amber-50/50 p-4 rounded-2xl border border-amber-100">
                                        <label class="block text-xs font-bold text-amber-700 uppercase mb-2 ml-1 flex items-center gap-2">
                                            <i class="fas fa-shield-alt"></i> Password Baru (Opsional)
                                        </label>
                                        <div class="relative group">
                                            <i class="fas fa-lock absolute left-4 top-3.5 text-amber-400 group-focus-within:text-amber-500 transition-colors"></i>
                                            <input type="text" name="password" placeholder="Isi hanya jika ingin mengganti password" class="w-full pl-12 pr-4 py-3 border-amber-200 bg-white rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all placeholder:text-amber-300">
                                        </div>
                                    </div>

                                    {{-- UPDATE: Select Option di Modal Edit --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Role Akun</label>
                                        <div class="relative group">
                                            <i class="fas fa-id-badge absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                            <select name="role" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-600 bg-slate-50/50 transition-all appearance-none">
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                                                <option value="kepala_kantor" {{ $user->role == 'kepala_kantor' ? 'selected' : '' }}>Kepala Kantor</option>
                                                <option value="manager_unit" {{ $user->role == 'manager_unit' ? 'selected' : '' }}>Manager Unit</option>
                                                <option value="eco" {{ $user->role == 'eco' ? 'selected' : '' }}>Admin Kantor (Eco)</option>
                                                <option value="keuangan_eco" {{ $user->role == 'keuangan_eco' ? 'selected' : '' }}>Keuangan Eco</option>
                                                <option value="indie" {{ $user->role == 'indie' ? 'selected' : '' }}>Tim Syafa Indie</option>
                                                <option value="keuangan_indie" {{ in_array($user->role, ['keuangan_indie', 'keuangan']) ? 'selected' : '' }}>Keuangan Indie</option>
                                                <option value="subkon_pt" {{ $user->role == 'subkon_pt' ? 'selected' : '' }}>Manager Proyek</option>
                                                <option value="subkon_eks" {{ $user->role == 'subkon_eks' ? 'selected' : '' }}>Subkon (EKS)</option>
                                            </select>
                                            <i class="fas fa-chevron-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>

                                    {{-- Perusahaan --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Perusahaan</label>
                                        <div class="relative group">
                                            <i class="fas fa-building absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                            <input type="text" name="company_name" value="{{ $user->company_name }}" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-600 bg-slate-50/50 transition-all">
                                        </div>
                                    </div>

                                    {{-- Telepon --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">No. Telepon</label>
                                        <div class="relative group">
                                            <i class="fas fa-phone absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                            <input type="text" name="phone" value="{{ $user->phone }}" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-600 bg-slate-50/50 transition-all">
                                        </div>
                                    </div>

                                    {{-- Spesialisasi --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Spesialisasi</label>
                                        <div class="relative group">
                                            <i class="fas fa-hammer absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                            <input type="text" name="specialization" value="{{ $user->specialization }}" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-600 bg-slate-50/50 transition-all">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Footer --}}
                            <div class="px-8 py-5 border-t border-slate-100 flex justify-end gap-3 bg-white rounded-b-2xl shrink-0 z-10">
                                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200 transition-colors focus:outline-none">
                                    Batal
                                </button>
                                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </x-modal>

                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-300">
                                <i class="fas fa-user-slash text-5xl mb-3"></i>
                                <p class="font-medium">Belum ada data pengguna.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= MODAL TAMBAH ================= --}}
    <x-modal name="add-user-modal" focusable>
        <form method="POST" action="{{ route('admin.users.store') }}" class="bg-white rounded-2xl flex flex-col max-h-[90vh] shadow-2xl">
            @csrf
            
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-5 flex justify-between items-center shrink-0 rounded-t-2xl">
                <h2 class="text-xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-user-plus text-blue-200"></i> Tambah Pengguna Baru
                </h2>
                <button type="button" x-on:click="$dispatch('close')" class="text-blue-200 hover:text-white transition-colors focus:outline-none">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-8 overflow-y-auto custom-scrollbar flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama --}}
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Nama Lengkap / PT</label>
                        <div class="relative group">
                            <i class="fas fa-user absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            <input type="text" name="name" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-semibold text-slate-700 bg-slate-50/50 transition-all" placeholder="Masukkan nama..." required>
                        </div>
                    </div>

                    {{-- Email/NIP --}}
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">NIB/NIP</label>
                        <div class="relative group">
                            <i class="fas fa-envelope absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            <input type="text" name="email" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-600 bg-slate-50/50 transition-all" placeholder="Masukan NIB/NIP" required>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="col-span-2 bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 flex items-center gap-2">
                            <i class="fas fa-key"></i> Password Default
                        </label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-3.5 text-slate-400"></i>
                            <input type="text" name="password" value="password123" class="w-full pl-12 pr-4 py-3 border-slate-200 bg-white rounded-xl text-sm text-slate-500 font-medium cursor-not-allowed" readonly>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2 ml-1">*Pengguna dapat menggantinya setelah login.</p>
                    </div>

                    {{-- UPDATE: Select Option di Modal Tambah --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Role Akun</label>
                        <div class="relative group">
                            <i class="fas fa-id-badge absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            <select name="role" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-600 bg-slate-50/50 transition-all appearance-none" required>
                                <option value="" disabled selected>-- Pilih Role --</option>
                                <option value="admin">Administrator</option>
                                <option value="kepala_kantor">Kepala Kantor</option>
                                <option value="manager_unit">Manager Unit</option>
                                <option value="eco">Admin Kantor (Eco)</option>
                                <option value="keuangan_eco">Keuangan Eco</option>
                                <option value="indie">Tim Syafa Indie</option>
                                <option value="keuangan_indie">Keuangan Indie</option>
                                <option value="subkon_pt">Manager Proyek</option>
                                <option value="subkon_eks">Subkon (EKS)</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>

                    {{-- Perusahaan --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Perusahaan</label>
                        <div class="relative group">
                            <i class="fas fa-building absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            <input type="text" name="company_name" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-600 bg-slate-50/50 transition-all" placeholder="Opsional">
                        </div>
                    </div>

                    {{-- Telepon --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">No. Telepon</label>
                        <div class="relative group">
                            <i class="fas fa-phone absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            <input type="text" name="phone" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-600 bg-slate-50/50 transition-all" placeholder="08...">
                        </div>
                    </div>

                    {{-- Spesialisasi --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Spesialisasi</label>
                        <div class="relative group">
                            <i class="fas fa-hammer absolute left-4 top-3.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            <input type="text" name="specialization" class="w-full pl-12 pr-4 py-3 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-600 bg-slate-50/50 transition-all" placeholder="Contoh: Elektrikal, Keuangan...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-8 py-5 border-t border-slate-100 flex justify-end gap-3 bg-white rounded-b-2xl shrink-0 z-10">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200 transition-colors focus:outline-none">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl text-sm font-bold hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fas fa-save"></i> Simpan Data
                </button>
            </div>
        </form>
    </x-modal>

    {{-- CSS CUSTOM SCROLLBAR --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            margin-block: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#userTable tbody tr');
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Pengguna?',
                text: "Data akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
</x-admin-layout>