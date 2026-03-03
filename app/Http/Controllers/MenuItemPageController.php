<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Contracts\View\View;

class MenuItemPageController extends Controller
{
    public function __invoke(MenuItem $menuItem): View
    {
        $menuItem->load('category');

        $relatedItems = MenuItem::query()
            ->where('menu_category_id', $menuItem->menu_category_id)
            ->where('is_available', true)
            ->whereKeyNot($menuItem->getKey())
            ->orderBy('id')
            ->limit(3)
            ->get();

        return view('pages.menu-item', [
            'menuItem' => $menuItem,
            'relatedItems' => $relatedItems,
        ]);
    }
}
