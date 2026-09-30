<x-layouts.app :title="'Detail Unit ' . $unit->unit_code" :header="'Unit: ' . $unit->unit_code . ' (' . $unit->name . ')'" subtitle="Informasi spesifikasi lengkap, pelacakan Hours Meter (HM), dan riwayat servis">
    <div class="space-y-6" x-data="{ showHmModal: false }">
        {{-- Top Bar Actions --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('units.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Unit
            </a>
            <div class="flex items-center gap-2">
                <button @click="showHmModal = true" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Catat Hours Meter
                </button>
                <a href="{{ route('work-orders.create', ['unit_id' => $unit->id]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Intake Work Order
                </a>
                <a href="{{ route('units.edit', $unit) }}" class="px-3.5 py-2 border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50 transition">
                    Edit Data
                </a>
            </div>
        </div>

        {{-- Overview Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Status Unit</span>
                <div class="mt-2">
                    <x-status-badge :status="$unit->status" />
                </div>
                <div class="mt-2 text-xs text-gray-500">Lokasi: {{ $unit->location ?? 'Site Utama' }}</div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">HM Terkini</span>
                <div class="mt-1 text-2xl font-bold font-mono text-gray-900">
                    {{ number_format($unit->current_hm, 1) }} <span class="text-xs font-normal text-gray-500">Jam</span>
                </div>
                <div class="mt-1 text-xs text-gray-500">PM Terakhir: {{ number_format($unit->last_pm_hm, 1) }} HM</div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Jadwal PM Berikutnya</span>
                @php
                    $nextInterval = $unit->getNextPmInterval();
                    $nextHm = $unit->last_pm_hm + $nextInterval;
                    $remHm = $unit->getHoursUntilNextPm();
                @endphp
                <div class="mt-1 text-lg font-bold text-gray-900">
                    PM-{{ $nextInterval }} <span class="text-sm font-normal text-gray-500">(@ {{ number_format($nextHm, 0) }} HM)</span>
                </div>
                <div class="mt-1">
                    @if($unit->isApproachingPm())
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-800">
                            Sisa {{ number_format($remHm, 1) }} HM lagi
                        </span>
                    @else
                        <span class="text-xs text-emerald-600 font-semibold">Sisa {{ number_format($remHm, 1) }} HM</span>
                    @endif
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kepemilikan & Usia</span>
                <div class="mt-1 text-base font-bold text-gray-900 capitalize">
                    {{ $unit->ownership }}
                </div>
                <div class="mt-1 text-xs text-gray-500">Tahun: {{ $unit->year_manufactured ?? 'N/A' }}</div>
            </div>
        </div>

        {{-- Specification Details --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-3 border-b border-gray-100 mb-4">
                Spesifikasi Teknis Unit
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">
                <div>
                    <span class="text-xs text-gray-400 block font-medium">Merk / Brand</span>
                    <span class="font-semibold text-gray-800">{{ $unit->brand ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block font-medium">Model / Tipe</span>
                    <span class="font-semibold text-gray-800">{{ $unit->model ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block font-medium">Nomor Seri Sasis</span>
                    <span class="font-mono font-semibold text-gray-800">{{ $unit->serial_number }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block font-medium">Nomor Seri Mesin</span>
                    <span class="font-mono font-semibold text-gray-800">{{ $unit->engine_serial ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block font-medium">Kategori Alat</span>
                    <span class="font-semibold text-gray-800">{{ $unit->category }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block font-medium">Lokasi Penempatan</span>
                    <span class="font-semibold text-gray-800">{{ $unit->location ?? 'Tidak ditentukan' }}</span>
                </div>
                <div class="col-span-2">
                    <span class="text-xs text-gray-400 block font-medium">Catatan Unit</span>
                    <span class="text-gray-700">{{ $unit->notes ?? 'Tidak ada catatan khusus.' }}</span>
                </div>
            </div>
        </div>

        {{-- Two-column tabs: Work Orders & HM Logs --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Work Orders History --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900">Riwayat Work Order (Servis Bengkel)</h3>
                    <span class="text-xs text-gray-500">{{ $unit->workOrders->count() }} WO Terakhir</span>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($unit->workOrders as $wo)
                        <div class="p-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('work-orders.show', $wo) }}" class="font-mono font-bold text-sm text-primary-600 hover:underline">
                                    {{ $wo->wo_number }}
                                </a>
                                <div class="flex items-center gap-2">
                                    <x-status-badge :status="$wo->maintenance_type" />
                                    <x-status-badge :status="$wo->status" />
                                </div>
                            </div>
                            <div class="mt-2 text-xs text-gray-600">
                                @if($wo->fault_description)
                                    <p class="line-clamp-1 italic">"{{ $wo->fault_description }}"</p>
                                @endif
                                <div class="mt-1 flex items-center gap-3 text-gray-400">
                                    <span>Intake: {{ number_format($wo->intake_hm, 1) }} HM</span>
                                    <span>&bull;</span>
                                    <span>Tgl: {{ $wo->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-sm text-gray-500">
                            Belum ada riwayat Work Order untuk unit ini.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Hours Meter (HM) Logs --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900">Log Hours Meter (HM History)</h3>
                    <span class="text-xs text-gray-500">{{ $unit->hmLogs->count() }} Pencatatan</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase font-semibold">
                                <th class="py-2.5 px-3">Tanggal</th>
                                <th class="py-2.5 px-3">Nilai HM</th>
                                <th class="py-2.5 px-3">Kenaikan (&Delta;)</th>
                                <th class="py-2.5 px-3">Sumber</th>
                                <th class="py-2.5 px-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($unit->hmLogs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2.5 px-3 text-gray-600">
                                        {{ \Carbon\Carbon::parse($log->recorded_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-2.5 px-3 font-mono font-bold text-gray-900">
                                        {{ number_format($log->hm_value, 1) }}
                                    </td>
                                    <td class="py-2.5 px-3 text-emerald-600 font-semibold">
                                        +{{ number_format($log->delta_hm, 1) }}
                                    </td>
                                    <td class="py-2.5 px-3 uppercase text-[10px] text-gray-500 font-bold">
                                        {{ $log->source }}
                                    </td>
                                    <td class="py-2.5 px-3 text-gray-500 truncate max-w-[120px]">
                                        {{ $log->notes ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-gray-400">
                                        Belum ada data pencatatan HM.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal Catat Hours Meter --}}
        <div x-show="showHmModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div @click.away="showHmModal = false" class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900">Pencatatan Hours Meter (HM) Baru</h3>
                    <button @click="showHmModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <form action="{{ route('units.record-hm', $unit) }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div class="bg-blue-50 p-3 rounded-lg text-xs text-blue-800">
                            HM saat ini: <strong class="font-mono">{{ number_format($unit->current_hm, 1) }} Jam</strong>.
                            Input HM baru harus &ge; nilai sebelumnya.
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nilai HM Terkini (Jam) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.1" name="hm_value" min="{{ $unit->current_hm }}" value="{{ old('hm_value', $unit->current_hm) }}" required
                                   class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Pembacaan <span class="text-red-500">*</span></label>
                            <input type="date" name="recorded_date" value="{{ date('Y-m-d') }}" required
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Keterangan / Catatan Shift</label>
                            <input type="text" name="notes" placeholder="Catatan operator atau pemeriksaan harian"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2">
                        <button type="button" @click="showHmModal = false" class="px-3.5 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm">
                            Simpan Pembacaan HM
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
