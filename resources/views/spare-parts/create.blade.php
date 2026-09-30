<x-layouts.app title="Tambah Spare Part Baru" header="Tambah Suku Cadang Gudang" subtitle="Katalogisasi part number, batas stok minimum, dan harga perolehan">
    <div class="max-w-3xl mx-auto">
        <form action="{{ route('spare-parts.store') }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            @csrf

            <div class="p-6 space-y-6">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-100">
                        1. Identifikasi Spare Part
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Part Number <span class="text-red-500">*</span></label>
                            <input type="text" name="part_number" value="{{ old('part_number') }}" placeholder="Contoh: 6732-71-6120, 1R-0716" required
                                   class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            @error('part_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Part / Deskripsi <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Filter Oli Mesin (Engine Oil Filter)" required
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Merk / Brand</label>
                            <input type="text" name="brand" value="{{ old('brand') }}" placeholder="Contoh: Komatsu Genuine, Caterpillar, Fleetguard"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori Part</label>
                            <select name="category" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach(['Filters', 'Oils & Fluids', 'Hydraulics', 'Engine Parts', 'Undercarriage', 'Electrical', 'Brakes & Transmission', 'Fasteners & Seals'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
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
                            <input type="text" name="uom" value="{{ old('uom', 'PCS') }}" placeholder="PCS, LTR, SET, ROLL" required
                                   class="w-full px-3 py-2 text-sm uppercase font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Stok Fisik Awal <span class="text-red-500">*</span></label>
                            <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0" required
                                   class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Batas Minimum Stok <span class="text-red-500">*</span></label>
                            <input type="number" name="minimum_stock" value="{{ old('minimum_stock', 2) }}" min="0" required
                                   class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Titik Reorder (ROP) <span class="text-red-500">*</span></label>
                            <input type="number" name="reorder_point" value="{{ old('reorder_point', 5) }}" min="0" required
                                   class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="unit_price" value="{{ old('unit_price', 0) }}" min="0" step="1000" required
                                   class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Lokasi Rak / Bin</label>
                            <input type="text" name="warehouse_location" value="{{ old('warehouse_location', 'A-01-1') }}" placeholder="Contoh: RAK-01-A"
                                   class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_critical" value="1" {{ old('is_critical') ? 'checked' : '' }}
                                   class="rounded text-red-600 focus:ring-red-500">
                            <span class="text-xs font-semibold text-gray-700">Spare Part Kritis (Kegagalan part ini menyebabkan unit mati total / breakdown)</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('spare-parts.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-white transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    Simpan Suku Cadang
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
