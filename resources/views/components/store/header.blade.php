<header class="store-header">
    <div class="store-container header-inner">
        <a class="store-logo" href="{{ route('storefront.home') }}" aria-label="7or 100">
            <img class="store-logo-image" src="{{ asset('images/logo.png') }}" alt="7or 100">
        </a>
        <nav class="store-nav" aria-label="Navigation principale">
            @foreach (__('store.navigation') as $key => $label)
                @php
                    $pageRoute = match ($key) {
                        'home' => 'storefront.home',
                        'shop' => 'storefront.shop',
                        'about' => 'storefront.about',
                        'blog' => 'storefront.blog.index',
                        'contact' => 'storefront.contact',
                        default => null,
                    };
                    $active = ($key === 'home' && request()->routeIs('storefront.home'))
                        || ($key === 'shop' && request()->routeIs('storefront.shop', 'storefront.products.show'))
                        || ($key === 'about' && request()->routeIs('storefront.about'))
                        || ($key === 'blog' && request()->routeIs('storefront.blog.*'))
                        || ($key === 'contact' && request()->routeIs('storefront.contact'));
                @endphp
                <a class="{{ $active ? 'active' : '' }}" href="{{ $pageRoute ? route($pageRoute) : route('storefront.home').'#'.$key }}">{{ $label }}</a>
            @endforeach
        </nav>
        <div class="header-actions">
            <form class="store-search" action="{{ route('storefront.shop') }}" method="GET"><i data-lucide="search"></i><input type="search" name="q" value="{{ request('q') }}"
                    placeholder="{{ __('store.search') }}" aria-label="{{ __('store.search') }}"></form>
            <button class="header-icon" type="button" data-store-message="{{ __('orders.account_not_required') }}"
                aria-label="{{ __('orders.customer') }}"><i data-lucide="user-round"></i></button>
            <button class="header-icon cart-button" type="button" data-cart-open aria-label="{{ __('orders.cart') }}" aria-controls="storeCart" aria-expanded="false">
                <i data-lucide="shopping-cart"></i><b data-cart-count>{{ array_sum(session('cart', [])) }}</b>
            </button>
        </div>
    </div>
</header>
