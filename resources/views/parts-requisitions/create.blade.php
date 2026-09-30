<x-layouts.app title="Ajukan Permintaan Spare Part" header="Permintaan Suku Cadang (Parts Requisition)" subtitle="Mekanik mengajukan kebutuhan suku cadang atau pelumas untuk Work Order">
    <div class="max-w-2xl mx-auto" x-data="{
        spareParts: {{ $spareParts->toJson() }},
        selectedPartId: '{{ old('spare_part_id') }}',
        selectedPart: null,
        qty: {{ old('quantity_requested', 1) }},
        init() {
            this.updatePart();
        },
        updatePart() {
            this.selectedPart = this.spareParts.find(p => p.id == this.selectedPartId) || null;
        }
    }">
        <form action="{{ route('parts-requisitions.store') }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            @csrf

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Work Order Aktif <span class="text-red-500">*</span></label>
                    <select name="work_order_id" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        <option value="">-- Pilih Work Order --</option>
                        @foreach($workOrders as $wo)
                            <option value="{{ $wo->id }}" {{ (string) old('work_order_id', $selectedWoId) === (string) $wo->id ? 'selected' : '' }}>
                                [{{ $wo->wo_number }}] Unit: {{ $wo->unit->unit_code }} &bull; Status: {{ $wo->status->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('work_order_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Suku Cadang / Pelumas <span class="text-red-500">*</span></label>
                    <select name="spare_part_id" x-model="selectedPartId" @change="updatePart()" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        <option value="">-- Pilih Suku Cadang --</option>
                        @foreach($spareParts as $part)
                            <option value="{{ $part->id }}">
                                {{ $part->part_number }} - {{ $part->name }} (Stok: {{ $part->stock_quantity }} {{ $part->uom }})
                            </option>
                        @endforeach
                    </select>
                    @error('spare_part_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Part Information Snapshot --}}
                <template x-if="selectedPart">
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 grid grid-cols-3 gap-3 text-xs">
                        <div>
                            <span class="text-gray-400 block">Stok Gudang:</span>
                            <span class="font-bold text-gray-900" x-text="selectedPart.stock_quantity + ' ' + selectedPart.uom"></span>
                        </div>
                        <div>
                            <span class="text-gray-400 block">Lokasi Rak:</span>
                            <span class="font-mono font-bold text-gray-900" x-text="selectedPart.warehouse_location || '-'"></span>
                        </div>
                        <div>
                            <span class="text-gray-400 block">Harga Satuan:</span>
                            <span class="font-mono font-bold text-gray-900" x-text="'Rp ' + parseInt(selectedPart.unit_price).toLocaleString('id-ID')"></span>
                        </div>
                    </div>
                </template>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Jumlah yang Diminta <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="quantity_requested" x-model="qty" min="1" required
                               class="w-full px-3 py-2 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-xs text-gray-400 font-semibold pointer-events-none"
                              x-text="selectedPart ? selectedPart.uom : 'UNIT'"></span>
                    </div>
                    @error('quantity_requested') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Kebutuhan / Posisi Pasang</label>
                    <textarea name="notes" rows="2" placeholder="Contoh: Penggantian filter pada sisi kanan mesin, oil cooler leak..."
                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('parts-requisitions.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-white transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    Ajukan Permintaan Spare Part
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
