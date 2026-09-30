<x-layouts.app title="Dashboard Operasional" header="Dashboard Pemeliharaan Alat Berat" subtitle="Monitoring fleet, job control bengkel, suku cadang, dan jadwal PM">
    {{-- Metric Stat Cards --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <x-stat-card
            label="Total Armada Unit"
            :value="$totalUnits"
            color="primary"
            icon='<svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>'
            trend="Siap Operasi: {{ $unitsAvailable }}"
            :trend-up="true"
        />

        <x-stat-card
            label="Work Order Aktif"
            :value="$totalWoActive"
            color="warning"
            icon='<svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>'
            trend="Dalam Workshop: {{ $unitsInService }}"
            :trend-up="false"
        />

        <x-stat-card
            label="Unit Breakdown"
            :value="$unitsBreakdown"
            color="danger"
            icon='<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>'
            trend="{{ $unitsBreakdown > 0 ? 'Perlu tindakan cepat' : 'Kondisi Prima' }}"
            :trend-up="$unitsBreakdown == 0"
        />

        <x-stat-card
            label="Mekanik Tersedia"
            :value="$mechanicsAvailable"
            color="success"
            icon='<svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>'
            trend="Siap Ditugaskan"
            :trend-up="true"
        />
    </div>

    {{-- Alert PM Mendekati Interval & Low Stock --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- PM Approaching Alert --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-gray-200 bg-amber-50/50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 bg-amber-100 text-amber-700 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <h3 class="text-sm font-bold text-gray-900">Unit Mendekati Jadwal Periodic Maintenance</h3>
                </div>
                <a href="{{ route('work-orders.create') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-800">
                    + Intake WO PM
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($unitsApproachingPm as $u)
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                        <div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('units.show', $u) }}" class="font-bold text-gray-900 hover:text-primary-600">
                                    {{ $u->unit_code }}
                                </a>
                                <span class="text-xs text-gray-500">({{ $u->name }})</span>
                            </div>
                            <div class="mt-1 text-xs text-gray-500">
                                HM Terkini: <span class="font-semibold text-gray-700">{{ number_format($u->current_hm, 1) }} Jam</span> |
                                PM Terakhir: <span class="text-gray-700">{{ number_format($u->last_pm_hm, 1) }} Jam</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-amber-100 text-amber-800">
                                Sisa {{ number_format($u->getHoursUntilNextPm(), 1) }} HM
                            </span>
                            <div class="mt-1">
                                <a href="{{ route('work-orders.create', ['unit_id' => $u->id]) }}" class="text-xs text-primary-600 hover:underline">
                                    Buat WO &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-gray-500">
                        Tidak ada unit yang mendekati interval PM (&lt; 50 HM).
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Low Stock Spare Parts --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-gray-200 bg-red-50/50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 bg-red-100 text-red-700 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </span>
                    <h3 class="text-sm font-bold text-gray-900">Peringatan Stok Suku Cadang Kritis</h3>
                </div>
                <a href="{{ route('spare-parts.index', ['low_stock' => 1]) }}" class="text-xs font-semibold text-primary-600 hover:text-primary-800">
                    Lihat Semua
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($lowStockParts as $part)
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                        <div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('spare-parts.show', $part) }}" class="font-bold text-gray-900 hover:text-primary-600">
                                    {{ $part->part_number }}
                                </a>
                                @if($part->is_critical)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">Kritis</span>
                                @endif
                            </div>
                            <div class="mt-1 text-xs text-gray-500">
                                {{ $part->name }} &bull; Lokasi: {{ $part->warehouse_location ?? 'Gudang Utama' }}
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-red-600">{{ $part->stock_quantity }} {{ $part->uom }}</span>
                            <div class="text-[11px] text-gray-400">Min: {{ $part->minimum_stock }} {{ $part->uom }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-gray-500">
                        Semua suku cadang berada dalam batas stok aman.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Work Orders Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Work Order Operasional Terbaru</h3>
                <p class="text-xs text-gray-500">Daftar WO yang baru masuk dan sedang diproses dalam workshop</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('work-orders.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Intake WO Baru
                </a>
                <a href="{{ route('work-orders.index') }}" class="px-3 py-2 border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50 transition">
                    Semua WO
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                        <th class="py-3 px-4">No. WO</th>
                        <th class="py-3 px-4">Unit Alat</th>
                        <th class="py-3 px-4">Tipe Pemeliharaan</th>
                        <th class="py-3 px-4">Mekanik Lead</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Prioritas</th>
                        <th class="py-3 px-4">Waktu Dibuat</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentWorkOrders as $wo)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-primary-600">
                                <a href="{{ route('work-orders.show', $wo) }}">{{ $wo->wo_number }}</a>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-gray-900">{{ $wo->unit->unit_code }}</div>
                                <div class="text-xs text-gray-500">{{ $wo->unit->name }} ({{ number_format($wo->intake_hm, 1) }} HM)</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <x-status-badge :status="$wo->maintenance_type" />
                            </td>
                            <td class="py-3.5 px-4">
                                @if($wo->assignedMechanic)
                                    <div class="font-medium text-gray-800">{{ $wo->assignedMechanic->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $wo->assignedMechanic->specialization ?? 'Mekanik' }}</div>
                                @else
                                    <span class="text-xs italic text-gray-400">Belum ditugaskan</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <x-status-badge :status="$wo->status" />
                            </td>
                            <td class="py-3.5 px-4">
                                <x-status-badge :status="$wo->priority" />
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
                            <td colspan="8" class="py-8 text-center text-sm text-gray-500">
                                Belum ada Work Order. Klik tombol <strong>Intake WO Baru</strong> untuk memulai proses servis.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
