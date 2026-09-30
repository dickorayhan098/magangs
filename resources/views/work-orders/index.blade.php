<x-layouts.app title="Daftar Work Order" header="Work Order Bengkel" subtitle="Job control, pemantauan status pengerjaan unit, dan alur pemeliharaan">
    <div class="space-y-6">
        {{-- Filter & Actions Bar --}}
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('work-orders.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
                {{-- Search --}}
                <div class="relative flex-1 min-w-[200px]">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No WO, Kode Unit, Nama..."
                           class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>

                {{-- Status Filter --}}
                <select name="status" class="py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Status</option>
                    @foreach(\App\Enums\WorkOrderStatus::cases() as $st)
                        <option value="{{ $st->value }}" {{ request('status') === $st->value ? 'selected' : '' }}>
                            {{ $st->label() }}
                        </option>
                    @endforeach
                </select>

                {{-- Maintenance Type Filter --}}
                <select name="maintenance_type" class="py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Tipe Maintenance</option>
                    @foreach(\App\Enums\MaintenanceType::cases() as $mt)
                        <option value="{{ $mt->value }}" {{ request('maintenance_type') === $mt->value ? 'selected' : '' }}>
                            {{ $mt->label() }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'maintenance_type']))
                    <a href="{{ route('work-orders.index') }}" class="text-xs text-gray-500 hover:text-gray-700 py-2">Reset</a>
                @endif
            </form>

            <a href="{{ route('work-orders.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Intake Work Order Baru
            </a>
        </div>

        {{-- WO Table --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                            <th class="py-3 px-4">Nomor WO</th>
                            <th class="py-3 px-4">Unit Alat</th>
                            <th class="py-3 px-4">Tipe Pemeliharaan</th>
                            <th class="py-3 px-4">HM Masuk</th>
                            <th class="py-3 px-4">Mekanik Lead</th>
                            <th class="py-3 px-4">Prioritas</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Total Biaya</th>
                            <th class="py-3 px-4">Tanggal Intake</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($workOrders as $wo)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3.5 px-4">
                                    <a href="{{ route('work-orders.show', $wo) }}" class="font-mono font-bold text-primary-600 hover:underline">
                                        {{ $wo->wo_number }}
                                    </a>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-gray-900">{{ $wo->unit->unit_code }}</div>
                                    <div class="text-xs text-gray-500">{{ $wo->unit->name }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <x-status-badge :status="$wo->maintenance_type" />
                                    @if($wo->pm_interval)
                                        <span class="text-xs text-gray-500 ml-1">PM-{{ $wo->pm_interval }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 font-mono font-semibold text-gray-800">
                                    {{ number_format($wo->intake_hm, 1) }} <span class="text-xs font-normal text-gray-400">HM</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($wo->assignedMechanic)
                                        <div class="font-medium text-gray-900">{{ $wo->assignedMechanic->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $wo->assignedMechanic->specialization ?? 'Mekanik' }}</div>
                                    @else
                                        <span class="text-xs italic text-gray-400">Unassigned</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <x-status-badge :status="$wo->priority" />
                                </td>
                                <td class="py-3.5 px-4">
                                    <x-status-badge :status="$wo->status" />
                                </td>
                                <td class="py-3.5 px-4 font-mono text-xs font-semibold text-gray-800">
                                    Rp {{ number_format($wo->total_cost, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4 text-xs text-gray-500">
                                    {{ $wo->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('work-orders.show', $wo) }}" class="inline-flex items-center text-xs font-semibold text-primary-600 hover:text-primary-800">
                                        Detail &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-12 text-center text-sm text-gray-500">
                                    Tidak ada Work Order yang sesuai filter pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($workOrders->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $workOrders->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
