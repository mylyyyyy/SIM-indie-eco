<x-admin-layout>
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 animate__animated animate__fadeInDown">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Global Login History</h2>
            <p class="text-slate-500 text-sm">Memantau aktivitas login seluruh pengguna sistem.</p>
        </div>
        
        {{-- Statistik Sederhana --}}
        <div class="flex gap-3">
            <div class="bg-blue-50 px-4 py-2 rounded-xl border border-blue-100 text-center">
                <span class="block text-xs font-bold text-blue-400 uppercase">Total Log</span>
                <span class="font-black text-lg text-blue-700">{{ $histories->total() }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate__animated animate__fadeInUp">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Pengguna</th>
                        <th class="px-6 py-4">Waktu Login</th>
                        <th class="px-6 py-4">IP Address</th>
                        <th class="px-6 py-4">Perangkat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($histories as $history)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        
                        {{-- KOLOM 1: IDENTITAS USER --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                {{-- Avatar Inisial --}}
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs shadow-sm
                                    @if($history->user->role == 'admin') bg-purple-600 text-white
                                    @elseif($history->user->role == 'subkon_pt') bg-blue-600 text-white
                                    @else bg-emerald-500 text-white @endif">
                                    {{ substr($history->user->name, 0, 1) }}
                                </div>
                                
                                <div>
                                    <div class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors">
                                        {{ $history->user->name }}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        {{-- Badge Role --}}
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                            @if($history->user->role == 'admin') bg-purple-50 text-purple-700 border border-purple-100
                                            @elseif($history->user->role == 'subkon_pt') bg-blue-50 text-blue-700 border border-blue-100
                                            @else bg-emerald-50 text-emerald-700 border border-emerald-100 @endif">
                                            {{ str_replace('_', ' ', $history->user->role) }}
                                        </span>
                                        <span class="text-xs text-slate-400 hidden md:inline">| {{ $history->user->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- KOLOM 2: WAKTU --}}
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-700">
                                    {{ \Carbon\Carbon::parse($history->login_at)->translatedFormat('d M Y') }}
                                </span>
                                <span class="text-xs text-slate-400 font-mono">
                                    {{ \Carbon\Carbon::parse($history->login_at)->format('H:i:s') }} WIB
                                </span>
                            </div>
                        </td>

                        {{-- KOLOM 3: IP ADDRESS --}}
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs font-semibold bg-slate-100 px-2 py-1 rounded text-slate-600 border border-slate-200">
                                {{ $history->ip_address }}
                            </span>
                        </td>

                        {{-- KOLOM 4: DEVICE --}}
                        <td class="px-6 py-4">
                            <div class="max-w-[200px] truncate text-xs text-slate-500" title="{{ $history->user_agent }}">
                                @if(Str::contains($history->user_agent, 'Mobile'))
                                    <i class="fas fa-mobile-alt mr-1"></i> Mobile
                                @else
                                    <i class="fas fa-desktop mr-1"></i> Desktop
                                @endif
                                <span class="opacity-70">- {{ Str::limit($history->user_agent, 20) }}</span>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <i class="fas fa-history text-4xl mb-3 opacity-30"></i>
                                <span class="text-sm">Belum ada data riwayat login.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $histories->links() }}
        </div>
    </div>
</x-admin-layout>