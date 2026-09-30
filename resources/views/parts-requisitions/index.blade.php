<x-layouts.app title="Daftar Parts Requisition" header="Permintaan Suku Cadang (Parts Requisitions)" subtitle="Alur persetujuan dan pengeluaran suku cadang dari gudang untuk Work Order">
    <div class="space-y-6">
        {{-- Filter & Actions Bar --}}
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('parts-requisitions.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
                <div class="relative flex-1 min-w-[200px]">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No PR, WO, Part..."
                           class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>

                <select name="status" class="py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Status</option>
                    @foreach(\App\Enums\RequisitionStatus::cases() as $st)
                        <option value="{{ $st->value }}" {{ request('status') === $st->value ? 'selected' : '' }}>
                            {{ $st->label() }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('parts-requisitions.index') }}" class="text-xs text-gray-500 hover:text-gray-700 py-2">Reset</a>
                @endif
            </form>

            <a href="{{ route('parts-requisitions.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Permintaan Baru
            </a>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                            <th class="py-3 px-4">No. Requisition</th>
                            <th class="py-3 px-4">No. Work Order</th>
                            <th class="py-3 px-4">Part Number & Deskripsi</th>
                            <th class="py-3 px-4">Diminta</th>
                            <th class="py-3 px-4">Dikeluarkan</th>
                            <th class="py-3 px-4">Stok Gudang</th>
                            <th class="py-3 px-4">Harga & Total</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi Gudang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($requisitions as $pr)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-gray-900">
                                    {{ $pr->requisition_number }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <a href="{{ route('work-orders.show', $pr->workOrder) }}" class="font-mono font-semibold text-primary-600 hover:underline">
                                        {{ $pr->workOrder->wo_number }}
                                    </a>
                                    <div class="text-xs text-gray-500">{{ $pr->workOrder->unit->unit_code }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <a href="{{ route('spare-parts.show', $pr->sparePart) }}" class="font-bold text-gray-900 hover:underline">
                                        {{ $pr->sparePart->part_number }}
                                    </a>
                                    <div class="text-xs text-gray-500">{{ $pr->sparePart->name }}</div>
                                </td>
                                <td class="py-3.5 px-4 font-mono font-semibold">
                                    {{ $pr->quantity_requested }} {{ $pr->sparePart->uom }}
                                </td>
                                <td class="py-3.5 px-4 font-mono">
                                    {{ $pr->quantity_issued }} {{ $pr->sparePart->uom }}
                                </td>
                                <td class="py-3.5 px-4 font-mono text-xs">
                                    <span class="{{ $pr->sparePart->stock_quantity < $pr->quantity_requested ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                                        {{ $pr->sparePart->stock_quantity }} {{ $pr->sparePart->uom }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-xs">
                                    <div class="text-gray-500">@ Rp {{ number_format($pr->unit_price, 0, ',', '.') }}</div>
                                    <div class="font-bold text-gray-900">Rp {{ number_format(($pr->quantity_issued > 0 ? $pr->quantity_issued : $pr->quantity_requested) * $pr->unit_price, 0, ',', '.') }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <x-status-badge :status="$pr->status" />
                                </td>
                                <td class="py-3.5 px-4 text-right space-x-2">
                                    @if($pr->status === \App\Enums\RequisitionStatus::Pending)
                                        <form action="{{ route('parts-requisitions.approve', $pr) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded shadow-sm">
                                                Setujui
                                            </button>
                                        </form>
                                    @endif

                                    @if($pr->status === \App\Enums\RequisitionStatus::Approved)
                                        <form action="{{ route('parts-requisitions.issue', $pr) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Keluarkan suku cadang dan potong stok gudang sekarang?')"
                                                    class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded shadow-sm">
                                                Keluarkan Part
                                            </button>
                                        </form>
                                    @endif

                                    @if(in_array($pr->status, [\App\Enums\RequisitionStatus::Pending, \App\Enums\RequisitionStatus::Approved]))
                                        <form action="{{ route('parts-requisitions.cancel', $pr) }}" method="POST" class="inline" onsubmit="return confirm('Batalkan permintaan ini?')">
                                            @csrf
                                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold ml-1">Batal</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center text-sm text-gray-500">
                                    Belum ada transaksi Parts Requisition.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($requisitions->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $requisitions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
