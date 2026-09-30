<x-layouts.app :title="'Edit ' . $mechanic->name" :header="'Edit Mekanik: ' . $mechanic->name" subtitle="Perbarui data sertifikasi dan status mekanik">
    <div class="max-w-2xl mx-auto">
        <form action="{{ route('mechanics.update', $mechanic) }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">ID Karyawan / NIK Mekanik <span class="text-red-500">*</span></label>
                    <input type="text" name="employee_id" value="{{ old('employee_id', $mechanic->employee_id) }}" required
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap Mekanik <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $mechanic->name) }}" required
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Bidang Spesialisasi</label>
                        <select name="specialization" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            @foreach(['Hydraulic Specialist', 'Engine Specialist', 'Electrical & Controls', 'Undercarriage Specialist', 'General Mechanic', 'Welder & Fabricator'] as $spec)
                                <option value="{{ $spec }}" {{ old('specialization', $mechanic->specialization) === $spec ? 'selected' : '' }}>{{ $spec }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Level Sertifikasi <span class="text-red-500">*</span></label>
                        <select name="certification_level" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            <option value="junior" {{ old('certification_level', $mechanic->certification_level) === 'junior' ? 'selected' : '' }}>Junior Mechanic</option>
                            <option value="senior" {{ old('certification_level', $mechanic->certification_level) === 'senior' ? 'selected' : '' }}>Senior Mechanic</option>
                            <option value="lead" {{ old('certification_level', $mechanic->certification_level) === 'lead' ? 'selected' : '' }}>Lead Mechanic / Group Leader</option>
                            <option value="specialist" {{ old('certification_level', $mechanic->certification_level) === 'specialist' ? 'selected' : '' }}>Master Specialist</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone', $mechanic->phone) }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>

                <div class="flex items-center gap-6 py-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $mechanic->is_active) ? 'checked' : '' }}
                               class="rounded text-primary-600 focus:ring-primary-500">
                        <span class="text-xs font-semibold text-gray-700">Karyawan Aktif</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_available" value="1" {{ old('is_available', $mechanic->is_available) ? 'checked' : '' }}
                               class="rounded text-primary-600 focus:ring-primary-500">
                        <span class="text-xs font-semibold text-gray-700">Tersedia untuk Ditugaskan (Available)</span>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Keahlian</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">{{ old('notes', $mechanic->notes) }}</textarea>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <div>
                    <button type="button" onclick="if(confirm('Hapus mekanik ini?')) document.getElementById('delete-form').submit();"
                            class="text-xs text-red-600 hover:text-red-800 font-semibold">
                        Hapus Mekanik
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('mechanics.show', $mechanic) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-white transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                        Perbarui Data
                    </button>
                </div>
            </div>
        </form>

        <form id="delete-form" action="{{ route('mechanics.destroy', $mechanic) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</x-layouts.app>
