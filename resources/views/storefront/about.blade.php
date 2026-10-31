<x-layouts.store :locale="$locale">
    <x-store.header />
    <main>
        <section class="content-page-hero about-page-hero">
            <div class="store-container content-page-hero-inner">
                <span>{{ __('store.about_page_eyebrow') }}</span>
                <h1>{{ __('store.about_page_title') }}</h1>
                <p>{{ __('store.about_page_intro') }}</p>
                <a class="gold-button" href="{{ route('storefront.shop') }}">{{ __('store.about_cta') }} <i data-lucide="arrow-right"></i></a>
            </div>
        </section>
        <x-store.about />
        <section class="about-story-section">
            <div class="store-container about-story-grid">
                <div class="about-story-mark" aria-hidden="true"><span>7or</span><i>✿</i></div>
                <div>
                    <x-store.section-heading :eyebrow="__('store.about_page_eyebrow')" :title="__('store.about_story_title')" />
                    <p>{{ __('store.about_story_first') }}</p>
                    <p>{{ __('store.about_story_second') }}</p>
                </div>
            </div>
        </section>
    </main>
    <x-store.footer />
</x-layouts.store>
