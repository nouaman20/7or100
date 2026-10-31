<?php

namespace App\Support;

use App\Models\ProductVariant;
use Illuminate\Support\Collection;

class CartService
{
    /**
     * @return Collection<int, array{variant: ProductVariant|null, quantity: int, line_total: float, available: bool}>
     */
    public function contents(): Collection
    {
        $cart = session()->get('cart', []);

        if ($cart === []) {
            return collect();
        }

        $variants = ProductVariant::query()
            ->with(['product.product_images' => fn ($query) => $query->orderByDesc('is_primary')->orderBy('sort_order')])
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        return collect($cart)->map(function (int $quantity, string $variantId) use ($variants): array {
            /** @var ProductVariant|null $variant */
            $variant = $variants->get($variantId);
            $available = $variant !== null
                && $variant->is_active
                && $variant->product?->is_active
                && ($variant->stock_quantity === null || $quantity <= $variant->stock_quantity);

            return [
                'variant_id' => $variantId,
                'variant' => $variant,
                'quantity' => $quantity,
                'line_total' => $available ? (float) $variant->price * $quantity : 0.0,
                'available' => $available,
            ];
        })->values();
    }

    public function add(string $variantId, int $quantity): void
    {
        $cart = session()->get('cart', []);
        $cart[$variantId] = ($cart[$variantId] ?? 0) + $quantity;

        session()->put('cart', $cart);
    }

    public function setQuantity(string $variantId, int $quantity): void
    {
        $cart = session()->get('cart', []);

        if ($quantity < 1) {
            unset($cart[$variantId]);
        } else {
            $cart[$variantId] = $quantity;
        }

        session()->put('cart', $cart);
    }

    public function remove(string $variantId): void
    {
        $cart = session()->get('cart', []);
        unset($cart[$variantId]);

        session()->put('cart', $cart);
    }

    public function clear(): void
    {
        session()->forget('cart');
    }
}
