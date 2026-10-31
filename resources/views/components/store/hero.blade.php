<section class="store-hero" id="home">
    <div class="hero-honeycomb honeycomb-one" aria-hidden="true"></div><div class="hero-honeycomb honeycomb-two" aria-hidden="true"></div>
    <div class="store-container hero-content">
        <div class="hero-copy"><span class="green-kicker"><i data-lucide="badge-check"></i>{{ __('store.hero_eyebrow') }}</span><h1>{{ __('store.hero_before') }} <em>{{ __('store.hero_highlight') }}</em></h1><p>{{ __('store.hero_copy') }}</p><a class="gold-button" href="{{ route('storefront.shop') }}">{{ __('store.discover') }} <i data-lucide="arrow-right"></i></a>
            <div class="hero-benefits">@foreach(__('store.benefits') as $benefit)<div><span><i data-lucide="{{ $benefit['icon'] }}"></i></span><p><strong>{{ $benefit['title'] }}</strong>{{ $benefit['copy'] }}</p></div>@endforeach</div>
        </div>
        <div class="hero-art" aria-hidden="true"><i class="hero-leaf leaf-a"></i><i class="hero-leaf leaf-b"></i><i class="hero-flower flower-a">✿</i><i class="hero-flower flower-b">✿</i><i class="hero-almonds">● ● ●</i><div class="hero-jar large"><b>🐝</b><span>Miel Pur<small>100% Naturel</small></span></div><div class="hero-jar small"><span>Amlou<small>Traditionnel</small></span></div><i class="honey-dipper">🍯</i></div>
    </div>
</section>
