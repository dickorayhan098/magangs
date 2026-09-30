<x-layouts.app title="Daftar QC Checklist" header="Inspeksi Quality Control (QC)" subtitle="Standar pemeriksaan mutu pekerjaan bengkel alat berat sebelum penyerahan unit">
    <div class="space-y-6">
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('qc-checklists.index') }}" class="flex items-center gap-3">
                <select name="result" class="py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Hasil QC</option>
                    <option value="pass" {{ request('result') === 'pass' ? 'selected' : '' }}>Lulus (Pass)</option>
                    <option value="fail" {{ request('result') === 'fail' ? 'selected' : '' }}>Gagal / Re-work (Fail)</option>
                    <option value="conditional" {{ request('result') === 'conditional' ? 'selected' : '' }}>Bersyarat (Conditional)</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition">
                    Filter
                </button>
                @if(request()->filled('result'))
                    <a href="{{ route('qc-checklists.index') }}" class="text-xs text-gray-500 hover:text-gray-700 py-2">Reset</a>
                @endif
            </form>

            <a href="{{ route('qc-checklists.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Inspeksi QC Baru
            </a>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                            <th class="py-3 px-4">No. Work Order</th>
                            <th class="py-3 px-4">Unit Alat</th>
                            <th class="py-3 px-4">Inspektor QC</th>
                            <th class="py-3 px-4">Hasil Evaluasi</th>
                            <th class="py-3 px-4">Waktu Inspeksi</th>
                            <th class="py-3 px-4">Catatan Temuan</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($checklists as $qc)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-gray-900">
                                    <a href="{{ route('work-orders.show', $qc->workOrder) }}" class="text-primary-600 hover:underline">
                                        {{ $qc->workOrder->wo_number }}
                                    </a>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-gray-900">{{ $qc->workOrder->unit->unit_code }}</div>
                                    <div class="text-xs text-gray-500">{{ $qc->workOrder->unit->name }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-gray-700">
                                    {{ $qc->inspectedBy->name ?? 'Foreman QC' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <x-status-badge :status="$qc->overall_result" />
                                </td>
                                <td class="py-3.5 px-4 text-xs text-gray-500">
                                    {{ $qc->inspected_at ? $qc->inspected_at->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-xs text-gray-600 truncate max-w-xs">
                                    {{ $qc->notes ?? 'Pemeriksaan standar selesai tanpa catatan khusus.' }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('qc-checklists.show', $qc) }}" class="inline-flex items-center text-xs font-semibold text-primary-600 hover:text-primary-800">
                                        Lihat Lembar &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-sm text-gray-500">
                                    Belum ada catatan inspeksi QC.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($checklists->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $checklists->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
