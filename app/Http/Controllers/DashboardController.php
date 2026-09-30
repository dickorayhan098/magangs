<?php

namespace App\Http\Controllers;

use App\Enums\UnitStatus;
use App\Enums\WorkOrderStatus;
use App\Models\Mechanic;
use App\Models\SparePart;
use App\Models\Unit;
use App\Models\WorkOrder;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $totalUnits = Unit::count();
        $unitsAvailable = Unit::where('status', UnitStatus::Available)->count();
        $unitsInService = Unit::where('status', UnitStatus::InService)->count();
        $unitsBreakdown = Unit::where('status', UnitStatus::Breakdown)->count();

        $totalWoActive = WorkOrder::whereIn('status', [
            WorkOrderStatus::Scheduled,
            WorkOrderStatus::InProgress,
            WorkOrderStatus::WaitingParts,
            WorkOrderStatus::QcTesting,
        ])->count();

        $woByStatus = WorkOrder::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recentWorkOrders = WorkOrder::with(['unit', 'assignedMechanic'])
            ->latest()
            ->limit(10)
            ->get();

        $mechanicsAvailable = Mechanic::where('is_active', true)
            ->where('is_available', true)
            ->count();

        $lowStockParts = SparePart::whereColumn('stock_quantity', '<=', 'minimum_stock')
            ->orderBy('stock_quantity')
            ->limit(5)
            ->get();

        $unitsApproachingPm = Unit::where('status', UnitStatus::Available)
            ->get()
            ->filter(fn (Unit $u) => $u->isApproachingPm())
            ->take(5);

        return view('dashboard', compact(
            'totalUnits',
            'unitsAvailable',
            'unitsInService',
            'unitsBreakdown',
            'totalWoActive',
            'woByStatus',
            'recentWorkOrders',
            'mechanicsAvailable',
            'lowStockParts',
            'unitsApproachingPm',
        ));
    }
}
