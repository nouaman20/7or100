<section class="store-hero" id="home">
    <div class="hero-honeycomb honeycomb-one" aria-hidden="true"></div>
    <div class="hero-honeycomb honeycomb-two" aria-hidden="true"></div>

    <div class="store-container hero-content">
        <div class="hero-copy">
            <span class="green-kicker"><i data-lucide="badge-check"></i>{{ __('store.hero_eyebrow') }}</span>
            <h1>{{ __('store.hero_before') }} <em>{{ __('store.hero_highlight') }}</em></h1>
            <p>{{ __('store.hero_copy') }}</p>
            <a class="gold-button" href="{{ route('storefront.shop') }}">{{ __('store.discover') }} <i data-lucide="arrow-right"></i></a>

            <div class="hero-benefits">
                @foreach(__('store.benefits') as $benefit)
                    <div>
                        <span><i data-lucide="{{ $benefit['icon'] }}"></i></span>
                        <p><strong>{{ $benefit['title'] }}</strong>{{ $benefit['copy'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="hero-art">
            <div class="hero-image-glow" aria-hidden="true"></div>
            <img class="hero-image" src="{{ asset('images/hero.png') }}" alt="{{ __('store.hero_image_alt') }}" fetchpriority="high" decoding="async">
            <div class="hero-image-note">
                <span><i data-lucide="leaf"></i></span>
                <p><strong>{{ __('store.hero_eyebrow') }}</strong><small>{{ __('store.hero_image_caption') }}</small></p>
            </div>
            <span class="hero-image-honeycomb" aria-hidden="true">⬡</span>
        </div>
    </div>
</section>
