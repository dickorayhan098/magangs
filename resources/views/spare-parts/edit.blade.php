<x-layouts.app :title="'Edit ' . $sparePart->part_number" :header="'Edit Suku Cadang: ' . $sparePart->part_number" :subtitle="$sparePart->name">
    <div class="max-w-3xl mx-auto">
        <form action="{{ route('spare-parts.update', $sparePart) }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-100">
                        1. Identifikasi Spare Part
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Part Number <span class="text-red-500">*</span></label>
                            <input type="text" name="part_number" value="{{ old('part_number', $sparePart->part_number) }}" required
                                   class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Part / Deskripsi <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $sparePart->name) }}" required
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Merk / Brand</label>
                            <input type="text" name="brand" value="{{ old('brand', $sparePart->brand) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori Part</label>
                            <select name="category" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                                @foreach(['Filters', 'Oils & Fluids', 'Hydraulics', 'Engine Parts', 'Undercarriage', 'Electrical', 'Brakes & Transmission', 'Fasteners & Seals'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $sparePart->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-100">
                        2. Manajemen Stok & Biaya
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Satuan Ukur (UOM) <span class="text-red-500">*</span></label>
                            <input type="text" name="uom" value="{{ old('uom', $sparePart->uom) }}" required
                                   class="w-full px-3 py-2 text-sm uppercase font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Stok Fisik Saat Ini <span class="text-red-500">*</span></label>
                            <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $sparePart->stock_quantity) }}" min="0" required
                                   class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Batas Minimum Stok <span class="text-red-500">*</span></label>
                            <input type="number" name="minimum_stock" value="{{ old('minimum_stock', $sparePart->minimum_stock) }}" min="0" required
                                   class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Titik Reorder (ROP) <span class="text-red-500">*</span></label>
                            <input type="number" name="reorder_point" value="{{ old('reorder_point', $sparePart->reorder_point) }}" min="0" required
                                   class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="unit_price" value="{{ old('unit_price', $sparePart->unit_price) }}" min="0" step="1000" required
                                   class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Lokasi Rak / Bin</label>
                            <input type="text" name="warehouse_location" value="{{ old('warehouse_location', $sparePart->warehouse_location) }}"
                                   class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_critical" value="1" {{ old('is_critical', $sparePart->is_critical) ? 'checked' : '' }}
                                   class="rounded text-red-600 focus:ring-red-500">
                            <span class="text-xs font-semibold text-gray-700">Spare Part Kritis</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <div>
                    <button type="button" onclick="if(confirm('Hapus suku cadang ini?')) document.getElementById('delete-form').submit();"
                            class="text-xs text-red-600 hover:text-red-800 font-semibold">
                        Hapus Part
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('spare-parts.show', $sparePart) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-white transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                        Perbarui Data
                    </button>
                </div>
            </div>
        </form>

        <form id="delete-form" action="{{ route('spare-parts.destroy', $sparePart) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</x-layouts.app>
