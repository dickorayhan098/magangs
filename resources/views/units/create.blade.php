<x-layouts.app title="Registrasi Unit Baru" header="Registrasi Alat Berat" subtitle="Tambah master data unit armada ke dalam sistem pemeliharaan">
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('units.store') }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            @csrf
            
            <div class="p-6 space-y-6">
                {{-- Form Section 1: Identitas Unit --}}
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-100">
                        1. Identitas & Spesifikasi Unit
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Kode Unit (Nomor Lambung) <span class="text-red-500">*</span></label>
                            <input type="text" name="unit_code" value="{{ old('unit_code') }}" placeholder="Contoh: EX-01, DT-25, BD-04" required
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            @error('unit_code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Alat <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Hydraulic Excavator 20 Ton" required
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori Alat <span class="text-red-500">*</span></label>
                            <select name="category" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach(['Excavator', 'Bulldozer', 'Wheel Loader', 'Dump Truck', 'Motor Grader', 'Crane', 'Compactor', 'Forklift', 'Support Vehicle'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Merk / Brand</label>
                            <input type="text" name="brand" value="{{ old('brand') }}" placeholder="Contoh: Komatsu, Caterpillar, Hitachi, Volvo"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            @error('brand') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Model / Tipe</label>
                            <input type="text" name="model" value="{{ old('model') }}" placeholder="Contoh: PC200-8M0, CAT 320D, D85ESS-2"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            @error('model') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tahun Pembuatan</label>
                            <input type="number" name="year_manufactured" value="{{ old('year_manufactured', date('Y')) }}" min="1900" max="{{ date('Y') }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            @error('year_manufactured') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Form Section 2: Serial & Hours Meter --}}
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-100">
                        2. Serial Numbers & Hours Meter (HM)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Rangka / Serial Number <span class="text-red-500">*</span></label>
                            <input type="text" name="serial_number" value="{{ old('serial_number') }}" placeholder="Nomor seri sasis unik" required
                                   class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            @error('serial_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Engine Serial Number</label>
                            <input type="text" name="engine_serial" value="{{ old('engine_serial') }}" placeholder="Nomor seri mesin"
                                   class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            @error('engine_serial') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Hours Meter (HM) Awal <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" step="0.1" name="current_hm" value="{{ old('current_hm', 0) }}" min="0" required
                                       class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-xs text-gray-400 font-semibold pointer-events-none">JAM</span>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1">HM saat registrasi awal untuk acuan kalkulasi PM.</p>
                            @error('current_hm') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Status Kepemilikan <span class="text-red-500">*</span></label>
                            <select name="ownership" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                                <option value="owned" {{ old('ownership') === 'owned' ? 'selected' : '' }}>Milik Sendiri (Owned)</option>
                                <option value="rental" {{ old('ownership') === 'rental' ? 'selected' : '' }}>Sewa (Rental)</option>
                                <option value="leased" {{ old('ownership') === 'leased' ? 'selected' : '' }}>Leasing</option>
                            </select>
                            @error('ownership') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Form Section 3: Lokasi & Catatan --}}
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-100">
                        3. Lokasi & Catatan Operasional
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Lokasi Kerja / Site</label>
                            <input type="text" name="location" value="{{ old('location') }}" placeholder="Contoh: Pit 3 South, Workshop Central, Project A"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            @error('location') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Tambahan</label>
                            <textarea name="notes" rows="3" placeholder="Informasi modifikasi, riwayat bawaan, attachment khusus..."
                                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">{{ old('notes') }}</textarea>
                            @error('notes') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('units.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-white transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    Simpan Data Unit
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
