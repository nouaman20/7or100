<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Support\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function index(CartService $cart): View
    {
        $items = $cart->contents();

        return view('storefront.cart', [
            'items' => $items,
            'subtotal' => $items->sum('line_total'),
        ]);
    }

    public function store(Request $request, CartService $cart): RedirectResponse
    {
        $validated = $request->validate([
            'variant_id' => ['required', 'uuid', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'return_to' => ['nullable', 'in:home,shop,product'],
        ]);

        $variant = ProductVariant::query()
            ->with('product')
            ->whereKey($validated['variant_id'])
            ->where('is_active', true)
            ->first();

        if (! $variant?->product?->is_active) {
            throw ValidationException::withMessages([
                'variant_id' => __('orders.product_unavailable'),
            ]);
        }

        $currentQuantity = session()->get('cart.'.$variant->id, 0);
        $requestedQuantity = $currentQuantity + $validated['quantity'];

        if ($variant->stock_quantity !== null && $requestedQuantity > $variant->stock_quantity) {
            throw ValidationException::withMessages([
                'variant_id' => __('orders.stock_limit', ['quantity' => $variant->stock_quantity]),
            ]);
        }

        $cart->add($variant->id, $validated['quantity']);

        $redirect = match ($validated['return_to'] ?? 'home') {
            'product' => to_route('storefront.products.show', $variant->product->slug),
            'shop' => to_route('storefront.shop'),
            default => to_route('storefront.home'),
        };

        return $redirect
            ->with('cart_status', __('orders.added_to_cart'))
            ->with('cart_open', true);
    }

    public function update(Request $request, CartService $cart, string $variantId): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $variant = ProductVariant::query()->findOrFail($variantId);

        if ($variant->stock_quantity !== null && $validated['quantity'] > $variant->stock_quantity) {
            throw ValidationException::withMessages([
                'quantity' => __('orders.stock_limit', ['quantity' => $variant->stock_quantity]),
            ]);
        }

        $cart->setQuantity($variantId, $validated['quantity']);

        return to_route('cart.index');
    }

    public function destroy(CartService $cart, string $variantId): RedirectResponse
    {
        $cart->remove($variantId);

        return to_route('cart.index');
    }
}
