<?php

use App\Enums\WorkOrderStatus;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MechanicController;
use App\Http\Controllers\PartsRequisitionController;
use App\Http\Controllers\QcChecklistController;
use App\Http\Controllers\SparePartController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\WorkOrderItemController;
use App\Models\Mechanic;
use App\Models\SparePart;
use App\Models\Unit;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\Route;

// Portal Auto2000 Digiroom Heavy Equipment
Route::get('/', function () {
    $featuredUnits = Unit::take(4)->get();
    $popularParts = SparePart::take(4)->get();
    $activeWorkOrdersCount = WorkOrder::whereIn('status', [
        WorkOrderStatus::Scheduled,
        WorkOrderStatus::InProgress,
        WorkOrderStatus::WaitingParts,
        WorkOrderStatus::QcTesting,
    ])->count();
    $totalFleetCount = Unit::count();
    $certifiedMechanicsCount = Mechanic::where('is_active', true)->count();

    return view('welcome', compact(
        'featuredUnits',
        'popularParts',
        'activeWorkOrdersCount',
        'totalFleetCount',
        'certifiedMechanicsCount'
    ));
})->name('home');

// Dashboard Internal Workshop Job Control
Route::get('/dashboard', DashboardController::class)->name('dashboard');

// Units Management & HM Logs
Route::post('units/{unit}/hm', [UnitController::class, 'recordHm'])->name('units.record-hm');
Route::resource('units', UnitController::class);

// Work Orders & Job Control
Route::post('work-orders/{workOrder}/transition', [WorkOrderController::class, 'transition'])->name('work-orders.transition');
Route::post('work-orders/{workOrder}/items', [WorkOrderItemController::class, 'store'])->name('work-orders.items.store');
Route::patch('work-orders/{workOrder}/items/{item}/status', [WorkOrderItemController::class, 'updateStatus'])->name('work-orders.items.update-status');
Route::delete('work-orders/{workOrder}/items/{item}', [WorkOrderItemController::class, 'destroy'])->name('work-orders.items.destroy');
Route::resource('work-orders', WorkOrderController::class);

// Mechanics
Route::resource('mechanics', MechanicController::class);

// Spare Parts Inventory
Route::resource('spare-parts', SparePartController::class);

// Parts Requisition Workflow
Route::post('parts-requisitions/{requisition}/approve', [PartsRequisitionController::class, 'approve'])->name('parts-requisitions.approve');
Route::post('parts-requisitions/{requisition}/issue', [PartsRequisitionController::class, 'issue'])->name('parts-requisitions.issue');
Route::post('parts-requisitions/{requisition}/cancel', [PartsRequisitionController::class, 'cancel'])->name('parts-requisitions.cancel');
Route::resource('parts-requisitions', PartsRequisitionController::class)->only(['index', 'create', 'store']);

// QC Checklists
Route::resource('qc-checklists', QcChecklistController::class)->only(['index', 'create', 'store', 'show']);
