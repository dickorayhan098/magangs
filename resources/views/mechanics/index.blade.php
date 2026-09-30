<x-layouts.app title="Daftar Mekanik" header="Data Mekanik & Teknisi" subtitle="Kelola tim mekanik bengkel, spesialisasi alat berat, sertifikasi, dan ketersediaan">
    <div class="space-y-6">
        {{-- Filter & Actions Bar --}}
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('mechanics.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
                <div class="relative flex-1 min-w-[200px]">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Mekanik, ID Karyawan..."
                           class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>

                <select name="specialization" class="py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Spesialisasi</option>
                    @foreach(['Hydraulic Specialist', 'Engine Specialist', 'Electrical & Controls', 'Undercarriage Specialist', 'General Mechanic', 'Welder & Fabricator'] as $spec)
                        <option value="{{ $spec }}" {{ request('specialization') === $spec ? 'selected' : '' }}>{{ $spec }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'specialization']))
                    <a href="{{ route('mechanics.index') }}" class="text-xs text-gray-500 hover:text-gray-700 py-2">Reset</a>
                @endif
            </form>

            <a href="{{ route('mechanics.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Mekanik Baru
            </a>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                            <th class="py-3 px-4">ID Karyawan</th>
                            <th class="py-3 px-4">Nama Mekanik</th>
                            <th class="py-3 px-4">Spesialisasi</th>
                            <th class="py-3 px-4">Level Sertifikasi</th>
                            <th class="py-3 px-4">No. HP</th>
                            <th class="py-3 px-4">Status Kerja</th>
                            <th class="py-3 px-4">WO Aktif</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($mechanics as $mechanic)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-gray-700">
                                    {{ $mechanic->employee_id }}
                                </td>
                                <td class="py-3.5 px-4 font-bold text-gray-900">
                                    <a href="{{ route('mechanics.show', $mechanic) }}" class="text-primary-600 hover:underline">
                                        {{ $mechanic->name }}
                                    </a>
                                </td>
                                <td class="py-3.5 px-4 text-gray-600">
                                    {{ $mechanic->specialization ?? 'General' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold uppercase
                                        {{ match($mechanic->certification_level) {
                                            'specialist' => 'bg-purple-100 text-purple-800',
                                            'lead' => 'bg-indigo-100 text-indigo-800',
                                            'senior' => 'bg-blue-100 text-blue-800',
                                            default => 'bg-gray-100 text-gray-700',
                                        } }}">
                                        {{ $mechanic->certification_level }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-xs text-gray-500">
                                    {{ $mechanic->phone ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($mechanic->is_available)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">
                                            Available
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 ring-1 ring-amber-600/20">
                                            Sedang Bertugas
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-center">
                                    {{ $mechanic->assigned_work_orders_count }}
                                </td>
                                <td class="py-3.5 px-4 text-right space-x-2">
                                    <a href="{{ route('mechanics.show', $mechanic) }}" class="text-xs font-semibold text-primary-600 hover:text-primary-800">Detail</a>
                                    <a href="{{ route('mechanics.edit', $mechanic) }}" class="text-xs font-semibold text-gray-600 hover:text-gray-800">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-sm text-gray-500">
                                    Belum ada data mekanik yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($mechanics->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $mechanics->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
