<x-admin-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-800">Laporan Harian Proyek</h2>
        <p class="text-sm text-slate-500">
            @if(Auth::user()->role == 'subkon_pt')
                Input aktivitas harian, cuaca, dan jumlah pekerja di lapangan.
            @else
                Pantau aktivitas harian proyek dari Subkon PT.
            @endif
        </p>
    </div>

    @if(session('success'))
        <script>Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });</script>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- KOLOM KIRI: FORM INPUT (HANYA SUBKON PT) --}}
        @if(Auth::user()->role == 'subkon_pt')
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-fit sticky top-24">
            <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Buat Laporan Harian</h3>
            
            <form action="{{ route('subkon-pt.daily-reports.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Proyek</label>
                    <input type="text" name="project_name" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-blue-500" placeholder="Ketik nama proyek..." required>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Cuaca</label>
                        <select name="cuaca" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white" required>
                            <option value="Cerah">Cerah ☀️</option>
                            <option value="Berawan">Berawan ⛅</option>
                            <option value="Hujan Ringan">Hujan Ringan 🌧️</option>
                            <option value="Hujan Lebat">Hujan Lebat ⛈️</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Jumlah Pekerja (Orang)</label>
                    <input type="number" min="0" name="jumlah_pekerja" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-blue-500" value="0" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Pekerjaan Yang Dilakukan</label>
                    <textarea name="pekerjaan_dilakukan" rows="3" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-blue-500" placeholder="Rincian pekerjaan hari ini..." required></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kendala (Opsional)</label>
                    <textarea name="kendala" rows="2" class="w-full px-3 py-2 border border-red-200 bg-red-50 rounded-lg text-sm focus:ring-red-500" placeholder="Jika ada kendala di lapangan..."></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow mt-2">Simpan Laporan Harian</button>
            </form>
        </div>
        @endif

        {{-- KOLOM KANAN: TABEL DATA --}}
        <div class="{{ Auth::user()->role == 'subkon_pt' ? 'lg:col-span-2' : 'lg:col-span-3' }} bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold">
                        <tr>
                            <th class="px-4 py-3">Tanggal & Proyek</th>
                            @if(Auth::user()->role != 'subkon_pt')
                                <th class="px-4 py-3">Subkon PT</th>
                            @endif
                            <th class="px-4 py-3 text-center">Kondisi</th>
                            <th class="px-4 py-3">Detail Pekerjaan</th>
                            @if(Auth::user()->role == 'subkon_pt')
                                <th class="px-4 py-3 text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($dailyReports as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 align-top">
                                <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</div>
                                <div class="text-xs text-blue-600 font-bold mt-1">{{ $item->project_name }}</div>
                            </td>
                            
                            @if(Auth::user()->role != 'subkon_pt')
                            <td class="px-4 py-3 align-top font-bold text-slate-700">{{ $item->user->name ?? '-' }}</td>
                            @endif
                            
                            <td class="px-4 py-3 text-center align-top">
                                <div class="text-xs bg-slate-100 px-2 py-1 rounded font-medium mb-1">{{ $item->cuaca }}</div>
                                <div class="text-[10px] font-bold text-slate-500"><i class="fas fa-users mr-1"></i>{{ $item->jumlah_pekerja }} Pekerja</div>
                            </td>
                            
                            <td class="px-4 py-3 align-top">
                                <p class="text-xs text-slate-700 whitespace-pre-line">{{ $item->pekerjaan_dilakukan }}</p>
                                @if($item->kendala)
                                    <div class="mt-2 text-[10px] text-red-600 bg-red-50 border border-red-100 p-1.5 rounded">
                                        <span class="font-bold">Kendala:</span> {{ $item->kendala }}
                                    </div>
                                @endif
                            </td>
                            
                            @if(Auth::user()->role == 'subkon_pt')
                            <td class="px-4 py-3 text-center align-top">
                                <form action="{{ route('subkon-pt.daily-reports.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus laporan harian ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded transition-colors"><i class="fas fa-trash text-xs"></i></button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-8 text-slate-400">Belum ada laporan harian.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>