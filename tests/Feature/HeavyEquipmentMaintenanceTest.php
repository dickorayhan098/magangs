<?php

use App\Enums\MaintenanceType;
use App\Enums\Priority;
use App\Enums\RequisitionStatus;
use App\Enums\UnitStatus;
use App\Enums\WorkOrderStatus;
use App\Models\SparePart;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkOrder;
use App\Services\PartsRequisitionService;
use App\Services\WorkOrderService;

test('dashboard can be rendered with metrics and alerts', function () {
    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertSee('HeavyMaint');
    $response->assertSee('Total Armada Unit');
});

test('can register a new heavy equipment unit and record initial HM', function () {
    $payload = [
        'unit_code' => 'EX-99',
        'name' => 'Hydraulic Excavator Test',
        'brand' => 'Komatsu',
        'model' => 'PC200-8',
        'serial_number' => 'SN-TEST-999',
        'category' => 'Excavator',
        'current_hm' => 1250.5,
        'ownership' => 'owned',
        'location' => 'Pit North',
    ];

    $response = $this->post(route('units.store'), $payload);

    $response->assertRedirect(route('units.index'));
    $this->assertDatabaseHas('units', ['unit_code' => 'EX-99', 'current_hm' => 1250.5]);
    $this->assertDatabaseHas('hm_logs', ['hm_value' => 1250.5]);
});

test('service advisor can intake a work order and update unit status to in_service', function () {
    $unit = Unit::create([
        'unit_code' => 'BD-99',
        'name' => 'Bulldozer Test',
        'serial_number' => 'SN-BD-99',
        'category' => 'Bulldozer',
        'current_hm' => 2000.0,
        'last_pm_hm' => 1500.0,
        'status' => UnitStatus::Available,
        'ownership' => 'owned',
    ]);

    $response = $this->post(route('work-orders.store'), [
        'unit_id' => $unit->id,
        'intake_hm' => 2000.0,
        'maintenance_type' => 'periodic',
        'priority' => 'high',
        'pm_interval' => 500,
        'fault_description' => 'Jadwal PM-500 rutin.',
    ]);

    $this->assertDatabaseHas('work_orders', [
        'unit_id' => $unit->id,
        'maintenance_type' => 'periodic',
        'pm_interval' => 500,
    ]);

    expect($unit->fresh()->status)->toBe(UnitStatus::InService);
});

test('parts requisition workflow deducts warehouse stock upon issue', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $unit = Unit::create([
        'unit_code' => 'DT-88',
        'name' => 'Dump Truck Test',
        'serial_number' => 'SN-DT-88',
        'category' => 'Dump Truck',
        'current_hm' => 3000.0,
        'last_pm_hm' => 2500.0,
        'status' => UnitStatus::InService,
        'ownership' => 'owned',
    ]);

    $wo = WorkOrder::create([
        'wo_number' => 'WO-TEST-001',
        'unit_id' => $unit->id,
        'maintenance_type' => MaintenanceType::Corrective,
        'priority' => Priority::Medium,
        'status' => WorkOrderStatus::InProgress,
        'intake_hm' => 3000.0,
    ]);

    $part = SparePart::create([
        'part_number' => 'FLT-TEST-01',
        'name' => 'Fuel Filter Test',
        'uom' => 'PCS',
        'stock_quantity' => 10,
        'minimum_stock' => 2,
        'reorder_point' => 5,
        'unit_price' => 150000,
    ]);

    $service = app(PartsRequisitionService::class);
    $req = $service->createRequisition([
        'work_order_id' => $wo->id,
        'spare_part_id' => $part->id,
        'quantity_requested' => 3,
    ]);

    expect($req->status)->toBe(RequisitionStatus::Pending);

    // Approve
    $service->approveRequisition($req);
    expect($req->fresh()->status)->toBe(RequisitionStatus::Approved);

    // Issue (potong stok)
    $service->issueRequisition($req);
    expect($req->fresh()->status)->toBe(RequisitionStatus::Issued);
    expect($part->fresh()->stock_quantity)->toBe(7);
});

test('unit release updates unit status back to available and resets PM HM', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $unit = Unit::create([
        'unit_code' => 'EX-10',
        'name' => 'Excavator Release Test',
        'serial_number' => 'SN-EX-10',
        'category' => 'Excavator',
        'current_hm' => 5000.0,
        'last_pm_hm' => 4500.0,
        'status' => UnitStatus::InService,
        'ownership' => 'owned',
    ]);

    $wo = WorkOrder::create([
        'wo_number' => 'WO-TEST-002',
        'unit_id' => $unit->id,
        'maintenance_type' => MaintenanceType::Periodic,
        'priority' => Priority::Medium,
        'status' => WorkOrderStatus::Completed,
        'intake_hm' => 5000.0,
    ]);

    $service = app(WorkOrderService::class);
    $service->releaseUnit($wo);

    expect($wo->fresh()->status)->toBe(WorkOrderStatus::Released);
    expect($unit->fresh()->status)->toBe(UnitStatus::Available);
    expect((float) $unit->fresh()->last_pm_hm)->toBe(5000.0);
});
