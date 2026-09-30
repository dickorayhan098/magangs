<x-layouts.app title="Gudang Suku Cadang" header="Manajemen Suku Cadang & Pelumas" subtitle="Katalog parts alat berat, monitoring stok gudang, dan pengendalian batas minimum">
    <div class="space-y-6">
        {{-- Filter & Actions Bar --}}
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('spare-parts.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
                <div class="relative flex-1 min-w-[200px]">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Part Number, Nama Spare Part..."
                           class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>

                <select name="category" class="py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Kategori</option>
                    @foreach(['Filters', 'Oils & Fluids', 'Hydraulics', 'Engine Parts', 'Undercarriage', 'Electrical', 'Brakes & Transmission', 'Fasteners & Seals'] as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>

                <label class="flex items-center gap-1.5 py-2 px-3 bg-red-50 border border-red-200 rounded-lg cursor-pointer">
                    <input type="checkbox" name="low_stock" value="1" {{ request('low_stock') === '1' ? 'checked' : '' }}
                           class="rounded text-red-600 focus:ring-red-500" onchange="this.form.submit()">
                    <span class="text-xs font-bold text-red-700">Hanya Low Stock</span>
                </label>

                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'category', 'low_stock']))
                    <a href="{{ route('spare-parts.index') }}" class="text-xs text-gray-500 hover:text-gray-700 py-2">Reset</a>
                @endif
            </form>

            <a href="{{ route('spare-parts.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Spare Part
            </a>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                            <th class="py-3 px-4">Part Number</th>
                            <th class="py-3 px-4">Nama Suku Cadang</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Stok Fisik</th>
                            <th class="py-3 px-4">Min. Stok</th>
                            <th class="py-3 px-4">Lokasi Rak</th>
                            <th class="py-3 px-4">Harga Satuan</th>
                            <th class="py-3 px-4">Status Stok</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($spareParts as $part)
                            @php
                                $isLow = $part->isLowStock();
                            @endphp
                            <tr class="hover:bg-gray-50 transition {{ $isLow ? 'bg-red-50/20' : '' }}">
                                <td class="py-3.5 px-4 font-mono font-bold text-gray-900">
                                    <a href="{{ route('spare-parts.show', $part) }}" class="text-primary-600 hover:underline">
                                        {{ $part->part_number }}
                                    </a>
                                    @if($part->is_critical)
                                        <span class="ml-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">Kritis</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-gray-900">{{ $part->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $part->brand ?? 'OEM' }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-xs text-gray-600">
                                    {{ $part->category ?? 'General' }}
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-base {{ $isLow ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $part->stock_quantity }} <span class="text-xs font-normal text-gray-500">{{ $part->uom }}</span>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-xs text-gray-500">
                                    {{ $part->minimum_stock }} {{ $part->uom }}
                                </td>
                                <td class="py-3.5 px-4 font-mono text-xs text-gray-600">
                                    {{ $part->warehouse_location ?? 'A-01' }}
                                </td>
                                <td class="py-3.5 px-4 font-mono text-xs font-semibold text-gray-800">
                                    Rp {{ number_format($part->unit_price, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($part->stock_quantity == 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                            Out of Stock
                                        </span>
                                    @elseif($isLow)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                            Low Stock
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">
                                            Aman
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right space-x-2">
                                    <a href="{{ route('spare-parts.show', $part) }}" class="text-xs font-semibold text-primary-600 hover:text-primary-800">Detail</a>
                                    <a href="{{ route('spare-parts.edit', $part) }}" class="text-xs font-semibold text-gray-600 hover:text-gray-800">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center text-sm text-gray-500">
                                    Tidak ada suku cadang yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($spareParts->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $spareParts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
