<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-black text-slate-800">Manajemen Tim</h2>
        <a href="{{ route('admin.teams.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Anggota
        </a>
    </div>

    {{-- ALERT SUKSES --}}
    @if(session('success'))
        <script>Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false });</script>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Foto</th>
                        <th class="px-6 py-4">Nama & Jabatan</th>
                        <th class="px-6 py-4">Kontak</th>
                        <th class="px-6 py-4 text-center">Urutan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($teams as $index => $team)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            @if($team->photo)
                                <img src="{{ $team->photo }}" alt="{{ $team->name }}" class="w-12 h-12 rounded-full object-cover border border-slate-200 shadow-sm">
                            @else
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-xs border border-slate-200">
                                    No Pic
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 text-base">{{ $team->name }}</div>
                            <div class="text-xs text-blue-600 font-bold uppercase tracking-wide">{{ $team->role }}</div>
                        </td>
                        <td class="px-6 py-4 space-y-1">
                            @if($team->phone) <div class="text-xs"><i class="fab fa-whatsapp text-emerald-500 w-4"></i> {{ $team->phone }}</div> @endif
                            @if($team->email) <div class="text-xs"><i class="far fa-envelope text-blue-500 w-4"></i> {{ $team->email }}</div> @endif
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-slate-400">
                            {{ $team->urutan }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.teams.edit', $team->id) }}" class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center hover:bg-amber-200 transition-colors" title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>

                                {{-- Tombol Hapus (Trigger SweetAlert) --}}
                                <button onclick="confirmDelete({{ $team->id }})" class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition-colors" title="Hapus">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>

                                {{-- Form Hapus Tersembunyi --}}
                                <form id="delete-form-{{ $team->id }}" action="{{ route('admin.teams.destroy', $team->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- SCRIPT SWEETALERT HAPUS --}}
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Data Tim?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', // Merah
                cancelButtonColor: '#cbd5e1', // Abu-abu
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl', // Agar sudut membulat modern
                    confirmButton: 'font-bold px-4 py-2 rounded-lg',
                    cancelButton: 'font-bold px-4 py-2 rounded-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika user klik Ya, submit form tersembunyi
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
</x-admin-layout>