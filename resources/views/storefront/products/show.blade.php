<x-layouts.store :locale="$locale">
    <x-store.header />
    <main class="store-container product-detail-page">
        <nav class="product-breadcrumbs" aria-label="{{ __('store.breadcrumbs') }}">
            <a href="{{ route('storefront.home') }}">{{ __('store.home') }}</a><span>/</span>
            <a href="{{ route('storefront.shop', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a><span>/</span>
            <span>{{ $product->name }}</span>
        </nav>

        <section class="product-detail-grid">
            <div class="product-gallery">
                <div class="product-gallery-main {{ $product->slug }}" data-gallery-main>
                    @if ($product->product_images->isNotEmpty())
                        <img src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($product->product_images->first()->image) }}" alt="{{ $product->product_images->first()->alt ?: $product->name }}" data-gallery-active-image>
                    @else
                        <div class="product-gallery-fallback"><span>🍯</span><small>{{ __('store.product_photo_coming') }}</small></div>
                    @endif
                </div>
                @if ($product->product_images->count() > 1)
                    <div class="product-gallery-thumbnails" aria-label="{{ __('store.product_photos') }}">
                        @foreach ($product->product_images as $image)
                            <button type="button" class="product-gallery-thumbnail {{ $loop->first ? 'active' : '' }}" data-gallery-thumbnail data-image-url="{{ Illuminate\Support\Facades\Storage::disk('public')->url($image->image) }}" data-image-alt="{{ $image->alt ?: $product->name }}" aria-label="{{ __('store.photo_number', ['number' => $loop->iteration]) }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                                <img src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($image->image) }}" alt="" loading="lazy">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="product-detail-info">
                <span class="product-detail-category">{{ $product->category->name }}</span>
                <h1>{{ $product->name }}</h1>
                <div class="product-detail-price"><strong data-variant-price>{{ number_format((float) $product->product_variants->first()->price, 2) }}</strong><span>{{ __('orders.madd') }}</span></div>

                <form class="product-detail-purchase" action="{{ route('cart.items.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="return_to" value="product">
                    <label for="detailVariant">{{ __('orders.variant') }}</label>
                    <select id="detailVariant" name="variant_id" data-variant-select required>
                        @foreach ($product->product_variants as $variant)
                            <option value="{{ $variant->id }}" data-price="{{ number_format((float) $variant->price, 2, '.', '') }}" data-stock="{{ $variant->stock_quantity ?? 20 }}" @selected($loop->first)>{{ $variant->name }}</option>
                        @endforeach
                    </select>
                    <label for="detailQuantity">{{ __('orders.quantity') }}</label>
                    <div class="product-detail-quantity"><button type="button" data-quantity-step="-1" aria-label="{{ __('store.decrease_quantity') }}">−</button><input id="detailQuantity" type="number" name="quantity" min="1" max="{{ min(20, $product->product_variants->first()->stock_quantity ?? 20) }}" value="1" required data-quantity-input><button type="button" data-quantity-step="1" aria-label="{{ __('store.increase_quantity') }}">+</button></div>
                    <button class="gold-button product-detail-add" type="submit"><i data-lucide="shopping-cart"></i>{{ __('store.add_to_cart') }}</button>
                </form>

                <div class="product-detail-benefits"><span><i data-lucide="leaf"></i>{{ __('store.natural_product') }}</span><span><i data-lucide="truck"></i>{{ __('store.delivery_morocco') }}</span></div>
            </div>
        </section>

        <section class="product-detail-description-panel">
            <h2>{{ __('store.product_information') }}</h2>
            <p>{{ $product->description ?: __('store.product_description_pending') }}</p>
        </section>

        @if($product->is_pack && $product->packItems->isNotEmpty())
            <section class="product-detail-pack-panel">
                <h2>{{ __('store.pack_contents') }}</h2>
                <ul>@foreach($product->packItems as $packItem)<li><i data-lucide="package-check"></i><span>{{ $packItem->name }}</span></li>@endforeach</ul>
            </section>
        @endif

        <section class="product-detail-more"><x-store.section-heading :eyebrow="__('store.best_eyebrow')" :title="__('store.view_products')" /><a class="gold-button" href="{{ route('storefront.shop') }}">{{ __('store.continue_shopping') }} <i data-lucide="arrow-right"></i></a></section>
    </main>
    <x-store.footer />
</x-layouts.store>
