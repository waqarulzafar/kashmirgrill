<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MenuPageController extends Controller
{
    public function __invoke(Request $request): View
    {
        $categories = MenuCategory::query()
            ->orderBy('id')
            ->with([
                'menuItems' => fn ($query) => $query
                    ->where('is_available', true)
                    ->orderBy('id'),
            ])
            ->get()
            ->filter(fn (MenuCategory $category) => $category->menuItems->isNotEmpty())
            ->values();

        return view('pages.menu', [
            'categories' => $categories,
        ]);
    }
}
