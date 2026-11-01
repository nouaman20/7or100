@props(['locale' => app()->getLocale(), 'metaTitle' => null, 'metaDescription' => null, 'canonicalUrl' => null, 'ogImage' => null, 'jsonLd' => null, 'robots' => 'index,follow'])
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#f7a900">
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
        <title>{{ $metaTitle ?: __('store.meta_title') }}</title>
        <meta name="description" content="{{ $metaDescription ?: __('store.meta_description') }}">
        <meta name="robots" content="{{ $robots }}">
        @if ($canonicalUrl)<link rel="canonical" href="{{ $canonicalUrl }}">@endif
        <meta property="og:type" content="{{ request()->routeIs('storefront.blog.show') ? 'article' : 'website' }}">
        <meta property="og:title" content="{{ $metaTitle ?: __('store.meta_title') }}">
        <meta property="og:description" content="{{ $metaDescription ?: __('store.meta_description') }}">
        @if ($canonicalUrl)<meta property="og:url" content="{{ $canonicalUrl }}">@endif
        @if ($ogImage)<meta property="og:image" content="{{ str_starts_with($ogImage, 'http') ? $ogImage : url($ogImage) }}">@endif
        <meta name="twitter:card" content="{{ $ogImage ? 'summary_large_image' : 'summary' }}">
        @if ($jsonLd)<script type="application/ld+json">@json($jsonLd, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)</script>@endif
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/lucide@latest" defer></script>
    </head>
    <body class="storefront {{ $locale === 'ar' ? 'arabic' : '' }}">
        @php
            $storeFlash = session('cart_status') ?: session('newsletter_status') ?: $errors->first();
        @endphp
        {{ $slot }}
        <x-store.cart-sidebar />
        <div class="store-toast {{ $storeFlash ? 'show' : '' }}" data-store-toast role="status" aria-live="polite">{{ $storeFlash }}</div>
    </body>
</html>
