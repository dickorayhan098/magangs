<x-layouts.app :title="'Lembar QC ' . $qcChecklist->workOrder->wo_number" header="Lembar Inspeksi Quality Control" :subtitle="'Work Order: ' . $qcChecklist->workOrder->wo_number . ' — Unit: ' . $qcChecklist->workOrder->unit->unit_code">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('qc-checklists.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar QC
            </a>
            <a href="{{ route('work-orders.show', $qcChecklist->workOrder) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                Buka Work Order &rarr;
            </a>
        </div>

        {{-- Inspection Header Card --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-gray-100">
                <div>
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Hasil Akhir Pemeriksaan</span>
                    <div class="mt-1 flex items-center gap-3">
                        <x-status-badge :status="$qcChecklist->overall_result" />
                        <span class="text-xs text-gray-500">
                            Inspektor: <strong class="text-gray-900">{{ $qcChecklist->inspectedBy->name ?? 'Foreman QC' }}</strong>
                        </span>
                    </div>
                </div>
                <div class="text-right text-xs text-gray-500">
                    <div>Waktu Inspeksi: <strong class="text-gray-800">{{ $qcChecklist->inspected_at ? $qcChecklist->inspected_at->format('d F Y, H:i') : '-' }}</strong></div>
                    <div class="mt-1">Status WO Terkait: <x-status-badge :status="$qcChecklist->workOrder->status" /></div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 text-xs">
                <div>
                    <span class="text-gray-400 block">Unit Alat:</span>
                    <span class="font-bold text-gray-900">{{ $qcChecklist->workOrder->unit->unit_code }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Model Alat:</span>
                    <span class="font-semibold text-gray-800">{{ $qcChecklist->workOrder->unit->name }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Serial Number:</span>
                    <span class="font-mono font-semibold text-gray-800">{{ $qcChecklist->workOrder->unit->serial_number }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Mekanik Lead:</span>
                    <span class="font-semibold text-gray-800">{{ $qcChecklist->workOrder->assignedMechanic->name ?? '-' }}</span>
                </div>
            </div>

            @if($qcChecklist->notes)
                <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200 text-xs">
                    <strong class="text-gray-700">Catatan Foreman QC:</strong>
                    <p class="text-gray-600 mt-1">{{ $qcChecklist->notes }}</p>
                </div>
            @endif
        </div>

        {{-- Inspection Items Details --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-sm font-bold text-gray-900">Rincian Lembar Evaluasi Checklist</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @if(is_array($qcChecklist->checklist_items))
                    @foreach($qcChecklist->checklist_items as $idx => $item)
                        <div class="p-4 flex items-center justify-between text-xs hover:bg-gray-50 transition">
                            <div class="flex-1 pr-4">
                                <span class="font-bold text-gray-800">{{ $idx + 1 }}. {{ $item['task'] ?? 'Pemeriksaan' }}</span>
                                @if(!empty($item['remarks']))
                                    <p class="text-gray-500 mt-0.5 italic">Catatan: {{ $item['remarks'] }}</p>
                                @endif
                            </div>
                            <div>
                                @php
                                    $res = $item['result'] ?? 'na';
                                    $badge = match($res) {
                                        'pass' => 'bg-emerald-100 text-emerald-800',
                                        'fail' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded text-[11px] font-bold uppercase {{ $badge }}">
                                    {{ $res }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="p-6 text-center text-sm text-gray-500">
                        Data item checklist tidak tersedia dalam format terstruktur.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
