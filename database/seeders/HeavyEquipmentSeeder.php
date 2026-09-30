<?php

namespace Database\Seeders;

use App\Enums\MaintenanceType;
use App\Enums\Priority;
use App\Enums\RequisitionStatus;
use App\Enums\UnitStatus;
use App\Enums\WorkOrderStatus;
use App\Models\HmLog;
use App\Models\Mechanic;
use App\Models\PartsRequisition;
use App\Models\QcChecklist;
use App\Models\SparePart;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use App\Models\WorkOrderStatusLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HeavyEquipmentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create System Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@heavymaint.com'],
            [
                'name' => 'Service Advisor & Admin',
                'password' => Hash::make('password'),
            ]
        );

        $foreman = User::firstOrCreate(
            ['email' => 'foreman@heavymaint.com'],
            [
                'name' => 'Foreman QC Bambang',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Create Master Mechanics
        $mechanics = [
            [
                'employee_id' => 'MEK-001',
                'name' => 'Rahmat Hidayat',
                'phone' => '081234567801',
                'specialization' => 'Hydraulic Specialist',
                'certification_level' => 'specialist',
                'is_active' => true,
                'is_available' => false,
                'notes' => 'Komatsu Certified Master Hydraulic Technician (PC200-PC400).',
            ],
            [
                'employee_id' => 'MEK-002',
                'name' => 'Agus Prasetyo',
                'phone' => '081234567802',
                'specialization' => 'Engine Specialist',
                'certification_level' => 'lead',
                'is_active' => true,
                'is_available' => true,
                'notes' => 'Spesialis Cummins, Komatsu SAA6D107E, CAT C7.1 ACERT.',
            ],
            [
                'employee_id' => 'MEK-003',
                'name' => 'Denny Setiawan',
                'phone' => '081234567803',
                'specialization' => 'Electrical & Controls',
                'certification_level' => 'senior',
                'is_active' => true,
                'is_available' => true,
                'notes' => 'Troubleshooting wiring harness, ECU, monitor panel, sensors.',
            ],
            [
                'employee_id' => 'MEK-004',
                'name' => 'Slamet Riyadi',
                'phone' => '081234567804',
                'specialization' => 'Undercarriage Specialist',
                'certification_level' => 'senior',
                'is_active' => true,
                'is_available' => true,
                'notes' => 'Track link replacement, roller rebuild, idler alignment.',
            ],
            [
                'employee_id' => 'MEK-005',
                'name' => 'Fajar Pratama',
                'phone' => '081234567805',
                'specialization' => 'General Mechanic',
                'certification_level' => 'junior',
                'is_active' => true,
                'is_available' => true,
                'notes' => 'Periodic maintenance service, filter replacement, fluid sampling.',
            ],
        ];

        $mechanicModels = [];
        foreach ($mechanics as $m) {
            $mechanicModels[] = Mechanic::updateOrCreate(['employee_id' => $m['employee_id']], $m);
        }

        // 3. Create Master Spare Parts
        $parts = [
            [
                'part_number' => '6732-71-6120',
                'name' => 'Engine Oil Filter (Komatsu)',
                'brand' => 'Komatsu Genuine',
                'category' => 'Filters',
                'uom' => 'PCS',
                'stock_quantity' => 24,
                'minimum_stock' => 5,
                'reorder_point' => 10,
                'unit_price' => 385000,
                'warehouse_location' => 'RAK-A-01',
                'is_critical' => true,
            ],
            [
                'part_number' => '600-319-3550',
                'name' => 'Fuel Pre-Filter Water Separator',
                'brand' => 'Komatsu Genuine',
                'category' => 'Filters',
                'uom' => 'PCS',
                'stock_quantity' => 18,
                'minimum_stock' => 4,
                'reorder_point' => 8,
                'unit_price' => 460000,
                'warehouse_location' => 'RAK-A-02',
                'is_critical' => true,
            ],
            [
                'part_number' => '07063-01100',
                'name' => 'Hydraulic Return Filter Element',
                'brand' => 'Komatsu Genuine',
                'category' => 'Hydraulics',
                'uom' => 'PCS',
                'stock_quantity' => 3, // LOW STOCK
                'minimum_stock' => 5,
                'reorder_point' => 8,
                'unit_price' => 1250000,
                'warehouse_location' => 'RAK-B-04',
                'is_critical' => true,
            ],
            [
                'part_number' => '1R-0716',
                'name' => 'Advanced Efficiency Fuel Filter (CAT)',
                'brand' => 'Caterpillar',
                'category' => 'Filters',
                'uom' => 'PCS',
                'stock_quantity' => 12,
                'minimum_stock' => 4,
                'reorder_point' => 8,
                'unit_price' => 520000,
                'warehouse_location' => 'RAK-A-05',
                'is_critical' => true,
            ],
            [
                'part_number' => 'LUB-15W40-DH',
                'name' => 'Heavy Duty Diesel Engine Oil SAE 15W-40',
                'brand' => 'Shell Rimula R4X',
                'category' => 'Oils & Fluids',
                'uom' => 'LTR',
                'stock_quantity' => 320,
                'minimum_stock' => 100,
                'reorder_point' => 200,
                'unit_price' => 58000,
                'warehouse_location' => 'DRUM-BAY-01',
                'is_critical' => false,
            ],
            [
                'part_number' => 'LUB-HYD-ISO68',
                'name' => 'Anti-Wear Hydraulic Oil ISO VG 68',
                'brand' => 'Shell Tellus S2',
                'category' => 'Oils & Fluids',
                'uom' => 'LTR',
                'stock_quantity' => 40, // LOW STOCK
                'minimum_stock' => 80,
                'reorder_point' => 150,
                'unit_price' => 62000,
                'warehouse_location' => 'DRUM-BAY-02',
                'is_critical' => true,
            ],
            [
                'part_number' => '207-70-14151',
                'name' => 'Bucket Tooth Point (Standard Tip)',
                'brand' => 'Komatsu',
                'category' => 'Fasteners & Seals',
                'uom' => 'PCS',
                'stock_quantity' => 30,
                'minimum_stock' => 10,
                'reorder_point' => 20,
                'unit_price' => 275000,
                'warehouse_location' => 'BIN-C-10',
                'is_critical' => false,
            ],
            [
                'part_number' => 'SEAL-KIT-BOOM-PC200',
                'name' => 'Hydraulic Boom Cylinder Seal Kit',
                'brand' => 'NOK Japan',
                'category' => 'Hydraulics',
                'uom' => 'SET',
                'stock_quantity' => 2, // LOW STOCK
                'minimum_stock' => 3,
                'reorder_point' => 5,
                'unit_price' => 1850000,
                'warehouse_location' => 'RAK-B-01',
                'is_critical' => true,
            ],
        ];

        $sparePartModels = [];
        foreach ($parts as $p) {
            $sparePartModels[] = SparePart::updateOrCreate(['part_number' => $p['part_number']], $p);
        }

        // 4. Create Master Units
        $unitsData = [
            [
                'unit_code' => 'EX-01',
                'name' => 'Hydraulic Excavator 20 Ton',
                'brand' => 'Komatsu',
                'model' => 'PC200-8M0',
                'serial_number' => 'C60241-KMT',
                'year_manufactured' => 2021,
                'engine_serial' => 'SAA6D107E-1-26514',
                'category' => 'Excavator',
                'current_hm' => 4742.5,
                'last_pm_hm' => 4500.0,
                'status' => UnitStatus::Available,
                'location' => 'Pit 2 South',
                'ownership' => 'owned',
                'notes' => 'Unit prima, mendekati interval PM-250 (4750 HM).',
            ],
            [
                'unit_code' => 'EX-02',
                'name' => 'Hydraulic Excavator 20 Ton',
                'brand' => 'Caterpillar',
                'model' => '320D Series 2',
                'serial_number' => 'CAT0320D-D78921',
                'year_manufactured' => 2020,
                'engine_serial' => 'C7.1-ACERT-90812',
                'category' => 'Excavator',
                'current_hm' => 6120.0,
                'last_pm_hm' => 6000.0,
                'status' => UnitStatus::InService,
                'location' => 'Workshop Bay 1',
                'ownership' => 'owned',
                'notes' => 'Sedang pengerjaan kebocoran boom cylinder di bengkel.',
            ],
            [
                'unit_code' => 'BD-01',
                'name' => 'Crawler Bulldozer 200 HP',
                'brand' => 'Komatsu',
                'model' => 'D85ESS-2',
                'serial_number' => 'BD-KMT-D85-11029',
                'year_manufactured' => 2019,
                'engine_serial' => 'SA6D125E-2-44109',
                'category' => 'Bulldozer',
                'current_hm' => 8490.0,
                'last_pm_hm' => 8000.0,
                'status' => UnitStatus::Available,
                'location' => 'Disposal Area East',
                'ownership' => 'owned',
                'notes' => 'Mendekati PM-500 (8500 HM). Sisa 10 HM.',
            ],
            [
                'unit_code' => 'DT-01',
                'name' => 'Articulated Dump Truck 40 Ton',
                'brand' => 'Volvo',
                'model' => 'A40G',
                'serial_number' => 'VLV-A40G-55102',
                'year_manufactured' => 2022,
                'engine_serial' => 'D13J-99120',
                'category' => 'Dump Truck',
                'current_hm' => 3240.0,
                'last_pm_hm' => 3000.0,
                'status' => UnitStatus::Breakdown,
                'location' => 'Hauling Road KM 14',
                'ownership' => 'owned',
                'notes' => 'Transmission error code 43-2. Perlu evakuasi & corrective repair.',
            ],
            [
                'unit_code' => 'WL-01',
                'name' => 'Wheel Loader 3.5 m3',
                'brand' => 'Komatsu',
                'model' => 'WA380-6',
                'serial_number' => 'WA380-H60912',
                'year_manufactured' => 2021,
                'engine_serial' => 'SAA6D107E-1-31088',
                'category' => 'Wheel Loader',
                'current_hm' => 5210.0,
                'last_pm_hm' => 5000.0,
                'status' => UnitStatus::Available,
                'location' => 'Crusher ROM Stockpile',
                'ownership' => 'rental',
                'notes' => 'Kondisi normal siap pakai.',
            ],
        ];

        $unitModels = [];
        foreach ($unitsData as $ud) {
            $unit = Unit::updateOrCreate(['unit_code' => $ud['unit_code']], $ud);
            $unitModels[$ud['unit_code']] = $unit;

            // Seed initial HM Log
            HmLog::firstOrCreate(
                ['unit_id' => $unit->id, 'hm_value' => $unit->current_hm],
                [
                    'previous_hm' => $unit->current_hm - 8.5,
                    'delta_hm' => 8.5,
                    'recorded_date' => now()->toDateString(),
                    'source' => 'manual',
                    'notes' => 'Pencatatan HM rutin shift pagi.',
                ]
            );
        }

        // 5. Create Work Order 1: EX-02 (In Progress - Corrective Repair Boom Seal)
        $ex02 = $unitModels['EX-02'];
        $wo1 = WorkOrder::updateOrCreate(
            ['wo_number' => 'WO-'.now()->format('Ymd').'-0001'],
            [
                'unit_id' => $ex02->id,
                'maintenance_type' => MaintenanceType::Corrective,
                'priority' => Priority::High,
                'status' => WorkOrderStatus::InProgress,
                'reported_by_user_id' => $admin->id,
                'foreman_id' => $foreman->id,
                'assigned_mechanic_id' => $mechanicModels[0]->id, // Rahmat (Hydraulic Specialist)
                'intake_hm' => 6120.0,
                'scheduled_start' => now()->subHours(4),
                'scheduled_end' => now()->addHours(3),
                'actual_start' => now()->subHours(3),
                'estimated_hours' => 6.0,
                'fault_description' => 'Silinder boom kanan menetes oli hidrolik deras saat menahan beban bucket penuh.',
                'total_labor_cost' => 450000,
                'total_parts_cost' => 3100000,
                'total_cost' => 3550000,
                'notes' => 'Unit diparkir di Workshop Bay 1.',
            ]
        );

        // Work Order Items for WO 1
        WorkOrderItem::firstOrCreate(
            ['work_order_id' => $wo1->id, 'description' => 'Pembongkaran silinder boom hidrolik & inspeksi rod'],
            [
                'category' => 'hydraulic',
                'assigned_mechanic_id' => $mechanicModels[0]->id,
                'estimated_hours' => 3.0,
                'actual_hours' => 3.0,
                'status' => 'completed',
                'findings' => 'Seal rod getas dan aus akibat jam kerja tinggi.',
                'action_taken' => 'Silinder dibongkar dan dibersihkan.',
            ]
        );
        WorkOrderItem::firstOrCreate(
            ['work_order_id' => $wo1->id, 'description' => 'Pemasangan seal kit baru & bleeding udara sistem hidrolik'],
            [
                'category' => 'hydraulic',
                'assigned_mechanic_id' => $mechanicModels[0]->id,
                'estimated_hours' => 3.0,
                'status' => 'in_progress',
                'action_taken' => 'Proses instalasi seal kit baru.',
            ]
        );

        // Status Logs for WO 1
        WorkOrderStatusLog::firstOrCreate(
            ['work_order_id' => $wo1->id, 'to_status' => 'draft'],
            ['from_status' => null, 'changed_by_user_id' => $admin->id, 'remarks' => 'Intake Service Advisor selesai.']
        );
        WorkOrderStatusLog::firstOrCreate(
            ['work_order_id' => $wo1->id, 'to_status' => 'scheduled'],
            ['from_status' => 'draft', 'changed_by_user_id' => $admin->id, 'remarks' => 'Ditugaskan ke Rahmat Hidayat.']
        );
        WorkOrderStatusLog::firstOrCreate(
            ['work_order_id' => $wo1->id, 'to_status' => 'in_progress'],
            ['from_status' => 'scheduled', 'changed_by_user_id' => $mechanicModels[0]->id, 'remarks' => 'Mulai pembongkaran silinder boom.']
        );

        // Parts Requisition for WO 1
        $pr1 = PartsRequisition::firstOrCreate(
            ['requisition_number' => 'PR-'.now()->format('Ymd').'-0001'],
            [
                'work_order_id' => $wo1->id,
                'spare_part_id' => $sparePartModels[7]->id, // SEAL-KIT-BOOM
                'quantity_requested' => 1,
                'quantity_issued' => 1,
                'status' => RequisitionStatus::Issued,
                'requested_by_user_id' => $admin->id,
                'approved_by_user_id' => $foreman->id,
                'approved_at' => now()->subHours(2),
                'unit_price' => 1850000,
                'total_price' => 1850000,
                'notes' => 'Seal kit silinder boom kanan.',
            ]
        );
        $pr2 = PartsRequisition::firstOrCreate(
            ['requisition_number' => 'PR-'.now()->format('Ymd').'-0002'],
            [
                'work_order_id' => $wo1->id,
                'spare_part_id' => $sparePartModels[5]->id, // HYD OIL 68
                'quantity_requested' => 20,
                'quantity_issued' => 20,
                'status' => RequisitionStatus::Issued,
                'requested_by_user_id' => $admin->id,
                'approved_by_user_id' => $foreman->id,
                'approved_at' => now()->subHours(2),
                'unit_price' => 62000,
                'total_price' => 1240000,
                'notes' => 'Top up oli hidrolik setelah pembongkaran.',
            ]
        );

        // 6. Create Work Order 2: BD-01 (QC Testing - Periodic Maintenance PM-500)
        $bd01 = $unitModels['BD-01'];
        $wo2 = WorkOrder::updateOrCreate(
            ['wo_number' => 'WO-'.now()->format('Ymd').'-0002'],
            [
                'unit_id' => $bd01->id,
                'maintenance_type' => MaintenanceType::Periodic,
                'pm_interval' => 500,
                'next_pm_hm' => 8500.0,
                'priority' => Priority::Medium,
                'status' => WorkOrderStatus::QcTesting,
                'reported_by_user_id' => $admin->id,
                'foreman_id' => $foreman->id,
                'assigned_mechanic_id' => $mechanicModels[1]->id, // Agus (Engine Specialist)
                'intake_hm' => 8490.0,
                'scheduled_start' => now()->subHours(6),
                'scheduled_end' => now()->subHours(1),
                'actual_start' => now()->subHours(5),
                'actual_end' => now()->subHour(),
                'actual_hours' => 4.0,
                'estimated_hours' => 4.5,
                'total_labor_cost' => 300000,
                'total_parts_cost' => 1800000,
                'total_cost' => 2100000,
                'notes' => 'PM-500 rutin sesuai jadwal HM.',
            ]
        );

        WorkOrderItem::firstOrCreate(
            ['work_order_id' => $wo2->id, 'description' => 'Penggantian Oli Mesin 15W-40 & Filter Oli'],
            [
                'category' => 'preventive',
                'assigned_mechanic_id' => $mechanicModels[1]->id,
                'estimated_hours' => 2.0,
                'actual_hours' => 2.0,
                'status' => 'completed',
                'action_taken' => 'Oli mesin 15W-40 didrain dan filter oli diganti baru.',
            ]
        );
        WorkOrderItem::firstOrCreate(
            ['work_order_id' => $wo2->id, 'description' => 'Penggantian Filter Solar & Water Separator'],
            [
                'category' => 'preventive',
                'assigned_mechanic_id' => $mechanicModels[1]->id,
                'estimated_hours' => 2.0,
                'actual_hours' => 2.0,
                'status' => 'completed',
                'action_taken' => 'Filter solar primer dan sekunder diganti serta diprimer.',
            ]
        );

        // QC Checklist for WO 2
        QcChecklist::firstOrCreate(
            ['work_order_id' => $wo2->id],
            [
                'inspected_by_user_id' => $foreman->id,
                'checklist_items' => [
                    ['task' => 'Pemeriksaan Level Cairan Oli & Coolant', 'result' => 'pass', 'remarks' => 'Level oli tepat pada batas H dipstick.'],
                    ['task' => 'Pemeriksaan Kebocoran Fluida', 'result' => 'pass', 'remarks' => 'Tidak ada rembesan pada drain plug.'],
                    ['task' => 'Kekencangan Baut Filter', 'result' => 'pass', 'remarks' => 'Torsi sesuai spek manual shop.'],
                    ['task' => 'Kondisi Track Shoe & Tension', 'result' => 'pass', 'remarks' => 'Sag 25 mm (normal).'],
                    ['task' => 'Uji Operasi Mesin & Gas Buang', 'result' => 'pass', 'remarks' => 'Asap normal, rpm stabil.'],
                ],
                'overall_result' => 'pass',
                'notes' => 'Unit telah lulus pemeriksaan PM-500 dengan sangat baik. Siap diserahkan.',
                'inspected_at' => now()->subMinutes(30),
            ]
        );
    }
}
