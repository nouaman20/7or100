<x-layouts.store :locale="$locale">
    <x-store.header />
    <main class="store-container order-success-page">
        <section class="order-success-card">
            <div class="order-success-icon"><i data-lucide="check"></i></div>
            <span class="order-success-eyebrow">{{ __('orders.order_received') }}</span>
            <h1>{{ __('orders.success_title') }}</h1>
            <p>{{ __('orders.success_copy') }}</p>
            <div class="order-success-number"><span>{{ __('orders.order_number') }}</span><strong>{{ $order->order_number }}</strong></div>

            <div class="order-success-items">
                <h2>{{ __('orders.items') }}</h2>
                @foreach ($order->items as $item)
                    <div class="order-success-item"><span>{{ $item->product_name }} <b>× {{ $item->quantity }}</b></span><strong>{{ number_format((float) $item->total_price, 2) }} {{ __('orders.madd') }}</strong></div>
                @endforeach
                <div class="order-success-total"><span>{{ __('orders.total') }}</span><strong>{{ number_format((float) $order->subtotal, 2) }} {{ __('orders.madd') }}</strong></div>
            </div>
            <a class="gold-button" href="{{ route('storefront.shop') }}">{{ __('orders.continue_shopping') }} <i data-lucide="arrow-right"></i></a>
        </section>
    </main>
    <x-store.footer />
</x-layouts.store>
