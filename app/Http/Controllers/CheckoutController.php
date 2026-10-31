<?php

namespace App\Http\Controllers;

use App\Enums\Enums\OrderStatus;
use App\Mail\AdminOrderPlaced;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Support\AdminMailRecipient;
use App\Support\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class CheckoutController extends Controller
{
    public function create(CartService $cart): View|RedirectResponse
    {
        $items = $cart->contents();

        if ($items->isEmpty() || $items->contains(fn (array $item): bool => ! $item['available'])) {
            return to_route('cart.index')->withErrors([
                'cart' => __('orders.cart_unavailable'),
            ]);
        }

        return view('storefront.checkout', [
            'items' => $items,
            'subtotal' => $items->sum('line_total'),
        ]);
    }

    public function store(Request $request, CartService $cart, AdminMailRecipient $adminMailRecipient): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:255'],
            'customer_city' => ['required', 'string', 'max:255'],
            'customer_address' => ['required', 'string', 'max:255'],
            'customer_note' => ['nullable', 'string', 'max:2000'],
        ]);
        $cartItems = $cart->contents();

        if ($cartItems->isEmpty() || $cartItems->contains(fn (array $item): bool => ! $item['available'])) {
            return to_route('cart.index')->withErrors([
                'cart' => __('orders.cart_unavailable'),
            ]);
        }

        $order = DB::transaction(function () use ($cartItems, $validated): Order {
            $purchasedItems = [];
            $subtotal = 0.0;

            foreach ($cartItems as $item) {
                $variant = ProductVariant::query()
                    ->with('product')
                    ->whereKey($item['variant']->id)
                    ->lockForUpdate()
                    ->first();

                if (! $variant?->is_active || ! $variant->product?->is_active) {
                    throw ValidationException::withMessages([
                        'cart' => __('orders.product_unavailable'),
                    ]);
                }

                if ($variant->stock_quantity !== null && $item['quantity'] > $variant->stock_quantity) {
                    throw ValidationException::withMessages([
                        'cart' => __('orders.stock_limit', ['quantity' => $variant->stock_quantity]),
                    ]);
                }

                $unitPrice = (float) $variant->price;
                $subtotal += $unitPrice * $item['quantity'];
                $purchasedItems[] = [
                    'product_name' => $variant->product->name.' — '.$variant->name,
                    'unit_price' => $unitPrice,
                    'quantity' => $item['quantity'],
                    'product_id' => $variant->product_id,
                ];

            }

            $order = Order::query()->create([
                'order_number' => $this->newOrderNumber(),
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_city' => $validated['customer_city'],
                'customer_address' => $validated['customer_address'],
                'customer_note' => $validated['customer_note'] ?? '',
                'subtotal' => $subtotal,
                'status' => OrderStatus::Pending->value,
            ]);

            $order->items()->createMany($purchasedItems);

            return $order;
        });

        $cart->clear();

        $adminEmail = $adminMailRecipient->resolve();

        if (is_string($adminEmail) && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($adminEmail)->send(new AdminOrderPlaced($order->load('items')));
            } catch (Throwable $exception) {
                Log::error('New order was saved, but its admin email could not be sent.', [
                    'order_id' => $order->id,
                    'exception' => $exception->getMessage(),
                ]);
            }
        } else {
            Log::warning('New order was saved without an admin email notification because no valid admin email is configured or available.', [
                'order_id' => $order->id,
            ]);
        }

        return redirect()->to(URL::temporarySignedRoute(
            'checkout.success',
            now()->addDay(),
            ['orderNumber' => $order->order_number],
        ));
    }

    public function success(string $orderNumber): View
    {
        $order = Order::query()
            ->with('items')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('storefront.orders.success', [
            'locale' => app()->getLocale(),
            'order' => $order,
        ]);
    }

    private function newOrderNumber(): string
    {
        do {
            $orderNumber = '7OR-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Order::query()->where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}
