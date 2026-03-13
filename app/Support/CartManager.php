<?php

namespace App\Support;

use App\Models\MenuItem;
use Illuminate\Session\Store;

class CartManager
{
    private const SESSION_KEY = 'cart.items';

    public function __construct(
        private readonly Store $session,
    ) {}

    /**
     * @return array<int, array<string, mixed>>
     */
    public function items(): array
    {
        /** @var array<string, array<string, mixed>> $items */
        $items = $this->session->get(self::SESSION_KEY, []);

        return array_values($items);
    }

    public function count(): int
    {
        return (int) collect($this->items())->sum('quantity');
    }

    public function subtotal(): float
    {
        return (float) collect($this->items())
            ->sum(fn (array $item) => ((float) $item['price']) * ((int) $item['quantity']));
    }

    /**
     * @return array<string, mixed>
     */
    public function summary(): array
    {
        $items = collect($this->items())
            ->map(function (array $item): array {
                $quantity = (int) ($item['quantity'] ?? 1);
                $price = (float) ($item['price'] ?? 0);
                $lineTotal = $quantity * $price;

                return $item + [
                    'quantity' => $quantity,
                    'price' => number_format($price, 2, '.', ''),
                    'line_total' => number_format($lineTotal, 2, '.', ''),
                ];
            })
            ->values()
            ->all();

        $subtotal = $this->subtotal();

        return [
            'items' => $items,
            'count' => $this->count(),
            'subtotal' => number_format($subtotal, 2, '.', ''),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function add(MenuItem $menuItem, int $quantity = 1): array
    {
        $items = $this->session->get(self::SESSION_KEY, []);
        $key = (string) $menuItem->getKey();
        $existing = $items[$key] ?? null;

        $nextQuantity = $existing
            ? ((int) $existing['quantity']) + $quantity
            : $quantity;

        $items[$key] = [
            'menu_item_id' => (int) $menuItem->getKey(),
            'name' => (string) $menuItem->name,
            'slug' => (string) $menuItem->slug,
            'price' => (float) $menuItem->price,
            'quantity' => max(1, min(99, $nextQuantity)),
            'image_url' => $menuItem->imageUrl(),
            'category_name' => (string) ($menuItem->category?->name ?? ''),
        ];

        $this->session->put(self::SESSION_KEY, $items);

        return $this->summary();
    }

    /**
     * @return array<string, mixed>
     */
    public function setQuantity(int $menuItemId, int $quantity): array
    {
        $items = $this->session->get(self::SESSION_KEY, []);
        $key = (string) $menuItemId;

        if (! array_key_exists($key, $items)) {
            return $this->summary();
        }

        if ($quantity <= 0) {
            unset($items[$key]);
        } else {
            $items[$key]['quantity'] = max(1, min(99, $quantity));
        }

        $this->session->put(self::SESSION_KEY, $items);

        return $this->summary();
    }

    /**
     * @return array<string, mixed>
     */
    public function remove(int $menuItemId): array
    {
        return $this->setQuantity($menuItemId, 0);
    }

    public function clear(): void
    {
        $this->session->forget(self::SESSION_KEY);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }
}
