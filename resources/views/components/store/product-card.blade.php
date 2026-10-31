@props(['product', 'returnTo' => 'home'])

@php
    $variants = collect($product['variants']);
    $canPurchase = $variants->contains(fn ($variant) => $variant['available'] ?? true);
    $selectedVariant = $variants->first(fn ($variant) => $variant['available'] ?? true) ?? $variants->first();
@endphp

<article class="product-card">
    <a class="product-card-media-link" href="{{ route('storefront.products.show', $product['slug']) }}" aria-label="{{ __('store.view_details_for', ['product' => $product['name']]) }}">
        <div class="product-art {{ $product['image'] }}">
            <span class="product-card-category-chip">{{ $product['category'] }}</span>
            <span class="product-card-stock-chip {{ $canPurchase ? 'available' : 'unavailable' }}"><i></i>{{ $canPurchase ? __('store.in_stock') : __('store.out_of_stock') }}</span>
            @if($product['image_url'])<img class="product-photo" src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}" loading="lazy">@else<i class="product-jar"></i><i class="product-plant">✿</i>@endif
            <span class="product-card-image-action"><i data-lucide="scan-eye"></i>{{ __('store.view_details') }}</span>
        </div>
    </a>
    <div class="product-content">
        <h3><a href="{{ route('storefront.products.show', $product['slug']) }}">{{ $product['name'] }}</a></h3>
        @if($product['is_pack'])<span class="product-pack-summary">{{ __('store.pack_products_count', ['count' => count($product['pack_items'])]) }}</span>@endif
        @if($product['description'])<p class="product-card-description">{{ $product['description'] }}</p>@endif
        <form class="product-purchase" action="{{ route('cart.items.store') }}" method="POST">
            @csrf
            <input type="hidden" name="return_to" value="{{ $returnTo }}">
            @if($variants->count() > 1)
                <label class="variant-select-label" for="variant-{{ $product['id'] }}">{{ __('orders.variant') }}</label>
                <select id="variant-{{ $product['id'] }}" name="variant_id" required>
                    @foreach($variants as $variant)
                        <option value="{{ $variant['id'] }}" @selected($variant['id'] === $selectedVariant['id']) @disabled(! ($variant['available'] ?? true))>{{ $variant['name'] }} · {{ number_format($variant['price'], 2) }} {{ __('orders.madd') }}{{ ($variant['available'] ?? true) ? '' : ' · '.__('store.out_of_stock') }}</option>
                    @endforeach
                </select>
            @else
                <span class="product-card-single-variant">{{ $selectedVariant['name'] ?? __('orders.product_unavailable') }}</span>
                @if($selectedVariant)<input type="hidden" name="variant_id" value="{{ $selectedVariant['id'] }}">@endif
            @endif
            <input type="hidden" name="quantity" value="1">
            <div class="product-card-purchase-bottom"><div class="product-card-price"><small>{{ __('store.price_from') }}</small><strong>{{ number_format($product['starting_price'], 2) }}</strong><span>{{ __('orders.madd') }}</span></div><button type="submit" aria-label="{{ __('store.add_to_cart') }}" @disabled(! $canPurchase)><i data-lucide="shopping-bag"></i><span>{{ __('store.add_to_cart') }}</span></button></div>
        </form>
    </div>
</article>
