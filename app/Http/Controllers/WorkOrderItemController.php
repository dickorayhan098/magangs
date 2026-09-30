<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WorkOrderItemController extends Controller
{
    public function store(Request $request, WorkOrder $workOrder): RedirectResponse
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'category' => 'nullable|string|in:engine,hydraulic,electrical,undercarriage,preventive,general',
            'assigned_mechanic_id' => 'nullable|exists:mechanics,id',
            'estimated_hours' => 'nullable|numeric|min:0',
            'findings' => 'nullable|string',
            'action_taken' => 'nullable|string',
        ]);

        $workOrder->items()->create([
            'description' => $validated['description'],
            'category' => $validated['category'] ?? 'general',
            'assigned_mechanic_id' => $validated['assigned_mechanic_id'] ?? $workOrder->assigned_mechanic_id,
            'estimated_hours' => $validated['estimated_hours'] ?? 1.0,
            'status' => 'pending',
            'findings' => $validated['findings'] ?? null,
            'action_taken' => $validated['action_taken'] ?? null,
        ]);

        $workOrder->recalculateCosts();

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Item pekerjaan berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, WorkOrder $workOrder, WorkOrderItem $item): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,skipped',
            'actual_hours' => 'nullable|numeric|min:0',
            'action_taken' => 'nullable|string',
        ]);

        $data = ['status' => $validated['status']];
        if (isset($validated['actual_hours'])) {
            $data['actual_hours'] = $validated['actual_hours'];
        } elseif ($validated['status'] === 'completed' && ! $item->actual_hours) {
            $data['actual_hours'] = $item->estimated_hours ?? 1.0;
        }

        if (isset($validated['action_taken'])) {
            $data['action_taken'] = $validated['action_taken'];
        }

        $item->update($data);
        $workOrder->recalculateCosts();

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', "Status tugas \"{$item->description}\" diperbarui.");
    }

    public function destroy(WorkOrder $workOrder, WorkOrderItem $item): RedirectResponse
    {
        $item->delete();
        $workOrder->recalculateCosts();

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Item pekerjaan berhasil dihapus.');
    }
}
