<x-layouts.store :locale="app()->getLocale()">
    <x-store.header />
    <main class="store-container commerce-page">
        <div class="commerce-heading"><span>{{ __('orders.checkout') }}</span><h1>{{ __('orders.checkout_title') }}</h1></div>
        <div class="commerce-grid checkout-grid">
            <form class="checkout-form" action="{{ route('checkout.store') }}" method="POST">
                @csrf
                @if ($errors->any())<div class="commerce-alert" role="alert">{{ $errors->first() }}</div>@endif
                <label>{{ __('orders.customer_name') }}<input name="customer_name" value="{{ old('customer_name') }}" autocomplete="name" required maxlength="255">@error('customer_name')<small>{{ $message }}</small>@enderror</label>
                <label>{{ __('orders.customer_phone') }}<input name="customer_phone" type="tel" value="{{ old('customer_phone') }}" autocomplete="tel" required maxlength="255">@error('customer_phone')<small>{{ $message }}</small>@enderror</label>
                <label>{{ __('orders.customer_city') }}<input name="customer_city" value="{{ old('customer_city') }}" autocomplete="address-level2" required maxlength="255">@error('customer_city')<small>{{ $message }}</small>@enderror</label>
                <label>{{ __('orders.customer_address') }}<textarea name="customer_address" rows="3" autocomplete="street-address" required maxlength="255">{{ old('customer_address') }}</textarea>@error('customer_address')<small>{{ $message }}</small>@enderror</label>
                <label>{{ __('orders.customer_note') }}<textarea name="customer_note" rows="3" maxlength="2000">{{ old('customer_note') }}</textarea>@error('customer_note')<small>{{ $message }}</small>@enderror</label>
                <button class="gold-button" type="submit">{{ __('orders.place_order') }} <i data-lucide="arrow-right"></i></button>
            </form>
            <aside class="order-summary checkout-summary"><h2>{{ __('orders.cart') }}</h2>
                @foreach ($items as $item)
                    <div class="summary-line"><span>{{ $item['variant']->product->name }} · {{ $item['variant']->name }} <b>× {{ $item['quantity'] }}</b></span><strong>{{ number_format($item['line_total'], 2) }} {{ __('orders.madd') }}</strong></div>
                @endforeach
                <div class="summary-total"><span>{{ __('orders.total') }}</span><strong>{{ number_format($subtotal, 2) }} {{ __('orders.madd') }}</strong></div>
            </aside>
        </div>
    </main>
    <x-store.footer />
</x-layouts.store>
