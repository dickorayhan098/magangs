<x-layouts.app :title="'Edit Unit ' . $unit->unit_code" :header="'Edit Unit: ' . $unit->unit_code" subtitle="Perbarui spesifikasi dan data kepemilikan unit alat berat">
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('units.update', $unit) }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-6">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-100">
                        1. Identitas Unit
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Kode Unit <span class="text-red-500">*</span></label>
                            <input type="text" name="unit_code" value="{{ old('unit_code', $unit->unit_code) }}" required
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            @error('unit_code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Alat <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $unit->name) }}" required
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori Alat <span class="text-red-500">*</span></label>
                            <select name="category" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                                @foreach(['Excavator', 'Bulldozer', 'Wheel Loader', 'Dump Truck', 'Motor Grader', 'Crane', 'Compactor', 'Forklift', 'Support Vehicle'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $unit->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Merk / Brand</label>
                            <input type="text" name="brand" value="{{ old('brand', $unit->brand) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Model / Tipe</label>
                            <input type="text" name="model" value="{{ old('model', $unit->model) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tahun Pembuatan</label>
                            <input type="number" name="year_manufactured" value="{{ old('year_manufactured', $unit->year_manufactured) }}" min="1900" max="{{ date('Y') }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-100">
                        2. Serial & Kepemilikan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Rangka / Serial Number <span class="text-red-500">*</span></label>
                            <input type="text" name="serial_number" value="{{ old('serial_number', $unit->serial_number) }}" required
                                   class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            @error('serial_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Engine Serial Number</label>
                            <input type="text" name="engine_serial" value="{{ old('engine_serial', $unit->engine_serial) }}"
                                   class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Status Kepemilikan <span class="text-red-500">*</span></label>
                            <select name="ownership" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                                <option value="owned" {{ old('ownership', $unit->ownership) === 'owned' ? 'selected' : '' }}>Milik Sendiri (Owned)</option>
                                <option value="rental" {{ old('ownership', $unit->ownership) === 'rental' ? 'selected' : '' }}>Sewa (Rental)</option>
                                <option value="leased" {{ old('ownership', $unit->ownership) === 'leased' ? 'selected' : '' }}>Leasing</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Lokasi Kerja / Site</label>
                            <input type="text" name="location" value="{{ old('location', $unit->location) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Tambahan</label>
                            <textarea name="notes" rows="3" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">{{ old('notes', $unit->notes) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <div>
                    <button type="button" onclick="if(confirm('Yakin ingin menghapus unit ini?')) document.getElementById('delete-form').submit();"
                            class="text-xs text-red-600 hover:text-red-800 font-semibold">
                        Hapus Unit
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('units.show', $unit) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-white transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                        Perbarui Data Unit
                    </button>
                </div>
            </div>
        </form>

        <form id="delete-form" action="{{ route('units.destroy', $unit) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</x-layouts.app>
