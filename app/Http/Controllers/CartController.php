<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Support\CartManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function add(Request $request, CartManager $cart): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'menu_item_id' => ['required', 'integer', 'exists:menu_items,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $menuItem = MenuItem::query()
            ->with('category')
            ->where('is_available', true)
            ->findOrFail((int) $validated['menu_item_id']);

        $summary = $cart->add($menuItem, (int) ($validated['quantity'] ?? 1));

        return $this->respond($request, $summary, 'Item added to cart.');
    }

    public function update(Request $request, int $menuItem, CartManager $cart): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $quantity = (int) $validated['quantity'];
        $summary = $cart->setQuantity($menuItem, $quantity);
        $message = $quantity > 0 ? 'Cart quantity updated.' : 'Item removed from cart.';

        return $this->respond($request, $summary, $message);
    }

    public function remove(Request $request, int $menuItem, CartManager $cart): JsonResponse|RedirectResponse
    {
        $summary = $cart->remove($menuItem);

        return $this->respond($request, $summary, 'Item removed from cart.');
    }

    public function clear(Request $request, CartManager $cart): JsonResponse|RedirectResponse
    {
        $cart->clear();
        $summary = $cart->summary();

        return $this->respond($request, $summary, 'Cart cleared.');
    }

    public function drawer(Request $request, CartManager $cart): JsonResponse|View
    {
        $summary = $cart->summary();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'cart' => $summary,
                'drawer_html' => view('partials.cart.drawer-body', ['cart' => $summary])->render(),
            ]);
        }

        return view('partials.cart.drawer-body', ['cart' => $summary]);
    }

    /**
     * @param  array<string, mixed>  $summary
     */
    private function respond(Request $request, array $summary, string $message): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => $message,
                'cart' => $summary,
                'drawer_html' => view('partials.cart.drawer-body', ['cart' => $summary])->render(),
            ]);
        }

        return back()->with('success', $message);
    }
}
