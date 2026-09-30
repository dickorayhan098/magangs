<?php

namespace App\Http\Controllers;

use App\Enums\WorkOrderStatus;
use App\Models\QcChecklist;
use App\Models\WorkOrder;
use App\Services\WorkOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QcChecklistController extends Controller
{
    public function __construct(
        private WorkOrderService $workOrderService,
    ) {}

    public function index(Request $request): View
    {
        $query = QcChecklist::with(['workOrder.unit', 'inspectedBy']);

        if ($request->filled('result')) {
            $query->where('overall_result', $request->input('result'));
        }

        $checklists = $query->latest('inspected_at')->paginate(15)->withQueryString();

        return view('qc-checklists.index', compact('checklists'));
    }

    public function create(Request $request): View
    {
        $selectedWoId = $request->input('work_order_id');

        $pendingWorkOrders = WorkOrder::where('status', WorkOrderStatus::QcTesting)
            ->with('unit')
            ->latest()
            ->get();

        return view('qc-checklists.create', compact('pendingWorkOrders', 'selectedWoId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'work_order_id' => 'required|exists:work_orders,id',
            'overall_result' => 'required|in:pass,fail,conditional',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.task' => 'required|string',
            'items.*.result' => 'required|in:pass,fail,na',
            'items.*.remarks' => 'nullable|string',
        ]);

        $workOrder = WorkOrder::findOrFail($validated['work_order_id']);

        $checklist = QcChecklist::create([
            'work_order_id' => $workOrder->id,
            'inspected_by_user_id' => Auth::id(),
            'checklist_items' => $validated['items'],
            'overall_result' => $validated['overall_result'],
            'notes' => $validated['notes'] ?? null,
            'inspected_at' => now(),
        ]);

        // Jika overall result pass, panggil approveQc di service
        if ($validated['overall_result'] === 'pass') {
            $this->workOrderService->approveQc($workOrder, $validated['notes'] ?? 'Lulus QC Checklist');
        } elseif ($validated['overall_result'] === 'fail') {
            $this->workOrderService->rejectQc($workOrder, $validated['notes'] ?? 'Gagal inspeksi QC');
        }

        return redirect()->route('qc-checklists.show', $checklist)
            ->with('success', 'Hasil inspeksi QC berhasil disimpan.');
    }

    public function show(QcChecklist $qcChecklist): View
    {
        $qcChecklist->load(['workOrder.unit', 'workOrder.assignedMechanic', 'inspectedBy']);

        return view('qc-checklists.show', compact('qcChecklist'));
    }
}
