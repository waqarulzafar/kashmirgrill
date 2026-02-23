<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenuCategoryController extends Controller
{
    public function index(): View
    {
        $categories = MenuCategory::query()
            ->withCount('menuItems')
            ->latest()
            ->paginate(10);

        return view('admin.menu-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.menu-categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120', 'unique:menu_categories,slug'],
        ]);

        MenuCategory::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
        ]);

        return redirect()
            ->route('admin.menu-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(MenuCategory $menuCategory): View
    {
        return view('admin.menu-categories.edit', [
            'category' => $menuCategory,
        ]);
    }

    public function update(Request $request, MenuCategory $menuCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120', 'unique:menu_categories,slug,' . $menuCategory->id],
        ]);

        $menuCategory->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
        ]);

        return redirect()
            ->route('admin.menu-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(MenuCategory $menuCategory): RedirectResponse
    {
        $menuCategory->load('menuItems');

        foreach ($menuCategory->menuItems as $item) {
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
        }

        $menuCategory->delete();

        return redirect()
            ->route('admin.menu-categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
