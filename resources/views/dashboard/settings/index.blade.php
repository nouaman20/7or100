@extends('dashboard')

@section('content')
    <div class="page">
        <header class="topbar">
            <div class="title-group"><button class="mobile-menu" type="button" data-menu-toggle aria-label="Menu"><i data-lucide="menu"></i></button><div class="title-bee" aria-hidden="true">🐝</div><div><h1>{{ __('settings.title') }}</h1><p>{{ __('settings.subtitle') }}</p></div></div>
        </header>

        <div class="settings-flashes">
            @foreach(['profile_status', 'password_status', 'language_status'] as $statusKey)
                @if(session($statusKey))<p class="language-status" role="status">{{ session($statusKey) }}</p>@endif
            @endforeach
        </div>

        <div class="settings-grid">
            <section class="settings-panel">
                <header><span class="settings-panel-icon amber"><i data-lucide="user-round-cog"></i></span><div><h2>{{ __('settings.profile_title') }}</h2><p>{{ __('settings.profile_copy') }}</p></div></header>
                <form class="settings-form" action="{{ route('dashboard.settings.profile.update') }}" method="POST">
                    @csrf @method('PUT')
                    <label>{{ __('settings.name') }}<input type="text" name="name" value="{{ old('name', $user->name) }}" autocomplete="name" maxlength="255" required>@error('name', 'profile')<small>{{ $message }}</small>@enderror</label>
                    <label>{{ __('settings.email') }}<input type="email" name="email" value="{{ old('email', $user->email) }}" autocomplete="email" maxlength="255" required>@error('email', 'profile')<small>{{ $message }}</small>@enderror</label>
                    <label class="settings-full">{{ __('settings.current_password') }}<input type="password" name="current_password" autocomplete="current-password" required>@error('current_password', 'profile')<small>{{ $message }}</small>@enderror</label>
                    <button class="gold-button" type="submit"><i data-lucide="save"></i>{{ __('settings.save_profile') }}</button>
                </form>
            </section>

            <section class="settings-panel">
                <header><span class="settings-panel-icon green"><i data-lucide="key-round"></i></span><div><h2>{{ __('settings.password_title') }}</h2><p>{{ __('settings.password_copy') }}</p></div></header>
                <form class="settings-form" action="{{ route('dashboard.settings.password.update') }}" method="POST">
                    @csrf @method('PUT')
                    <label class="settings-full">{{ __('settings.current_password') }}<input type="password" name="current_password" autocomplete="current-password" required>@error('current_password', 'password')<small>{{ $message }}</small>@enderror</label>
                    <label>{{ __('settings.new_password') }}<input type="password" name="password" autocomplete="new-password" minlength="8" required>@error('password', 'password')<small>{{ $message }}</small>@enderror</label>
                    <label>{{ __('settings.confirm_password') }}<input type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required>@error('password_confirmation', 'password')<small>{{ $message }}</small>@enderror</label>
                    <button class="gold-button" type="submit"><i data-lucide="lock-keyhole"></i>{{ __('settings.save_password') }}</button>
                </form>
            </section>

            <section class="settings-panel settings-language-panel">
                <header><span class="settings-panel-icon violet"><i data-lucide="languages"></i></span><div><h2>{{ __('settings.language_title') }}</h2><p>{{ __('settings.language_copy') }}</p></div></header>
                <form class="settings-language-form" action="{{ route('dashboard.settings.language.update') }}" method="POST">
                    @csrf @method('PUT')
                    <label>{{ __('dashboard.site_language') }}<select name="locale" required><option value="fr" @selected($locale === 'fr')>{{ __('dashboard.french') }}</option><option value="ar" @selected($locale === 'ar')>{{ __('dashboard.arabic') }}</option></select></label>
                    @error('locale')<small>{{ $message }}</small>@enderror
                    <button class="gold-button" type="submit"><i data-lucide="save"></i>{{ __('settings.save_language') }}</button>
                </form>
            </section>
        </div>
    </div>
@endsection
