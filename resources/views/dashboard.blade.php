@php
    $locale = $locale ?? app()->getLocale();
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#1d3115">
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
        <title>{{ __('dashboard.title') }} · 7or 100</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/lucide@latest" defer></script>
    </head>
    <body class="{{ $locale === 'ar' ? 'arabic' : '' }}">
        @php
            $icons = ['dashboard' => 'house', 'products' => 'box', 'categories' => 'layers-3', 'blog' => 'newspaper', 'orders' => 'shopping-cart', 'marketing' => 'megaphone', 'settings' => 'settings'];
            $months = $locale === 'ar' ? ['يناير', 'فبراير', 'مارس', 'أبريل', 'ماي', 'يونيو', 'يوليوز', 'غشت', 'شتنبر', 'أكتوبر', 'نونبر', 'ديسمبر'] : ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc 2025'];
        @endphp
        <div class="app-shell">
            
            @include("layouts.dashboard.sidebar")

            <main class="main">
                @yield("content")
            </main>
        </div>
        <div class="toast" data-toast role="status" aria-live="polite"></div>
    </body>
</html>
