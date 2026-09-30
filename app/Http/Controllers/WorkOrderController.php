<?php

namespace App\Http\Controllers;

use App\Enums\WorkOrderStatus;
use App\Models\Mechanic;
use App\Models\Unit;
use App\Models\WorkOrder;
use App\Services\WorkOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkOrderController extends Controller
{
    public function __construct(
        private WorkOrderService $workOrderService,
    ) {}

    public function index(Request $request): View
    {
        $query = WorkOrder::with(['unit', 'assignedMechanic']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('wo_number', 'like', "%{$search}%")
                    ->orWhereHas('unit', fn ($q2) => $q2->where('name', 'like', "%{$search}%")->orWhere('unit_code', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('maintenance_type')) {
            $query->where('maintenance_type', $request->input('maintenance_type'));
        }

        $workOrders = $query->latest()->paginate(15)->withQueryString();

        return view('work-orders.index', compact('workOrders'));
    }

    public function create(): View
    {
        $units = Unit::where('status', '!=', 'decommissioned')->orderBy('unit_code')->get();
        $mechanics = Mechanic::where('is_active', true)->orderBy('name')->get();

        return view('work-orders.create', compact('units', 'mechanics'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'intake_hm' => 'required|numeric|min:0',
            'maintenance_type' => 'required|string|in:periodic,corrective,breakdown,overhaul',
            'priority' => 'required|string|in:low,medium,high,critical',
            'fault_description' => 'nullable|string',
            'pm_interval' => 'nullable|integer|in:250,500,1000,2000',
            'notes' => 'nullable|string',
        ]);

        try {
            $workOrder = $this->workOrderService->createIntake($validated);

            return redirect()->route('work-orders.show', $workOrder)
                ->with('success', "Work Order {$workOrder->wo_number} berhasil dibuat.");
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(WorkOrder $workOrder): View
    {
        $workOrder->load([
            'unit',
            'assignedMechanic',
            'reportedBy',
            'foreman',
            'items.assignedMechanic',
            'partsRequisitions.sparePart',
            'qcChecklist',
            'statusLogs.changedBy',
        ]);

        $mechanics = Mechanic::where('is_active', true)->get();

        return view('work-orders.show', compact('workOrder', 'mechanics'));
    }

    public function edit(WorkOrder $workOrder): View
    {
        if (! $workOrder->isEditable()) {
            return redirect()->route('work-orders.show', $workOrder)
                ->with('error', 'WO tidak bisa diedit karena sudah dalam proses.');
        }

        $units = Unit::orderBy('unit_code')->get();
        $mechanics = Mechanic::where('is_active', true)->get();

        return view('work-orders.edit', compact('workOrder', 'units', 'mechanics'));
    }

    public function update(Request $request, WorkOrder $workOrder): RedirectResponse
    {
        if (! $workOrder->isEditable()) {
            return redirect()->route('work-orders.show', $workOrder)
                ->with('error', 'WO tidak bisa diedit karena sudah dalam proses.');
        }

        $validated = $request->validate([
            'priority' => 'required|string|in:low,medium,high,critical',
            'fault_description' => 'nullable|string',
            'pm_interval' => 'nullable|integer|in:250,500,1000,2000',
            'assigned_mechanic_id' => 'nullable|exists:mechanics,id',
            'scheduled_start' => 'nullable|date',
            'scheduled_end' => 'nullable|date|after_or_equal:scheduled_start',
            'estimated_hours' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $workOrder->update($validated);

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Work Order berhasil diperbarui.');
    }

    /**
     * Transisi status WO.
     */
    public function transition(Request $request, WorkOrder $workOrder): RedirectResponse
    {
        $validated = $request->validate([
            'target_status' => 'required|string',
            'remarks' => 'nullable|string',
            'assigned_mechanic_id' => 'nullable|exists:mechanics,id',
            'scheduled_start' => 'nullable|date',
            'scheduled_end' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric',
        ]);

        try {
            $targetStatus = WorkOrderStatus::from($validated['target_status']);

            // Jika schedule, update assignment juga
            if ($targetStatus === WorkOrderStatus::Scheduled) {
                $workOrder->update([
                    'assigned_mechanic_id' => $validated['assigned_mechanic_id'] ?? $workOrder->assigned_mechanic_id,
                    'scheduled_start' => $validated['scheduled_start'] ?? $workOrder->scheduled_start,
                    'scheduled_end' => $validated['scheduled_end'] ?? $workOrder->scheduled_end,
                    'estimated_hours' => $validated['estimated_hours'] ?? $workOrder->estimated_hours,
                ]);
            }

            match ($targetStatus) {
                WorkOrderStatus::InProgress => $this->workOrderService->startWork($workOrder),
                WorkOrderStatus::QcTesting => $this->workOrderService->submitForQc($workOrder),
                WorkOrderStatus::Completed => $this->workOrderService->approveQc($workOrder, $validated['remarks'] ?? null),
                WorkOrderStatus::Released => $this->workOrderService->releaseUnit($workOrder),
                default => $this->workOrderService->transitionStatus($workOrder, $targetStatus, $validated['remarks'] ?? null),
            };

            return redirect()->route('work-orders.show', $workOrder)
                ->with('success', "Status berhasil diubah ke: {$targetStatus->label()}");
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
