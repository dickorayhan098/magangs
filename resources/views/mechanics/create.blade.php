<x-layouts.app title="Tambah Mekanik Baru" header="Tambah Mekanik / Teknisi" subtitle="Registrasi profil mekanik bengkel alat berat dan spesialisasi">
    <div class="max-w-2xl mx-auto">
        <form action="{{ route('mechanics.store') }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            @csrf

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">ID Karyawan / NIK Mekanik <span class="text-red-500">*</span></label>
                    <input type="text" name="employee_id" value="{{ old('employee_id') }}" placeholder="Contoh: MEK-010, TECH-204" required
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    @error('employee_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap Mekanik <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" required
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Bidang Spesialisasi</label>
                        <select name="specialization" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            <option value="">-- Pilih Spesialisasi --</option>
                            <option value="Hydraulic Specialist" {{ old('specialization') === 'Hydraulic Specialist' ? 'selected' : '' }}>Hydraulic Specialist</option>
                            <option value="Engine Specialist" {{ old('specialization') === 'Engine Specialist' ? 'selected' : '' }}>Engine Specialist</option>
                            <option value="Electrical & Controls" {{ old('specialization') === 'Electrical & Controls' ? 'selected' : '' }}>Electrical & Controls</option>
                            <option value="Undercarriage Specialist" {{ old('specialization') === 'Undercarriage Specialist' ? 'selected' : '' }}>Undercarriage Specialist</option>
                            <option value="General Mechanic" {{ old('specialization') === 'General Mechanic' ? 'selected' : '' }}>General Mechanic</option>
                            <option value="Welder & Fabricator" {{ old('specialization') === 'Welder & Fabricator' ? 'selected' : '' }}>Welder & Fabricator</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Level Sertifikasi <span class="text-red-500">*</span></label>
                        <select name="certification_level" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                            <option value="junior" {{ old('certification_level') === 'junior' ? 'selected' : '' }}>Junior Mechanic</option>
                            <option value="senior" {{ old('certification_level', 'senior') === 'senior' ? 'selected' : '' }}>Senior Mechanic</option>
                            <option value="lead" {{ old('certification_level') === 'lead' ? 'selected' : '' }}>Lead Mechanic / Group Leader</option>
                            <option value="specialist" {{ old('certification_level') === 'specialist' ? 'selected' : '' }}>Master Specialist</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Keahlian</label>
                    <textarea name="notes" rows="3" placeholder="Sertifikat pabrikan (Komatsu/CAT/Hitachi), pelatihan K3, riwayat pengalaman..."
                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('mechanics.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-white transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    Simpan Data Mekanik
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
