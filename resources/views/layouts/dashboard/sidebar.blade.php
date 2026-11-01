@php
    $icons = $icons ?? ['dashboard' => 'house', 'products' => 'box', 'categories' => 'layers-3', 'blog' => 'newspaper', 'orders' => 'shopping-cart', 'marketing' => 'megaphone', 'settings' => 'settings'];
@endphp
<aside class="sidebar" data-sidebar>
    <div class="brand"><img class="dashboard-brand-logo" src="{{ asset('images/logo.png') }}" alt="7or 100"></div>

    <nav class="nav-list" aria-label="{{ __('dashboard.title') }}">
        @foreach (__('dashboard.navigation') as $key => $label)
            @if ($key === 'dashboard')
                <a href="{{ route('dashboard') }}" class="nav-button {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i data-lucide="{{ $icons[$key] }}"></i><span>{{ $label }}</span>
                </a>
            @elseif ($key === 'products')
                <a href="{{ route('dashboard.products.index') }}" class="nav-button {{ request()->routeIs('dashboard.products.*') ? 'active' : '' }}">
                    <i data-lucide="{{ $icons[$key] }}"></i><span>{{ $label }}</span>
                </a>
            @elseif ($key === 'categories')
                <a href="{{ route('dashboard.categories.index') }}" class="nav-button {{ request()->routeIs('dashboard.categories.*') ? 'active' : '' }}">
                    <i data-lucide="{{ $icons[$key] }}"></i><span>{{ $label }}</span>
                </a>
            @elseif ($key === 'orders')
                <a href="{{ route('dashboard.orders.index') }}" class="nav-button {{ request()->routeIs('dashboard.orders.*') ? 'active' : '' }}">
                    <i data-lucide="{{ $icons[$key] }}"></i><span>{{ $label }}</span>
                </a>
            @elseif ($key === 'marketing')
                <a href="{{ route('dashboard.marketing.index') }}" class="nav-button {{ request()->routeIs('dashboard.marketing.*') ? 'active' : '' }}">
                    <i data-lucide="{{ $icons[$key] }}"></i><span>{{ $label }}</span>
                </a>
            @elseif ($key === 'settings')
                <a href="{{ route('dashboard.settings.index') }}" class="nav-button {{ request()->routeIs('dashboard.settings.*') ? 'active' : '' }}">
                    <i data-lucide="{{ $icons[$key] }}"></i><span>{{ $label }}</span>
                </a>
            @elseif ($key === 'blog')
                <a href="{{ route('dashboard.blog.index') }}" class="nav-button {{ request()->routeIs('dashboard.blog.*') ? 'active' : '' }}">
                    <i data-lucide="{{ $icons[$key] }}"></i><span>{{ $label }}</span>
                </a>
            @endif
        @endforeach
    </nav>

    <div class="sidebar-honey" aria-hidden="true"><i class="leaf"></i><i class="honey-jar"></i><i
            class="honeycomb"></i><span class="bee">🐝</span></div>
    <div class="profile-dropdown-wrapper">
    <button
        type="button"
        class="profile"
        id="profileToggle"
        aria-expanded="false"
        aria-controls="profileDropdown"
    >
        <div class="avatar">👤</div>

        <div class="profile-copy">
            <div class="profile-name">{{ auth()->user()->name }}</div>
            <div class="profile-role">{{ __('dashboard.user_role') }}</div>
        </div>

        <i class="profile-chevron" data-lucide="chevron-down"></i>
    </button>

    <div class="profile-dropdown" id="profileDropdown" hidden>
        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit" class="logout-button">
                <i data-lucide="log-out"></i>
                <span>{{ __('dashboard.logout') }}</span>
            </button>
        </form>
    </div>
</div>

</aside>
