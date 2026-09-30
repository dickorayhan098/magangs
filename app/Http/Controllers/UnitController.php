<?php

namespace App\Http\Controllers;

use App\Enums\UnitStatus;
use App\Models\HmLog;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnitController extends Controller
{
    public function index(Request $request): View
    {
        $query = Unit::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('unit_code', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $units = $query->latest()->paginate(15)->withQueryString();

        return view('units.index', compact('units'));
    }

    public function create(): View
    {
        return view('units.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'unit_code' => 'required|string|max:50|unique:units',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'serial_number' => 'required|string|max:100|unique:units',
            'year_manufactured' => 'nullable|integer|min:1900|max:'.date('Y'),
            'engine_serial' => 'nullable|string|max:100',
            'category' => 'required|string|max:50',
            'current_hm' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'ownership' => 'required|string|in:owned,rental,leased',
            'notes' => 'nullable|string',
        ]);

        $validated['last_pm_hm'] = 0;
        $validated['status'] = UnitStatus::Available;

        $unit = Unit::create($validated);

        // Catat HM awal
        if ($validated['current_hm'] > 0) {
            HmLog::create([
                'unit_id' => $unit->id,
                'hm_value' => $validated['current_hm'],
                'previous_hm' => 0,
                'delta_hm' => $validated['current_hm'],
                'recorded_date' => now()->toDateString(),
                'source' => 'manual',
                'notes' => 'HM awal saat registrasi unit.',
            ]);
        }

        return redirect()->route('units.index')->with('success', "Unit {$unit->unit_code} berhasil ditambahkan.");
    }

    public function show(Unit $unit): View
    {
        $unit->load(['hmLogs' => fn ($q) => $q->latest()->limit(20), 'workOrders' => fn ($q) => $q->latest()->limit(10)]);

        return view('units.show', compact('unit'));
    }

    public function edit(Unit $unit): View
    {
        return view('units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit): RedirectResponse
    {
        $validated = $request->validate([
            'unit_code' => 'required|string|max:50|unique:units,unit_code,'.$unit->id,
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'serial_number' => 'required|string|max:100|unique:units,serial_number,'.$unit->id,
            'year_manufactured' => 'nullable|integer|min:1900|max:'.date('Y'),
            'engine_serial' => 'nullable|string|max:100',
            'category' => 'required|string|max:50',
            'location' => 'nullable|string|max:255',
            'ownership' => 'required|string|in:owned,rental,leased',
            'notes' => 'nullable|string',
        ]);

        $unit->update($validated);

        return redirect()->route('units.show', $unit)->with('success', 'Data unit berhasil diperbarui.');
    }

    public function destroy(Unit $unit): RedirectResponse
    {
        $unit->delete();

        return redirect()->route('units.index')->with('success', "Unit {$unit->unit_code} berhasil dihapus.");
    }

    /**
     * Catat Hours Meter baru.
     */
    public function recordHm(Request $request, Unit $unit): RedirectResponse
    {
        $validated = $request->validate([
            'hm_value' => 'required|numeric|min:'.$unit->current_hm,
            'recorded_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $previousHm = $unit->current_hm;

        HmLog::create([
            'unit_id' => $unit->id,
            'hm_value' => $validated['hm_value'],
            'previous_hm' => $previousHm,
            'delta_hm' => $validated['hm_value'] - $previousHm,
            'recorded_date' => $validated['recorded_date'],
            'source' => 'manual',
            'notes' => $validated['notes'] ?? null,
        ]);

        $unit->update(['current_hm' => $validated['hm_value']]);

        return redirect()->route('units.show', $unit)->with('success', 'Hours Meter berhasil dicatat.');
    }
}
