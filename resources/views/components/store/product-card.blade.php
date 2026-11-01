@props(['product', 'returnTo' => 'home'])

@php
    $variants = collect($product['variants']);
    $canPurchase = $variants->contains(fn ($variant) => $variant['available'] ?? true);
    $selectedVariant = $variants->first(fn ($variant) => $variant['available'] ?? true) ?? $variants->first();
@endphp

<article data-product-card class="group flex h-full min-w-0 flex-col overflow-hidden rounded-[1.5rem] border border-amber-100/80 bg-white shadow-[0_8px_28px_rgba(62,45,12,0.07)] transition duration-300 hover:-translate-y-1 hover:border-amber-200 hover:shadow-[0_18px_38px_rgba(62,45,12,0.14)] focus-within:ring-4 focus-within:ring-amber-100">
    <a class="relative block overflow-hidden rounded-t-[1.45rem] bg-gradient-to-br from-amber-50 via-[#fffdf7] to-orange-100" href="{{ route('storefront.products.show', $product['slug']) }}" aria-label="{{ __('store.view_details_for', ['product' => $product['name']]) }}">
        <div class="relative isolate aspect-[0.92/1] overflow-hidden">
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_52%_36%,rgba(255,255,255,0.92),transparent_57%)]"></div>
            <span class="absolute start-3 top-3 z-20 max-w-[55%] truncate rounded-full border border-white/80 bg-white/90 px-3 py-1.5 text-[10px] font-bold text-stone-700 shadow-sm backdrop-blur">{{ $product['category'] }}</span>
            @if($product['image_url'])
                <span class="absolute inset-x-2 bottom-2 top-10 z-10 grid place-items-center overflow-hidden rounded-2xl">
                    <img class="block h-full w-full scale-105 object-contain object-center transition duration-500 group-hover:scale-110" src="{{ $product['image_url'] }}" alt="{{ $product['image_alt'] ?? $product['name'] }}" loading="lazy">
                </span>
            @else
                <span class="absolute inset-0 grid place-items-center text-7xl drop-shadow-sm transition duration-500 group-hover:scale-105" aria-hidden="true">🍯</span>
            @endif

            <span class="absolute bottom-3 start-3 z-20 inline-flex translate-y-2 items-center gap-1.5 rounded-full bg-white/90 px-3 py-2 text-[10px] font-bold text-stone-700 opacity-0 shadow-md backdrop-blur transition duration-200 group-hover:translate-y-0 group-hover:opacity-100 group-focus-visible:translate-y-0 group-focus-visible:opacity-100"><i data-lucide="scan-eye" class="h-3.5 w-3.5"></i>{{ __('store.view_details') }}</span>
        </div>
    </a>

    <div class="flex flex-1 flex-col p-2.5 sm:p-3">
        <div class="flex items-start justify-between gap-1">
            <h3 class="line-clamp-2 min-h-9 text-sm font-extrabold leading-snug text-slate-800 transition group-hover:text-amber-800 sm:text-base"><a href="{{ route('storefront.products.show', $product['slug']) }}">{{ $product['name'] }}</a></h3>
            @if($product['is_pack'])<span class="shrink-0 rounded-full bg-amber-100 px-2 py-1 text-[9px] font-extrabold text-amber-800">{{ __('store.pack_products_count', ['count' => count($product['pack_items'])]) }}</span>@endif
        </div>

        <form class="mt-auto space-y-1.5 pt-2" action="{{ route('cart.items.store') }}" method="POST">
            @csrf
            <input type="hidden" name="return_to" value="{{ $returnTo }}">
            @if($variants->count() > 1)
                <label class="sr-only" for="variant-{{ $product['id'] }}">{{ __('orders.variant') }}</label>
                <div class="relative">
                    <select id="variant-{{ $product['id'] }}" name="variant_id" class="h-8 w-full appearance-none rounded-lg border border-stone-200 bg-stone-50 px-2.5 pe-8 text-[11px] font-semibold text-slate-700 outline-none transition focus:border-amber-400 focus:bg-white focus:ring-4 focus:ring-amber-100" required>
                        @foreach($variants as $variant)
                            <option value="{{ $variant['id'] }}" @selected($variant['id'] === $selectedVariant['id']) @disabled(! ($variant['available'] ?? true))>{{ $variant['name'] }} · {{ number_format($variant['price'], 2) }} {{ __('orders.madd') }}{{ ($variant['available'] ?? true) ? '' : ' · '.__('store.out_of_stock') }}</option>
                        @endforeach
                    </select>
                    <i data-lucide="chevron-down" class="pointer-events-none absolute end-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-stone-400"></i>
                </div>
            @else
                <span class="inline-flex min-h-7 items-center gap-1.5 rounded-lg bg-stone-50 px-2 text-[11px] font-medium text-slate-600"><i data-lucide="scale" class="h-3.5 w-3.5 text-amber-600"></i>{{ $selectedVariant['name'] ?? __('orders.product_unavailable') }}</span>
                @if($selectedVariant)<input type="hidden" name="variant_id" value="{{ $selectedVariant['id'] }}">@endif
            @endif

            <input type="hidden" name="quantity" value="1">
            <div class="flex items-center justify-between gap-1.5 border-t border-stone-100 pt-2">
                <div class="flex min-w-0 flex-col gap-0.5">
                    <span class="text-[9px] font-semibold text-stone-400">{{ __('store.price_from') }}</span>
                    <div class="flex items-baseline gap-1"><strong class="text-lg font-black tracking-tight text-emerald-800">{{ number_format($product['starting_price'], 2) }}</strong><span class="text-[10px] font-bold text-stone-500">{{ __('orders.madd') }}</span></div>
                </div>
                <button type="submit" class="inline-grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-emerald-700 text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-800 hover:shadow-md focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-emerald-200 disabled:cursor-not-allowed disabled:bg-stone-300 disabled:text-stone-500 disabled:shadow-none" aria-label="{{ __('store.add_to_cart') }}" title="{{ __('store.add_to_cart') }}" @disabled(! $canPurchase)>
                    <i data-lucide="shopping-bag" class="h-[18px] w-[18px]"></i><span class="sr-only">{{ __('store.add_to_cart') }}</span>
                </button>
            </div>
        </form>
    </div>
</article>
