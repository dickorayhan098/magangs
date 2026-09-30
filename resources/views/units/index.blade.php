<x-layouts.app title="Daftar Unit Alat Berat" header="Armada Alat Berat" subtitle="Kelola master data alat berat, status operasional, dan riwayat HM">
    <div class="space-y-6">
        {{-- Header Actions & Filter Bar --}}
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('units.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
                {{-- Search --}}
                <div class="relative flex-1 min-w-[200px]">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode Unit, Model, Serial..."
                           class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>

                {{-- Status Filter --}}
                <select name="status" class="py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Status</option>
                    @foreach(\App\Enums\UnitStatus::cases() as $st)
                        <option value="{{ $st->value }}" {{ request('status') === $st->value ? 'selected' : '' }}>
                            {{ $st->label() }}
                        </option>
                    @endforeach
                </select>

                {{-- Category Filter --}}
                <select name="category" class="py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Kategori</option>
                    @foreach(['Excavator', 'Bulldozer', 'Wheel Loader', 'Dump Truck', 'Motor Grader', 'Crane', 'Compactor'] as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'category']))
                    <a href="{{ route('units.index') }}" class="text-xs text-gray-500 hover:text-gray-700 py-2">Reset</a>
                @endif
            </form>

            <a href="{{ route('units.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Registrasi Unit Baru
            </a>
        </div>

        {{-- Units Table --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                            <th class="py-3 px-4">Kode Unit</th>
                            <th class="py-3 px-4">Nama & Model</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Serial Number</th>
                            <th class="py-3 px-4">HM Terkini</th>
                            <th class="py-3 px-4">Status PM</th>
                            <th class="py-3 px-4">Status Alat</th>
                            <th class="py-3 px-4">Lokasi</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($units as $unit)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3.5 px-4">
                                    <a href="{{ route('units.show', $unit) }}" class="font-mono font-bold text-primary-600 hover:underline">
                                        {{ $unit->unit_code }}
                                    </a>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-gray-900">{{ $unit->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $unit->brand }} {{ $unit->model }} ({{ $unit->year_manufactured ?? '-' }})</div>
                                </td>
                                <td class="py-3.5 px-4 text-gray-600 font-medium">
                                    {{ $unit->category }}
                                </td>
                                <td class="py-3.5 px-4 font-mono text-xs text-gray-500">
                                    {{ $unit->serial_number }}
                                </td>
                                <td class="py-3.5 px-4 font-bold text-gray-900">
                                    {{ number_format($unit->current_hm, 1) }} <span class="text-xs font-normal text-gray-500">HM</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    @php
                                        $approaching = $unit->isApproachingPm();
                                        $sisaHm = $unit->getHoursUntilNextPm();
                                    @endphp
                                    @if($approaching)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-800">
                                            PM dalam {{ number_format($sisaHm, 1) }} HM
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-500">
                                            Next: {{ number_format($unit->last_pm_hm + $unit->getNextPmInterval(), 0) }} HM
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <x-status-badge :status="$unit->status" />
                                </td>
                                <td class="py-3.5 px-4 text-xs text-gray-500">
                                    {{ $unit->location ?? 'Site Utama' }}
                                </td>
                                <td class="py-3.5 px-4 text-right space-x-2">
                                    <a href="{{ route('units.show', $unit) }}" class="text-xs font-semibold text-primary-600 hover:text-primary-800">Detail</a>
                                    <a href="{{ route('units.edit', $unit) }}" class="text-xs font-semibold text-gray-600 hover:text-gray-800">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center text-sm text-gray-500">
                                    Tidak ada unit yang ditemukan sesuai kriteria pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($units->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $units->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
