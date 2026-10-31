<x-layouts.store :locale="$locale">
    <x-store.header />
    <main>
        <x-store.hero />
        @if($bestPacks->isNotEmpty())<x-store.products :products="$bestPacks" :eyebrow="__('store.best_packs_eyebrow')" :title="__('store.best_packs_title')" />@endif
        @if($featuredProducts->isNotEmpty())<x-store.products :products="$featuredProducts" :eyebrow="__('store.featured_products_eyebrow')" :title="__('store.featured_products_title')" />@endif
        @if($newestProducts->isNotEmpty())<x-store.products :products="$newestProducts" :eyebrow="__('store.newest_products_eyebrow')" :title="__('store.newest_products_title')" />@endif
        <x-store.categories :categories="$categories" />
        <x-store.products :products="$products" :search="$search" :eyebrow="__('store.all_products_eyebrow')" :title="__('store.all_products_title')" />
        <x-store.promo />
        <x-store.about />
        <x-store.testimonials :testimonials="$testimonials" />
    </main>
    <x-store.footer />
</x-layouts.store>
