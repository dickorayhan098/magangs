<x-layouts.app :title="'Profil ' . $mechanic->name" :header="'Mekanik: ' . $mechanic->name" subtitle="Informasi profil, sertifikasi teknis, dan riwayat penugasan unit">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('mechanics.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Mekanik
            </a>
            <a href="{{ route('mechanics.edit', $mechanic) }}" class="px-3.5 py-2 border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50 transition">
                Edit Profil
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-xl">
                        {{ strtoupper(substr($mechanic->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">{{ $mechanic->name }}</h3>
                        <p class="text-xs font-mono text-gray-400">NIK: {{ $mechanic->employee_id }}</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 space-y-3 text-xs">
                    <div>
                        <span class="text-gray-400 block">Spesialisasi:</span>
                        <span class="font-semibold text-gray-800">{{ $mechanic->specialization ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Level Sertifikasi:</span>
                        <span class="font-bold text-gray-800 uppercase">{{ $mechanic->certification_level }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Status Ketersediaan:</span>
                        @if($mechanic->is_available)
                            <span class="text-emerald-600 font-bold">Siap Ditugaskan (Available)</span>
                        @else
                            <span class="text-amber-600 font-bold">Sedang Mengerjakan WO</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-gray-400 block">No. Telepon:</span>
                        <span class="text-gray-800 font-medium">{{ $mechanic->phone ?? '-' }}</span>
                    </div>
                    @if($mechanic->notes)
                        <div>
                            <span class="text-gray-400 block">Catatan Keahlian:</span>
                            <span class="text-gray-700">{{ $mechanic->notes }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="md:col-span-2 bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900">Riwayat Penugasan Work Order</h3>
                    <span class="text-xs text-gray-500">{{ $mechanic->assignedWorkOrders->count() }} WO Terakhir</span>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($mechanic->assignedWorkOrders as $wo)
                        <div class="p-4 hover:bg-gray-50 transition flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('work-orders.show', $wo) }}" class="font-mono font-bold text-sm text-primary-600 hover:underline">
                                        {{ $wo->wo_number }}
                                    </a>
                                    <span class="text-xs font-semibold text-gray-700">&bull; {{ $wo->unit->unit_code }} ({{ $wo->unit->name }})</span>
                                </div>
                                <div class="mt-1 text-xs text-gray-500">
                                    Tipe: {{ ucfirst($wo->maintenance_type->value) }} &bull; Waktu: {{ $wo->created_at->format('d/m/Y') }}
                                </div>
                            </div>
                            <div>
                                <x-status-badge :status="$wo->status" />
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-sm text-gray-500">
                            Belum ada riwayat pengerjaan Work Order untuk mekanik ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
