<x-layouts.store :locale="app()->getLocale()">
    <x-store.header />
    <main class="store-container commerce-page">
        <div class="commerce-heading"><span>{{ __('store.best_eyebrow') }}</span><h1>{{ __('orders.cart') }}</h1></div>
        @if ($errors->any())<div class="commerce-alert" role="alert">{{ $errors->first() }}</div>@endif
        @if ($items->isEmpty())
            <div class="empty-cart"><span>🛒</span><h2>{{ __('orders.cart_empty') }}</h2><a class="gold-button" href="{{ route('storefront.home') }}">{{ __('orders.continue_shopping') }} <i data-lucide="arrow-right"></i></a></div>
        @else
            <div class="commerce-grid">
                <section class="cart-lines" aria-label="{{ __('orders.cart') }}">
                    @foreach ($items as $item)
                        @php
                            $variant = $item['variant'];
                        @endphp
                        <article class="cart-line {{ $item['available'] ? '' : 'unavailable' }}">
                            <div class="cart-product-art">@if($variant?->product?->product_images?->first())<img src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($variant->product->product_images->first()->image) }}" alt="{{ $variant->product->name }}">@else<span>🍯</span>@endif</div>
                            <div class="cart-line-info"><h2>{{ $variant?->product?->name ?? __('orders.product_unavailable') }}</h2><p>{{ $variant?->name ?? '' }}</p>@if(!$item['available'])<strong class="availability-error">{{ __('orders.product_unavailable') }}</strong>@endif</div>
                            @if($item['available'])
                                <strong class="cart-line-price">{{ number_format((float) $variant->price, 2) }} {{ __('orders.madd') }}</strong>
                                <form class="quantity-form" action="{{ route('cart.items.update', $item['variant_id']) }}" method="POST">@csrf @method('PATCH')<label class="sr-only" for="quantity-{{ $item['variant_id'] }}">{{ __('orders.quantity') }}</label><input id="quantity-{{ $item['variant_id'] }}" type="number" name="quantity" min="1" max="20" value="{{ $item['quantity'] }}" onchange="this.form.submit()"></form>
                                <strong class="cart-line-total">{{ number_format($item['line_total'], 2) }} {{ __('orders.madd') }}</strong>
                            @endif
                            <form action="{{ route('cart.items.destroy', $item['variant_id']) }}" method="POST">@csrf @method('DELETE')<button class="remove-item" type="submit">{{ __('orders.remove') }}</button></form>
                        </article>
                    @endforeach
                </section>
                <aside class="order-summary"><h2>{{ __('orders.total') }}</h2><div><span>{{ __('orders.subtotal') }}</span><strong>{{ number_format($subtotal, 2) }} {{ __('orders.madd') }}</strong></div><small>{{ __('orders.checkout') }}</small><a class="gold-button" href="{{ route('checkout.create') }}">{{ __('orders.checkout') }} <i data-lucide="arrow-right"></i></a><a class="continue-link" href="{{ route('storefront.home') }}">{{ __('orders.continue_shopping') }}</a></aside>
            </div>
        @endif
    </main>
    <x-store.footer />
</x-layouts.store>
