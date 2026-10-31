<x-layouts.store :locale="$locale">
    <x-store.header />
    <main class="contact-page">
        <section class="content-page-hero contact-page-hero">
            <div class="store-container content-page-hero-inner">
                <span>{{ __('store.contact_page_eyebrow') }}</span>
                <h1>{{ __('store.contact_page_title') }}</h1>
                <p>{{ __('store.contact_page_copy') }}</p>
            </div>
        </section>

        <section class="store-container contact-content">
            <div class="contact-form-panel">
                @if(session('contact_success'))<div class="contact-alert success" role="status">{{ session('contact_success') }}</div>@endif
                @if(session('contact_error'))<div class="contact-alert error" role="alert">{{ session('contact_error') }}</div>@endif
                <header><span>{{ __('store.contact_form_title') }}</span><p>{{ __('store.contact_form_copy') }}</p></header>
                <form class="contact-form" action="{{ route('storefront.contact.send') }}" method="POST">
                    @csrf
                    <div class="contact-field-row">
                        <label>{{ __('store.contact_name') }}<input type="text" name="name" value="{{ old('name') }}" autocomplete="name" maxlength="255" required>@error('name')<small>{{ $message }}</small>@enderror</label>
                        <label>{{ __('store.contact_email') }}<input type="email" name="email" value="{{ old('email') }}" autocomplete="email" maxlength="255" required>@error('email')<small>{{ $message }}</small>@enderror</label>
                    </div>
                    <label>{{ __('store.contact_subject') }}<input type="text" name="subject" value="{{ old('subject') }}" maxlength="255" required>@error('subject')<small>{{ $message }}</small>@enderror</label>
                    <label>{{ __('store.contact_message') }}<textarea name="message" rows="7" maxlength="5000" required>{{ old('message') }}</textarea>@error('message')<small>{{ $message }}</small>@enderror</label>
                    <button class="gold-button" type="submit"><i data-lucide="send"></i>{{ __('store.contact_send') }}</button>
                </form>
            </div>

            <aside class="contact-aside">
                <div class="contact-aside-art" aria-hidden="true"><span>🍯</span><i>✿</i></div>
                <h2>{{ __('store.contact_info_title') }}</h2>
                <p>{{ __('store.contact_info_copy') }}</p>
                <div class="contact-aside-note"><i data-lucide="mail-check"></i><span>{{ __('store.contact_privacy_copy') }}</span></div>
                <a href="{{ route('storefront.shop') }}">{{ __('store.about_cta') }} <i data-lucide="arrow-right"></i></a>
            </aside>
        </section>
    </main>
    <x-store.footer />
</x-layouts.store>
