<div class="cart-drawer-root {{ session('cart_open') ? 'cart-drawer-autopen' : '' }}" data-cart-autopen="{{ session('cart_open') ? 'true' : 'false' }}">
    <button class="cart-drawer-overlay" type="button" data-cart-close aria-label="{{ __('orders.close_cart') }}" tabindex="-1"></button>
    <aside class="cart-drawer" id="storeCart" role="dialog" aria-modal="true" aria-labelledby="storeCartTitle" aria-hidden="true" inert>
        <header class="cart-drawer-header">
            <div><p>{{ __('orders.cart_label') }}</p><h2 id="storeCartTitle">{{ __('orders.cart') }} <span>({{ array_sum(session('cart', [])) }})</span></h2></div>
            <button class="cart-drawer-close" type="button" data-cart-close aria-label="{{ __('orders.close_cart') }}"><i data-lucide="x"></i></button>
        </header>

        @if ($cartItems->isEmpty())
            <div class="cart-drawer-empty"><span>🛒</span><h3>{{ __('orders.cart_empty') }}</h3><a href="{{ route('storefront.shop') }}" data-cart-close>{{ __('orders.continue_shopping') }}</a></div>
        @else
            <div class="cart-drawer-items">
                @foreach ($cartItems as $item)
                    @php
                        $variant = $item['variant'];
                        $product = $variant?->product;
                        $image = $product?->product_images?->first();
                    @endphp
                    <article class="cart-drawer-item {{ $item['available'] ? '' : 'unavailable' }}">
                        <div class="cart-drawer-product-image">
                            @if ($image)
                                <img src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($image->image) }}" alt="{{ $image->alt ?: $product?->name }}">
                            @else
                                <span>🍯</span>
                            @endif
                        </div>
                        <div class="cart-drawer-product-info">
                            <strong>{{ $product?->name ?? __('orders.product_unavailable') }}</strong>
                            <small>{{ $variant?->name }}</small>
                            @if (! $item['available'])<small class="cart-drawer-error">{{ __('orders.product_unavailable') }}</small>@endif
                            <div class="cart-drawer-item-bottom">
                                <span>{{ __('orders.quantity') }}: {{ $item['quantity'] }}</span>
                                @if ($item['available'])<strong>{{ number_format($item['line_total'], 2) }} {{ __('orders.madd') }}</strong>@endif
                            </div>
                        </div>
                        <form action="{{ route('cart.items.destroy', $item['variant_id']) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="cart-drawer-remove" aria-label="{{ __('orders.remove') }} {{ $product?->name }}">{{ __('orders.remove') }}</button>
                        </form>
                    </article>
                @endforeach
            </div>
            <footer class="cart-drawer-footer">
                <div class="cart-drawer-subtotal"><span>{{ __('orders.subtotal') }}</span><strong>{{ number_format($cartSubtotal, 2) }} {{ __('orders.madd') }}</strong></div>
                <a class="cart-drawer-checkout" href="{{ route('checkout.create') }}">{{ __('orders.checkout') }} <i data-lucide="arrow-right"></i></a>
                <a class="cart-drawer-view" href="{{ route('cart.index') }}">{{ __('orders.view_cart') }}</a>
            </footer>
        @endif
    </aside>
</div>
