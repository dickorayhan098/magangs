<x-layouts.app :title="'Detail ' . $sparePart->part_number" :header="'Part: ' . $sparePart->part_number" :subtitle="$sparePart->name">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('spare-parts.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Katalog Parts
            </a>
            <a href="{{ route('spare-parts.edit', $sparePart) }}" class="px-3.5 py-2 border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50 transition">
                Edit Data Part
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Stok Gudang</span>
                <div class="mt-1 text-2xl font-bold font-mono {{ $sparePart->isLowStock() ? 'text-red-600' : 'text-gray-900' }}">
                    {{ $sparePart->stock_quantity }} <span class="text-sm font-normal text-gray-500">{{ $sparePart->uom }}</span>
                </div>
                <div class="mt-2 text-xs text-gray-500">Min. Stok: {{ $sparePart->minimum_stock }} {{ $sparePart->uom }}</div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Harga Satuan</span>
                <div class="mt-1 text-xl font-bold font-mono text-gray-900">
                    Rp {{ number_format($sparePart->unit_price, 0, ',', '.') }}
                </div>
                <div class="mt-2 text-xs text-gray-500">Nilai Aset: Rp {{ number_format($sparePart->unit_price * $sparePart->stock_quantity, 0, ',', '.') }}</div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Lokasi Rak</span>
                <div class="mt-1 text-lg font-bold font-mono text-primary-700">
                    {{ $sparePart->warehouse_location ?? 'Tidak ditentukan' }}
                </div>
                <div class="mt-2 text-xs text-gray-500">Kategori: {{ $sparePart->category ?? 'Umum' }}</div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kritikalitas</span>
                <div class="mt-2">
                    @if($sparePart->is_critical)
                        <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-red-100 text-red-800">
                            Critical Spare Part
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-700">
                            Standar
                        </span>
                    @endif
                </div>
                <div class="mt-2 text-xs text-gray-500">ROP: {{ $sparePart->reorder_point }} {{ $sparePart->uom }}</div>
            </div>
        </div>

        {{-- Requisitions History --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900">Riwayat Pengeluaran & Permintaan Work Order</h3>
                <span class="text-xs text-gray-500">{{ $sparePart->requisitions->count() }} Transaksi</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase font-semibold">
                            <th class="py-3 px-4">No. Requisition</th>
                            <th class="py-3 px-4">Work Order</th>
                            <th class="py-3 px-4">Jumlah Diminta</th>
                            <th class="py-3 px-4">Jumlah Dikeluarkan</th>
                            <th class="py-3 px-4">Total Biaya</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($sparePart->requisitions as $pr)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 font-mono font-bold text-gray-800">{{ $pr->requisition_number }}</td>
                                <td class="py-3 px-4 font-mono">
                                    <a href="{{ route('work-orders.show', $pr->workOrder) }}" class="text-primary-600 hover:underline">
                                        {{ $pr->workOrder->wo_number }}
                                    </a>
                                </td>
                                <td class="py-3 px-4 font-mono">{{ $pr->quantity_requested }} {{ $sparePart->uom }}</td>
                                <td class="py-3 px-4 font-mono font-bold">{{ $pr->quantity_issued }} {{ $sparePart->uom }}</td>
                                <td class="py-3 px-4 font-mono">Rp {{ number_format(($pr->quantity_issued > 0 ? $pr->quantity_issued : $pr->quantity_requested) * $pr->unit_price, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">
                                    <x-status-badge :status="$pr->status" />
                                </td>
                                <td class="py-3 px-4 text-gray-500">{{ $pr->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-sm text-gray-400">
                                    Belum ada catatan permintaan untuk part ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
