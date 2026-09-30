<x-layouts.app title="Form QC Checklist Inspeksi" header="Lembar Inspeksi Quality Control (QC)" subtitle="Pemeriksaan mutu operasional dan kelaikan alat berat pasca perbaikan">
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('qc-checklists.store') }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            @csrf

            <div class="p-6 space-y-6">
                {{-- WO Selector --}}
                <div class="pb-4 border-b border-gray-100">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Work Order untuk Inspeksi QC <span class="text-red-500">*</span></label>
                    <select name="work_order_id" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        <option value="">-- Pilih Work Order (Status QC Testing) --</option>
                        @foreach($pendingWorkOrders as $wo)
                            <option value="{{ $wo->id }}" {{ (string) old('work_order_id', $selectedWoId) === (string) $wo->id ? 'selected' : '' }}>
                                [{{ $wo->wo_number }}] Unit: {{ $wo->unit->unit_code }} ({{ $wo->unit->name }}) &bull; Tipe: {{ ucfirst($wo->maintenance_type->value) }}
                            </option>
                        @endforeach
                    </select>
                    @error('work_order_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Checklist Items Table --}}
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-100 mb-3">
                        Daftar Pemeriksaan Standar Kelaikan Bengkel (QC Items)
                    </h3>

                    @php
                        $standardTasks = [
                            'Pemeriksaan Level Cairan (Oli Mesin, Coolant, Hidrolik, Transmisi)',
                            'Pemeriksaan Kebocoran Fluida & Selang (No Visible Leaks under pressure)',
                            'Kekencangan Baut & Fastener Kritis (Slewing bearing, track shoe, wheel nuts)',
                            'Kondisi Undercarriage / Roda (Track tension, roller, sprockets, tire wear)',
                            'Fungsi Sistem Hidrolik & Tekanan Kerja (Boom, Arm, Bucket cycle times)',
                            'Sistem Keselamatan & Kelistrikan (Horn, Rotary Lamp, Reverse Alarm, E-Stop)',
                            'Uji Fungsi Operasi (Travel forward/reverse, brake test, steering response)',
                            'Kerapihan, Pelumasan Grease Point, dan Kebersihan Kabin Operator',
                        ];
                    @endphp

                    <div class="space-y-3">
                        @foreach($standardTasks as $idx => $task)
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-3">
                                <div class="flex-1">
                                    <input type="hidden" name="items[{{ $idx }}][task]" value="{{ $task }}">
                                    <p class="text-xs font-bold text-gray-800">{{ $loop->iteration }}. {{ $task }}</p>
                                </div>
                                <div class="flex items-center gap-4 flex-shrink-0">
                                    <div class="flex items-center gap-3 text-xs">
                                        <label class="flex items-center gap-1 cursor-pointer">
                                            <input type="radio" name="items[{{ $idx }}][result]" value="pass" checked class="text-emerald-600 focus:ring-emerald-500">
                                            <span class="text-emerald-700 font-semibold">Pass</span>
                                        </label>
                                        <label class="flex items-center gap-1 cursor-pointer">
                                            <input type="radio" name="items[{{ $idx }}][result]" value="fail" class="text-red-600 focus:ring-red-500">
                                            <span class="text-red-700 font-semibold">Fail</span>
                                        </label>
                                        <label class="flex items-center gap-1 cursor-pointer">
                                            <input type="radio" name="items[{{ $idx }}][result]" value="na" class="text-gray-400 focus:ring-gray-400">
                                            <span class="text-gray-500">N/A</span>
                                        </label>
                                    </div>
                                    <input type="text" name="items[{{ $idx }}][remarks]" placeholder="Catatan item..."
                                           class="px-2 py-1 text-xs border border-gray-200 rounded focus:ring-1 focus:ring-primary-500 focus:outline-none w-36">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Overall Evaluation & Decision --}}
                <div class="pt-4 border-t border-gray-100">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-100 mb-3">
                        Keputusan Akhir Quality Control
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Hasil Evaluasi Inspeksi <span class="text-red-500">*</span></label>
                            <select name="overall_result" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none font-bold">
                                <option value="pass" class="text-emerald-600">LULUS (Pass) — Unit Siap Diserahkan ke Operasional</option>
                                <option value="fail" class="text-red-600">GAGAL (Fail) — Kembalikan ke Mekanik untuk Perbaikan Ulang</option>
                                <option value="conditional" class="text-amber-600">BERSYARAT (Conditional) — Catatan Khusus saat Handover</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Kesimpulan Foreman QC</label>
                            <textarea name="notes" rows="2" placeholder="Hasil uji beban, parameter tekanan yang terukur, rekomendasi servis berikutnya..."
                                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('qc-checklists.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-white transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    Simpan & Konfirmasi Hasil QC
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
