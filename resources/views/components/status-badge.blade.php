@props(['status'])

@php
$map = [
    // WorkOrderStatus
    'draft'         => ['Draft', 'badge-gray'],
    'scheduled'     => ['Dijadwalkan', 'badge-info'],
    'in_progress'   => ['Sedang Dikerjakan', 'badge-warning'],
    'waiting_parts' => ['Menunggu Parts', 'badge-danger'],
    'qc_testing'    => ['QC Testing', 'badge-primary'],
    'completed'     => ['Selesai', 'badge-success'],
    'released'      => ['Diserahkan', 'badge-success'],
    'cancelled'     => ['Dibatalkan', 'badge-gray'],
    // UnitStatus
    'available'       => ['Tersedia', 'badge-success'],
    'in_service'      => ['Dalam Servis', 'badge-warning'],
    'breakdown'       => ['Breakdown', 'badge-danger'],
    'standby'         => ['Standby', 'badge-gray'],
    'decommissioned'  => ['Dinonaktifkan', 'badge-gray'],
    // MaintenanceType
    'periodic'    => ['PM Berkala', 'badge-info'],
    'corrective'  => ['Korektif', 'badge-warning'],
    'breakdown_m' => ['Breakdown', 'badge-danger'],
    'overhaul'    => ['Overhaul', 'badge-primary'],
    // Priority
    'low'      => ['Rendah', 'badge-gray'],
    'medium'   => ['Sedang', 'badge-info'],
    'high'     => ['Tinggi', 'badge-warning'],
    'critical' => ['Kritikal', 'badge-danger'],
    // RequisitionStatus
    'pending'    => ['Pending', 'badge-warning'],
    'approved'   => ['Disetujui', 'badge-info'],
    'issued'     => ['Dikeluarkan', 'badge-success'],
    'rejected'   => ['Ditolak', 'badge-danger'],
    'back_order' => ['Back Order', 'badge-gray'],
    // QC
    'passed'      => ['Lulus', 'badge-success'],
    'failed'      => ['Gagal', 'badge-danger'],
    'conditional' => ['Bersyarat', 'badge-warning'],
];

$statusValue = $status instanceof \BackedEnum ? $status->value : $status;
$info = $map[$statusValue] ?? [$statusValue, 'badge-gray'];
@endphp

<span class="{{ $info[1] }}">{{ $info[0] }}</span>
