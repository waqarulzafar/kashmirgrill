<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DineInSlot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DineInSlotController extends Controller
{
    public function index(): View
    {
        $slots = DineInSlot::query()
            ->orderBy('sort_order')
            ->orderBy('start_time')
            ->paginate(12);

        return view('admin.dine-in-slots.index', [
            'slots' => $slots,
            'totalSlots' => (int) DineInSlot::query()->count(),
            'activeSlots' => (int) DineInSlot::query()->where('is_active', true)->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.dine-in-slots.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'max_guests' => ['required', 'integer', 'min:1', 'max:200'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DineInSlot::query()->create([
            'name' => $validated['name'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'max_guests' => (int) $validated['max_guests'],
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.dine-in-slots.index')
            ->with('success', 'Dine-in slot created successfully.');
    }

    public function edit(DineInSlot $dineInSlot): View
    {
        return view('admin.dine-in-slots.edit', [
            'slot' => $dineInSlot,
        ]);
    }

    public function update(Request $request, DineInSlot $dineInSlot): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'max_guests' => ['required', 'integer', 'min:1', 'max:200'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $dineInSlot->update([
            'name' => $validated['name'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'max_guests' => (int) $validated['max_guests'],
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.dine-in-slots.index')
            ->with('success', 'Dine-in slot updated successfully.');
    }

    public function destroy(DineInSlot $dineInSlot): RedirectResponse
    {
        $dineInSlot->delete();

        return redirect()
            ->route('admin.dine-in-slots.index')
            ->with('success', 'Dine-in slot deleted successfully.');
    }
}
