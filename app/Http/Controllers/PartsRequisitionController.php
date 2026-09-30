<?php

namespace App\Http\Controllers;

use App\Enums\WorkOrderStatus;
use App\Models\PartsRequisition;
use App\Models\SparePart;
use App\Models\WorkOrder;
use App\Services\PartsRequisitionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartsRequisitionController extends Controller
{
    public function __construct(
        private PartsRequisitionService $requisitionService,
    ) {}

    public function index(Request $request): View
    {
        $query = PartsRequisition::with(['workOrder.unit', 'sparePart', 'requestedBy', 'approvedBy']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('requisition_number', 'like', "%{$search}%")
                    ->orWhereHas('sparePart', fn ($sp) => $sp->where('name', 'like', "%{$search}%")->orWhere('part_number', 'like', "%{$search}%"))
                    ->orWhereHas('workOrder', fn ($wo) => $wo->where('wo_number', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $requisitions = $query->latest()->paginate(15)->withQueryString();

        return view('parts-requisitions.index', compact('requisitions'));
    }

    public function create(Request $request): View
    {
        $selectedWoId = $request->input('work_order_id');
        $workOrders = WorkOrder::whereIn('status', [
            WorkOrderStatus::Scheduled,
            WorkOrderStatus::InProgress,
            WorkOrderStatus::WaitingParts,
        ])->with('unit')->latest()->get();

        $spareParts = SparePart::orderBy('name')->get();

        return view('parts-requisitions.create', compact('workOrders', 'spareParts', 'selectedWoId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'work_order_id' => 'required|exists:work_orders,id',
            'spare_part_id' => 'required|exists:spare_parts,id',
            'quantity_requested' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        try {
            $requisition = $this->requisitionService->createRequisition($validated);

            return redirect()->route('parts-requisitions.index')
                ->with('success', "Permintaan spare part {$requisition->requisition_number} berhasil diajukan.");
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function approve(PartsRequisition $requisition): RedirectResponse
    {
        try {
            $this->requisitionService->approveRequisition($requisition);

            return back()->with('success', "Permintaan {$requisition->requisition_number} berhasil disetujui.");
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function issue(Request $request, PartsRequisition $requisition): RedirectResponse
    {
        $qty = $request->filled('quantity_issued') ? (int) $request->input('quantity_issued') : null;

        try {
            $this->requisitionService->issueRequisition($requisition, $qty);

            return back()->with('success', "Part untuk {$requisition->requisition_number} berhasil dikeluarkan dari gudang.");
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function cancel(Request $request, PartsRequisition $requisition): RedirectResponse
    {
        $reason = $request->input('reason', 'Dibatalkan oleh pengguna.');

        try {
            $this->requisitionService->cancelRequisition($requisition, $reason);

            return back()->with('success', "Permintaan {$requisition->requisition_number} telah dibatalkan.");
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
