<x-layouts.app title="Intake Work Order Baru" header="Service Advisor Intake — Work Order" subtitle="Penerimaan unit alat berat ke workshop untuk perbaikan atau servis berkala">
    <div class="max-w-4xl mx-auto" x-data="{
        units: {{ $units->toJson() }},
        selectedUnitId: '{{ old('unit_id', request('unit_id', '')) }}',
        selectedType: '{{ old('maintenance_type', 'periodic') }}',
        selectedUnit: null,
        init() {
            this.updateSelectedUnit();
        },
        updateSelectedUnit() {
            this.selectedUnit = this.units.find(u => u.id == this.selectedUnitId) || null;
            if (this.selectedUnit && !document.getElementById('intake_hm').value) {
                document.getElementById('intake_hm').value = this.selectedUnit.current_hm;
            }
        }
    }">
        <form action="{{ route('work-orders.store') }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            @csrf

            <div class="p-6 space-y-6">
                {{-- Step 1: Unit & Hours Meter --}}
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center justify-between">
                        <span>1. Pemilihan Unit & Verifikasi Hours Meter (HM)</span>
                        <span class="text-xs normal-case font-normal text-gray-500">Standar Intake Service Advisor</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Unit Alat Berat <span class="text-red-500">*</span></label>
                            <select name="unit_id" x-model="selectedUnitId" @change="updateSelectedUnit()" required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                                <option value="">-- Pilih Unit Armada --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ (string) old('unit_id', request('unit_id')) === (string) $unit->id ? 'selected' : '' }}>
                                        [{{ $unit->unit_code }}] {{ $unit->name }} &bull; HM: {{ number_format($unit->current_hm, 1) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Hours Meter Saat Masuk Workshop (Intake HM) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" step="0.1" name="intake_hm" id="intake_hm"
                                       :min="selectedUnit ? selectedUnit.current_hm : 0"
                                       value="{{ old('intake_hm') }}" required
                                       class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-xs text-gray-400 font-semibold pointer-events-none">JAM</span>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1">Harus sama atau lebih besar dari HM terakhir di sistem.</p>
                            @error('intake_hm') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Selected Unit Snapshot Box --}}
                    <template x-if="selectedUnit">
                        <div class="mt-4 p-4 bg-blue-50/60 rounded-xl border border-blue-200/60 grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                            <div>
                                <span class="text-gray-500">Model:</span>
                                <div class="font-bold text-gray-900" x-text="selectedUnit.brand + ' ' + (selectedUnit.model || '')"></div>
                            </div>
                            <div>
                                <span class="text-gray-500">Serial No:</span>
                                <div class="font-mono font-bold text-gray-900" x-text="selectedUnit.serial_number"></div>
                            </div>
                            <div>
                                <span class="text-gray-500">PM Terakhir:</span>
                                <div class="font-bold text-gray-900" x-text="parseFloat(selectedUnit.last_pm_hm).toFixed(1) + ' HM'"></div>
                            </div>
                            <div>
                                <span class="text-gray-500">Status Saat Ini:</span>
                                <div class="font-bold uppercase text-primary-700" x-text="selectedUnit.status"></div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Step 2: Jenis Pemeliharaan & Prioritas --}}
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-100">
                        2. Klasifikasi Pekerjaan Bengkel
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tipe Pemeliharaan <span class="text-red-500">*</span></label>
                            <select name="maintenance_type" x-model="selectedType" required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                                <option value="periodic">Periodic Maintenance (Servis Rutin Berkala)</option>
                                <option value="corrective">Corrective Maintenance (Perbaikan Terencana)</option>
                                <option value="breakdown">Breakdown Maintenance (Kerusakan Mendadak / Unplanned)</option>
                                <option value="overhaul">General Overhaul (Rekondisi Besar)</option>
                            </select>
                            @error('maintenance_type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tingkat Prioritas <span class="text-red-500">*</span></label>
                            <select name="priority" required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low (Dapat ditunda)</option>
                                <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium (Jadwal normal)</option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High (Prioritas hari ini)</option>
                                <option value="critical" {{ old('priority') === 'critical' ? 'selected' : '' }}>Critical (Pekerjaan terhenti / Stop Line)</option>
                            </select>
                            @error('priority') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Interval PM (Only if Periodic) --}}
                        <div x-show="selectedType === 'periodic'" class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Paket Interval PM</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach([250 => 'PM-250 (Ganti Oli Mesin & Filter)', 500 => 'PM-500 (+ Filter Solar & Hidrolik)', 1000 => 'PM-1000 (+ Tune-up & Transmisi)', 2000 => 'PM-2000 (Major PM & Flushing)'] as $interval => $label)
                                    <label class="flex flex-col p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-primary-500 transition">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" name="pm_interval" value="{{ $interval }}" {{ (int) old('pm_interval', 250) === $interval ? 'checked' : '' }}
                                                   class="text-primary-600 focus:ring-primary-500">
                                            <span class="text-sm font-bold text-gray-900">{{ $interval }} HM</span>
                                        </div>
                                        <span class="text-[11px] text-gray-500 mt-1">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 3: Keluhan & Deskripsi Kerusakan --}}
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-100">
                        3. Keluhan Operator / Temuan Awal
                    </h3>

                    <div class="space-y-4 mt-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Keluhan Operator / Gejala Kerusakan</label>
                            <textarea name="fault_description" rows="3" placeholder="Contoh: Tekanan hidrolik boom drop, suara kasar pada turbocharger, kebocoran oli di final drive..."
                                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">{{ old('fault_description') }}</textarea>
                            @error('fault_description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Tambahan Service Advisor</label>
                            <textarea name="notes" rows="2" placeholder="Catatan inspeksi visual penerimaan, level solar, kondisi fisik bodi unit..."
                                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">{{ old('notes') }}</textarea>
                            @error('notes') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('work-orders.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-white transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    Terbitkan Work Order (Intake)
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
