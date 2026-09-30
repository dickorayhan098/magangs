<?php

namespace App\Http\Controllers;

use App\Models\Mechanic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MechanicController extends Controller
{
    public function index(Request $request): View
    {
        $query = Mechanic::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('specialization')) {
            $query->where('specialization', $request->input('specialization'));
        }

        $mechanics = $query->withCount(['assignedWorkOrders' => fn ($q) => $q->whereIn('status', ['in_progress', 'waiting_parts'])])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('mechanics.index', compact('mechanics'));
    }

    public function create(): View
    {
        return view('mechanics.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50|unique:mechanics',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:100',
            'certification_level' => 'required|string|in:junior,senior,lead,specialist',
            'notes' => 'nullable|string',
        ]);

        Mechanic::create($validated);

        return redirect()->route('mechanics.index')->with('success', 'Mekanik berhasil ditambahkan.');
    }

    public function show(Mechanic $mechanic): View
    {
        $mechanic->load(['assignedWorkOrders' => fn ($q) => $q->with('unit')->latest()->limit(10)]);

        return view('mechanics.show', compact('mechanic'));
    }

    public function edit(Mechanic $mechanic): View
    {
        return view('mechanics.edit', compact('mechanic'));
    }

    public function update(Request $request, Mechanic $mechanic): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50|unique:mechanics,employee_id,'.$mechanic->id,
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:100',
            'certification_level' => 'required|string|in:junior,senior,lead,specialist',
            'is_active' => 'boolean',
            'is_available' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_available'] = $request->boolean('is_available');

        $mechanic->update($validated);

        return redirect()->route('mechanics.show', $mechanic)->with('success', 'Data mekanik berhasil diperbarui.');
    }

    public function destroy(Mechanic $mechanic): RedirectResponse
    {
        $mechanic->delete();

        return redirect()->route('mechanics.index')->with('success', 'Mekanik berhasil dihapus.');
    }
}
