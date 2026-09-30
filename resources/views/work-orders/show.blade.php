<x-layouts.app :title="'WO ' . $workOrder->wo_number" :header="'Work Order: ' . $workOrder->wo_number" :subtitle="'Unit: ' . $workOrder->unit->unit_code . ' (' . $workOrder->unit->name . ')'">
    <div class="space-y-6" x-data="{
        showScheduleModal: false,
        showTaskModal: false,
        showPartModal: false,
        showTransitionModal: false,
        transitionTarget: '',
        transitionTitle: '',
        openTransition(target, title) {
            this.transitionTarget = target;
            this.transitionTitle = title;
            this.showTransitionModal = true;
        }
    }">

        {{-- Top Navigation & Action Controls --}}
        <div class="flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('work-orders.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar WO
            </a>

            {{-- Operational Workflow Transition Buttons --}}
            <div class="flex flex-wrap items-center gap-2">
                {{-- Draft -> Scheduled --}}
                @if($workOrder->status === \App\Enums\WorkOrderStatus::Draft)
                    <button @click="showScheduleModal = true" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                        Jadwalkan & Tugaskan Mekanik
                    </button>
                @endif

                {{-- Scheduled -> In Progress --}}
                @if($workOrder->status === \App\Enums\WorkOrderStatus::Scheduled)
                    <form action="{{ route('work-orders.transition', $workOrder) }}" method="POST">
                        @csrf
                        <input type="hidden" name="target_status" value="in_progress">
                        <button type="submit" onclick="return confirm('Mulai pengerjaan unit sekarang?')" class="px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                            Mulai Pengerjaan (Start Job)
                        </button>
                    </form>
                @endif

                {{-- In Progress Actions --}}
                @if($workOrder->status === \App\Enums\WorkOrderStatus::InProgress)
                    <button @click="openTransition('waiting_parts', 'Tahan Pekerjaan (Menunggu Spare Part)')" class="px-3.5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                        Tahan (Tunggu Parts)
                    </button>
                    <form action="{{ route('work-orders.transition', $workOrder) }}" method="POST">
                        @csrf
                        <input type="hidden" name="target_status" value="qc_testing">
                        <button type="submit" onclick="return confirm('Pengerjaan selesai dan serahkan unit ke tahap QC?')" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                            Pekerjaan Selesai (Serahkan ke QC)
                        </button>
                    </form>
                @endif

                {{-- Waiting Parts -> Resume to In Progress --}}
                @if($workOrder->status === \App\Enums\WorkOrderStatus::WaitingParts)
                    <form action="{{ route('work-orders.transition', $workOrder) }}" method="POST">
                        @csrf
                        <input type="hidden" name="target_status" value="in_progress">
                        <button type="submit" class="px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                            Lanjutkan Pengerjaan (Parts Tersedia)
                        </button>
                    </form>
                @endif

                {{-- QC Testing -> Action to do QC inspection --}}
                @if($workOrder->status === \App\Enums\WorkOrderStatus::QcTesting)
                    <a href="{{ route('qc-checklists.create', ['work_order_id' => $workOrder->id]) }}" class="px-3.5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                        Lakukan Checklist QC Sekarang &rarr;
                    </a>
                @endif

                {{-- Completed -> Released Handover --}}
                @if($workOrder->status === \App\Enums\WorkOrderStatus::Completed)
                    <form action="{{ route('work-orders.transition', $workOrder) }}" method="POST">
                        @csrf
                        <input type="hidden" name="target_status" value="released">
                        <button type="submit" onclick="return confirm('Serahkan unit kembali ke divisi operasi / customer?')" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                            Handover / Release Unit ke Lapangan
                        </button>
                    </form>
                @endif

                @if($workOrder->isEditable())
                    <a href="{{ route('work-orders.edit', $workOrder) }}" class="px-3 py-2 border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50 transition">
                        Edit WO
                    </a>
                @endif
            </div>
        </div>

        {{-- Auto2000 Operational Pipeline Progression Bar --}}
        @php
            $stages = [
                'draft'         => '1. Draft Intake',
                'scheduled'     => '2. Penjadwalan',
                'in_progress'   => '3. Sedang Dikerjakan',
                'qc_testing'    => '4. QC Inspection',
                'completed'     => '5. QC Passed',
                'released'      => '6. Released Handover',
            ];
            $currentStatus = $workOrder->status->value;
            $statusOrder = ['draft', 'scheduled', 'in_progress', 'waiting_parts', 'qc_testing', 'completed', 'released'];
            $currentIndex = array_search($currentStatus, $statusOrder);
            if ($currentIndex === false) $currentIndex = 0;
        @endphp
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm overflow-x-auto">
            <div class="flex items-center justify-between min-w-[700px] text-xs">
                @foreach($stages as $key => $title)
                    @php
                        $keyIndex = array_search($key, $statusOrder);
                        $isPassed = $keyIndex < $currentIndex && $currentStatus !== 'cancelled';
                        $isCurrent = $key === $currentStatus || ($currentStatus === 'waiting_parts' && $key === 'in_progress');
                    @endphp
                    <div class="flex flex-col items-center flex-1 relative">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs mb-1
                            {{ $isCurrent ? 'bg-primary-600 text-white ring-4 ring-primary-100' : ($isPassed ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-400') }}">
                            @if($isPassed)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                {{ $loop->iteration }}
                            @endif
                        </div>
                        <span class="font-semibold text-center {{ $isCurrent ? 'text-primary-700' : ($isPassed ? 'text-gray-900' : 'text-gray-400') }}">
                            {{ $title }}
                        </span>
                    </div>
                    @if(! $loop->last)
                        <div class="h-0.5 flex-1 mx-2 {{ $keyIndex < $currentIndex ? 'bg-emerald-500' : 'bg-gray-200' }}"></div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Work Order Overview Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Unit & Serial</span>
                <div class="mt-1 font-bold text-gray-900 text-base">
                    <a href="{{ route('units.show', $workOrder->unit) }}" class="text-primary-600 hover:underline">
                        {{ $workOrder->unit->unit_code }}
                    </a>
                </div>
                <div class="text-xs text-gray-500">{{ $workOrder->unit->name }}</div>
                <div class="mt-2 text-xs font-mono text-gray-400">SN: {{ $workOrder->unit->serial_number }}</div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tipe & Prioritas</span>
                <div class="mt-2 flex items-center gap-2">
                    <x-status-badge :status="$workOrder->maintenance_type" />
                    <x-status-badge :status="$workOrder->priority" />
                </div>
                <div class="mt-2 text-xs text-gray-500">
                    Intake HM: <span class="font-mono font-semibold text-gray-800">{{ number_format($workOrder->intake_hm, 1) }} Jam</span>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Mekanik Lead</span>
                <div class="mt-1 text-base font-bold text-gray-900">
                    {{ $workOrder->assignedMechanic->name ?? 'Belum Ditugaskan' }}
                </div>
                <div class="text-xs text-gray-500">
                    {{ $workOrder->assignedMechanic->specialization ?? '-' }}
                </div>
                @if($workOrder->estimated_hours)
                    <div class="mt-2 text-xs text-gray-500">
                        Estimasi: <span class="font-semibold text-gray-800">{{ $workOrder->estimated_hours }} Jam</span>
                    </div>
                @endif
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Ringkasan Biaya</span>
                <div class="mt-1 text-xl font-bold font-mono text-gray-900">
                    Rp {{ number_format($workOrder->total_cost, 0, ',', '.') }}
                </div>
                <div class="mt-1 text-xs text-gray-500 flex justify-between">
                    <span>Jasa: Rp {{ number_format($workOrder->total_labor_cost, 0, ',', '.') }}</span>
                    <span>Parts: Rp {{ number_format($workOrder->total_parts_cost, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Fault Description & Notes --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Keluhan Operator / Laporan Intake</h3>
            <p class="text-sm text-gray-800 bg-gray-50 p-4 rounded-lg border border-gray-100 italic">
                "{{ $workOrder->fault_description ?? 'Tidak ada keluhan tertulis (servis berkala rutin).' }}"
            </p>
            @if($workOrder->notes)
                <p class="text-xs text-gray-500 mt-2"><strong>Catatan Workshop:</strong> {{ $workOrder->notes }}</p>
            @endif
        </div>

        {{-- Section: Job Control Tasks (Work Order Items) --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Tugas Mekanik & Jasa Pekerjaan (Job Control)</h3>
                    <p class="text-xs text-gray-500">Daftar item pekerjaan teknis dan jam kerja mekanik</p>
                </div>
                @if($workOrder->isActive() || $workOrder->status === \App\Enums\WorkOrderStatus::Draft)
                    <button @click="showTaskModal = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-900 hover:bg-gray-800 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                        + Tambah Item Pekerjaan
                    </button>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase font-semibold">
                            <th class="py-3 px-4">Deskripsi Tugas</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Mekanik Pelaksana</th>
                            <th class="py-3 px-4">Estimasi Jam</th>
                            <th class="py-3 px-4">Aktual Jam</th>
                            <th class="py-3 px-4">Temuan & Tindakan</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($workOrder->items as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-4 font-semibold text-gray-900">
                                    {{ $item->description }}
                                </td>
                                <td class="py-3 px-4 uppercase text-[10px] text-gray-500 font-bold">
                                    {{ $item->category ?? 'General' }}
                                </td>
                                <td class="py-3 px-4 text-gray-700">
                                    {{ $item->assignedMechanic->name ?? $workOrder->assignedMechanic->name ?? 'Belum Ditugaskan' }}
                                </td>
                                <td class="py-3 px-4 font-mono">{{ $item->estimated_hours ? number_format($item->estimated_hours, 1) . ' Jam' : '-' }}</td>
                                <td class="py-3 px-4 font-mono font-semibold">{{ $item->actual_hours ? number_format($item->actual_hours, 1) . ' Jam' : '-' }}</td>
                                <td class="py-3 px-4 text-gray-600 max-w-xs truncate">
                                    {{ $item->action_taken ?? $item->findings ?? '-' }}
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $itemBadge = match($item->status) {
                                            'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                            'in_progress' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                            default => 'bg-gray-50 text-gray-700 ring-gray-600/20',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold ring-1 ring-inset {{ $itemBadge }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right space-x-1">
                                    @if($item->status !== 'completed' && $workOrder->isActive())
                                        <form action="{{ route('work-orders.items.update-status', [$workOrder, $item]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="text-xs text-emerald-600 hover:text-emerald-800 font-semibold">Tandai Selesai</button>
                                        </form>
                                    @endif
                                    @if($workOrder->isEditable())
                                        <form action="{{ route('work-orders.items.destroy', [$workOrder, $item]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tugas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold ml-2">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-6 text-center text-sm text-gray-500">
                                    Belum ada rincian tugas pengerjaan mekanik.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Section: Parts Requisitions (Spare Part Permintaan Mekanik) --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Permintaan Suku Cadang & Oli (Parts Requisition)</h3>
                    <p class="text-xs text-gray-500">Alur pengeluaran spare part bengkel dari gudang</p>
                </div>
                @if($workOrder->isActive())
                    <a href="{{ route('parts-requisitions.create', ['work_order_id' => $workOrder->id]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                        + Ajukan Spare Part Baru
                    </a>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase font-semibold">
                            <th class="py-3 px-4">No. Requisition</th>
                            <th class="py-3 px-4">Part Number & Nama</th>
                            <th class="py-3 px-4">Diminta</th>
                            <th class="py-3 px-4">Dikeluarkan</th>
                            <th class="py-3 px-4">Harga Satuan</th>
                            <th class="py-3 px-4">Total Biaya</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi Gudang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($workOrder->partsRequisitions as $pr)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-4 font-mono font-bold text-gray-800">{{ $pr->requisition_number }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-gray-900">{{ $pr->sparePart->part_number }}</div>
                                    <div class="text-[11px] text-gray-500">{{ $pr->sparePart->name }}</div>
                                </td>
                                <td class="py-3 px-4 font-mono font-semibold">{{ $pr->quantity_requested }} {{ $pr->sparePart->uom }}</td>
                                <td class="py-3 px-4 font-mono">{{ $pr->quantity_issued }} {{ $pr->sparePart->uom }}</td>
                                <td class="py-3 px-4 font-mono">Rp {{ number_format($pr->unit_price, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 font-mono font-bold text-gray-900">
                                    Rp {{ number_format(($pr->quantity_issued > 0 ? $pr->quantity_issued : $pr->quantity_requested) * $pr->unit_price, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4">
                                    <x-status-badge :status="$pr->status" />
                                </td>
                                <td class="py-3 px-4 text-right space-x-2">
                                    @if($pr->status === \App\Enums\RequisitionStatus::Pending)
                                        <form action="{{ route('parts-requisitions.approve', $pr) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs font-semibold text-blue-600 hover:text-blue-800">Approve</button>
                                        </form>
                                    @endif
                                    @if($pr->status === \App\Enums\RequisitionStatus::Approved)
                                        <form action="{{ route('parts-requisitions.issue', $pr) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800">Keluarkan Part</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-6 text-center text-sm text-gray-500">
                                    Belum ada spare part yang diajukan untuk Work Order ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Section: QC Checklist & Audit Trail --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- QC Inspection Card --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900">Hasil Inspeksi Quality Control (QC)</h3>
                    @if($workOrder->qcChecklist)
                        <x-status-badge :status="$workOrder->qcChecklist->overall_result" />
                    @endif
                </div>
                <div class="p-5">
                    @if($workOrder->qcChecklist)
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500">Inspektor:</span>
                                <span class="font-semibold text-gray-900">{{ $workOrder->qcChecklist->inspectedBy->name ?? 'Foreman QC' }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500">Waktu Inspeksi:</span>
                                <span class="font-semibold text-gray-900">{{ $workOrder->qcChecklist->inspected_at ? $workOrder->qcChecklist->inspected_at->format('d/m/Y H:i') : '-' }}</span>
                            </div>
                            @if($workOrder->qcChecklist->notes)
                                <div class="text-xs bg-gray-50 p-3 rounded border border-gray-100">
                                    <strong>Catatan QC:</strong> {{ $workOrder->qcChecklist->notes }}
                                </div>
                            @endif
                            <div class="pt-2">
                                <a href="{{ route('qc-checklists.show', $workOrder->qcChecklist) }}" class="text-xs font-semibold text-primary-600 hover:underline">
                                    Lihat Lembar Checklist Lengkap &rarr;
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <p class="text-xs text-gray-500 mb-3">Unit belum melewati tahap pemeriksaan mutu QC.</p>
                            @if($workOrder->status === \App\Enums\WorkOrderStatus::QcTesting)
                                <a href="{{ route('qc-checklists.create', ['work_order_id' => $workOrder->id]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary-600 text-white text-xs font-semibold rounded-lg shadow-sm">
                                    Isi Lembar QC Checklist
                                </a>
                            @else
                                <span class="text-xs text-gray-400 italic">Tersedia saat status masuk tahap QC Testing</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Audit Trail (Status Logs) --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-bold text-gray-900">Riwayat Status (Audit Trail)</h3>
                </div>
                <div class="divide-y divide-gray-100 max-h-72 overflow-y-auto">
                    @forelse($workOrder->statusLogs as $log)
                        <div class="p-3 text-xs flex items-start gap-3">
                            <div class="w-2 h-2 mt-1.5 rounded-full bg-primary-500 flex-shrink-0"></div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-gray-800">
                                        {{ ucfirst($log->from_status ?? 'Start') }} &rarr; {{ ucfirst($log->to_status) }}
                                    </span>
                                    <span class="text-gray-400 text-[10px]">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($log->remarks)
                                    <p class="text-gray-600 mt-0.5">{{ $log->remarks }}</p>
                                @endif
                                <span class="text-[10px] text-gray-400">Oleh: {{ $log->changedBy->name ?? 'Sistem' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-gray-400">
                            Belum ada riwayat transisi status.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Modal Penjadwalan & Assign Mekanik --}}
        <div x-show="showScheduleModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div @click.away="showScheduleModal = false" class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900">Penjadwalan & Tugaskan Mekanik</h3>
                    <button @click="showScheduleModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <form action="{{ route('work-orders.transition', $workOrder) }}" method="POST">
                    @csrf
                    <input type="hidden" name="target_status" value="scheduled">
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Mekanik Lead / Teknisi Utama <span class="text-red-500">*</span></label>
                            <select name="assigned_mechanic_id" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                                <option value="">-- Pilih Mekanik --</option>
                                @foreach($mechanics as $m)
                                    <option value="{{ $m->id }}" {{ $workOrder->assigned_mechanic_id == $m->id ? 'selected' : '' }}>
                                        {{ $m->name }} ({{ $m->specialization ?? 'Umum' }} - Level: {{ ucfirst($m->certification_level) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Jadwal Mulai Kerja</label>
                            <input type="datetime-local" name="scheduled_start" value="{{ date('Y-m-d\TH:i') }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Estimasi Waktu Pengerjaan (Jam)</label>
                            <input type="number" step="0.5" name="estimated_hours" value="{{ old('estimated_hours', 4) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2">
                        <button type="button" @click="showScheduleModal = false" class="px-3.5 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm">
                            Konfirmasi Jadwal WO
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Tambah Item Pekerjaan (Task) --}}
        <div x-show="showTaskModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div @click.away="showTaskModal = false" class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900">Tambah Item Pekerjaan Bengkel</h3>
                    <button @click="showTaskModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <form action="{{ route('work-orders.items.store', $workOrder) }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi Tugas / Servis <span class="text-red-500">*</span></label>
                            <input type="text" name="description" placeholder="Contoh: Penggantian Oli Mesin 15W-40 & Filter" required
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori Pekerjaan</label>
                                <select name="category" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                                    <option value="engine">Engine System</option>
                                    <option value="hydraulic">Hydraulic System</option>
                                    <option value="electrical">Electrical & Sensor</option>
                                    <option value="undercarriage">Undercarriage & Final Drive</option>
                                    <option value="preventive">Preventive Maintenance</option>
                                    <option value="general">General Repair</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Estimasi Jam Kerja (Jam)</label>
                                <input type="number" step="0.5" name="estimated_hours" value="1.5" min="0.1" required
                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Temuan Teknis Awal (Optional)</label>
                            <input type="text" name="findings" placeholder="Kondisi atau gejala kerusakan spesifik..."
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2">
                        <button type="button" @click="showTaskModal = false" class="px-3.5 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-black text-white text-xs font-semibold rounded-lg shadow-sm">
                            Simpan Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Custom Transition (misal Waiting Parts) --}}
        <div x-show="showTransitionModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div @click.away="showTransitionModal = false" class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900" x-text="transitionTitle"></h3>
                    <button @click="showTransitionModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <form action="{{ route('work-orders.transition', $workOrder) }}" method="POST">
                    @csrf
                    <input type="hidden" name="target_status" :value="transitionTarget">
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Alasan / Catatan Status</label>
                            <textarea name="remarks" rows="3" placeholder="Masukkan alasan transisi atau suku cadang yang sedang ditunggu..."
                                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none"></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2">
                        <button type="button" @click="showTransitionModal = false" class="px-3.5 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold rounded-lg shadow-sm">
                            Konfirmasi Transisi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
