<x-layouts.app :title="'Edit WO ' . $workOrder->wo_number" :header="'Edit Work Order: ' . $workOrder->wo_number" :subtitle="'Unit: ' . $workOrder->unit->unit_code">
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('work-orders.update', $workOrder) }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-100">
                        1. Informasi & Prioritas Pekerjaan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tingkat Prioritas <span class="text-red-500">*</span></label>
                            <select name="priority" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                                <option value="low" {{ old('priority', $workOrder->priority->value) === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $workOrder->priority->value) === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $workOrder->priority->value) === 'high' ? 'selected' : '' }}>High</option>
                                <option value="critical" {{ old('priority', $workOrder->priority->value) === 'critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Mekanik Lead Ditugaskan</label>
                            <select name="assigned_mechanic_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                                <option value="">-- Belum Ditugaskan --</option>
                                @foreach($mechanics as $m)
                                    <option value="{{ $m->id }}" {{ old('assigned_mechanic_id', $workOrder->assigned_mechanic_id) == $m->id ? 'selected' : '' }}>
                                        {{ $m->name }} ({{ $m->specialization ?? 'Umum' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Jadwal Mulai Kerja</label>
                            <input type="datetime-local" name="scheduled_start"
                                   value="{{ old('scheduled_start', $workOrder->scheduled_start ? $workOrder->scheduled_start->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Estimasi Jam Kerja (Jam)</label>
                            <input type="number" step="0.5" name="estimated_hours"
                                   value="{{ old('estimated_hours', $workOrder->estimated_hours) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-100">
                        2. Deskripsi & Catatan
                    </h3>

                    <div class="space-y-4 mt-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Keluhan Operator / Gejala Kerusakan</label>
                            <textarea name="fault_description" rows="3" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">{{ old('fault_description', $workOrder->fault_description) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Tambahan Workshop</label>
                            <textarea name="notes" rows="2" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">{{ old('notes', $workOrder->notes) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('work-orders.show', $workOrder) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-white transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    Perbarui Work Order
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
