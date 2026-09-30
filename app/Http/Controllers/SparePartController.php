<?php

namespace App\Http\Controllers;

use App\Models\SparePart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SparePartController extends Controller
{
    public function index(Request $request): View
    {
        $query = SparePart::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('part_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->input('low_stock') === '1') {
            $query->whereColumn('stock_quantity', '<=', 'minimum_stock');
        }

        $spareParts = $query->latest()->paginate(15)->withQueryString();

        return view('spare-parts.index', compact('spareParts'));
    }

    public function create(): View
    {
        return view('spare-parts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'part_number' => 'required|string|max:100|unique:spare_parts',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'uom' => 'required|string|max:20',
            'stock_quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'reorder_point' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'warehouse_location' => 'nullable|string|max:100',
            'is_critical' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['is_critical'] = $request->boolean('is_critical');

        SparePart::create($validated);

        return redirect()->route('spare-parts.index')->with('success', 'Spare part berhasil ditambahkan.');
    }

    public function show(SparePart $sparePart): View
    {
        $sparePart->load(['requisitions' => fn ($q) => $q->with('workOrder')->latest()->limit(10)]);

        return view('spare-parts.show', compact('sparePart'));
    }

    public function edit(SparePart $sparePart): View
    {
        return view('spare-parts.edit', compact('sparePart'));
    }

    public function update(Request $request, SparePart $sparePart): RedirectResponse
    {
        $validated = $request->validate([
            'part_number' => 'required|string|max:100|unique:spare_parts,part_number,'.$sparePart->id,
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'uom' => 'required|string|max:20',
            'stock_quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'reorder_point' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'warehouse_location' => 'nullable|string|max:100',
            'is_critical' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['is_critical'] = $request->boolean('is_critical');

        $sparePart->update($validated);

        return redirect()->route('spare-parts.show', $sparePart)->with('success', 'Spare part berhasil diperbarui.');
    }

    public function destroy(SparePart $sparePart): RedirectResponse
    {
        $sparePart->delete();

        return redirect()->route('spare-parts.index')->with('success', 'Spare part berhasil dihapus.');
    }
}
