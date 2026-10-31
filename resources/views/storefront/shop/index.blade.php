<x-layouts.store :locale="$locale">
    <x-store.header />
    <main class="store-container shop-page">
        <header class="shop-heading">
            <div><span>{{ __('store.shop_eyebrow') }}</span><h1>{{ __('store.shop_title') }}</h1><p>{{ __('store.shop_copy') }}</p></div>
            <div class="shop-result-count"><strong>{{ number_format($resultCount) }}</strong> {{ __('store.products_found') }}</div>
        </header>

        <div class="shop-layout">
            <aside class="shop-filter-panel">
                <div class="shop-filter-title"><h2>{{ __('store.filters') }}</h2>@if(array_filter($filters, fn ($value) => $value !== '' && $value !== null && ! in_array($value, ['available', 'newest'], true)))<a href="{{ route('storefront.shop') }}">{{ __('store.clear_filters') }}</a>@endif</div>
                <form action="{{ route('storefront.shop') }}" method="GET" class="shop-filter-form">
                    <label>{{ __('store.search') }}<div class="shop-filter-search"><i data-lucide="search"></i><input type="search" name="q" value="{{ $filters['q'] }}" placeholder="{{ __('store.search') }}"></div></label>

                    <label>{{ __('store.filter_category') }}<select name="category"><option value="">{{ __('store.all_categories') }}</option>@foreach($categories as $category)<option value="{{ $category->slug }}" @selected($filters['category'] === $category->slug)>{{ $category->name }}</option>@endforeach</select></label>

                    <fieldset class="shop-filter-fieldset"><legend>{{ __('store.filter_price') }} <small>({{ __('orders.madd') }})</small></legend><div class="shop-filter-range"><label><span>{{ __('store.from') }}</span><input type="number" name="min_price" min="0" step="0.01" value="{{ $filters['min_price'] }}" placeholder="{{ number_format($priceRange['min'], 0) }}"></label><label><span>{{ __('store.to') }}</span><input type="number" name="max_price" min="0" step="0.01" value="{{ $filters['max_price'] }}" placeholder="{{ number_format($priceRange['max'], 0) }}"></label></div></fieldset>

                    <fieldset class="shop-filter-fieldset"><legend>{{ __('store.filter_weight') }} <small>({{ __('store.grams') }})</small></legend><div class="shop-filter-range"><label><span>{{ __('store.from') }}</span><input type="number" name="min_weight" min="1" step="1" value="{{ $filters['min_weight'] }}" placeholder="0"></label><label><span>{{ __('store.to') }}</span><input type="number" name="max_weight" min="1" step="1" value="{{ $filters['max_weight'] }}" placeholder="1000"></label></div></fieldset>

                    <label>{{ __('store.availability') }}<select name="availability"><option value="available" @selected($filters['availability'] === 'available')>{{ __('store.in_stock') }}</option><option value="all" @selected($filters['availability'] === 'all')>{{ __('store.include_out_of_stock') }}</option></select></label>
                    <label>{{ __('store.sort_by') }}<select name="sort"><option value="newest" @selected($filters['sort'] === 'newest')>{{ __('store.sort_newest') }}</option><option value="price_asc" @selected($filters['sort'] === 'price_asc')>{{ __('store.sort_price_low') }}</option><option value="price_desc" @selected($filters['sort'] === 'price_desc')>{{ __('store.sort_price_high') }}</option><option value="name_asc" @selected($filters['sort'] === 'name_asc')>{{ __('store.sort_name') }}</option></select></label>

                    <button class="shop-filter-submit" type="submit"><i data-lucide="list-filter"></i>{{ __('store.apply_filters') }}</button>
                </form>
            </aside>

            <section class="shop-results" aria-label="{{ __('store.shop_title') }}">
                @if($filters['category'] !== '' || $filters['q'] !== '')
                    <div class="shop-active-filters">@if($filters['q'] !== '')<span>{{ __('store.search') }}: {{ $filters['q'] }}</span>@endif @if($filters['category'] !== '')<span>{{ $categories->firstWhere('slug', $filters['category'])?->name ?? $filters['category'] }}</span>@endif</div>
                @endif
                <div class="product-grid shop-product-grid">
                    @forelse($products as $product)
                        <x-store.product-card :product="$product" return-to="shop" />
                    @empty
                        <div class="shop-empty"><i data-lucide="search-x"></i><h2>{{ __('store.no_results') }}</h2><p>{{ __('store.adjust_filters') }}</p><a href="{{ route('storefront.shop') }}">{{ __('store.clear_filters') }}</a></div>
                    @endforelse
                </div>
                @if($products->hasPages())<nav class="shop-pagination" aria-label="Pagination">{{ $products->links() }}</nav>@endif
            </section>
        </div>
    </main>
    <x-store.footer />
</x-layouts.store>
