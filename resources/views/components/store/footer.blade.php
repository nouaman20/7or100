<footer class="store-footer">
    <section class="newsletter-band">
        <div class="store-container newsletter-card">
            <div class="newsletter-copy">
                <span><i data-lucide="sparkles"></i>{{ __('newsletter.footer_eyebrow') }}</span>
                <h2>{{ __('newsletter.footer_title') }}</h2>
                <p>{{ __('newsletter.footer_copy') }}</p>
            </div>
            <div class="newsletter-form-area">
                @if(session('newsletter_status'))<p class="newsletter-success" role="status"><i data-lucide="circle-check"></i>{{ session('newsletter_status') }}</p>@endif
                <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                    @csrf
                    <div class="newsletter-input-row">
                        <label class="sr-only" for="newsletterEmail">{{ __('newsletter.email') }}</label>
                        <input id="newsletterEmail" type="email" name="newsletter_email" value="{{ old('newsletter_email') }}" placeholder="{{ __('newsletter.email_placeholder') }}" autocomplete="email" maxlength="255" required>
                        <button type="submit"><span>{{ __('newsletter.subscribe') }}</span><i data-lucide="arrow-right"></i></button>
                    </div>
                    @error('newsletter_email', 'newsletter')<small class="newsletter-error">{{ $message }}</small>@enderror
                    <label class="newsletter-consent"><input type="checkbox" name="newsletter_consent" value="1" @checked(old('newsletter_consent') === '1') required><span>{{ __('newsletter.consent') }}</span></label>
                    @error('newsletter_consent', 'newsletter')<small class="newsletter-error">{{ $message }}</small>@enderror
                    <small class="newsletter-privacy">{{ __('newsletter.privacy_note') }}</small>
                </form>
            </div>
            <span class="newsletter-honeycomb" aria-hidden="true">⬡ ⬡ ⬡</span>
        </div>
    </section>

    <div class="store-container footer-main">
        <div class="footer-brand-block">
            <a class="store-logo" href="{{ route('storefront.home') }}" aria-label="7or 100"><img class="store-logo-image" src="{{ asset('images/logo.png') }}" alt="7or 100"></a>
            <p>{{ __('newsletter.footer_brand') }}</p>
        </div>
        <div class="footer-link-block">
            <h3>{{ __('newsletter.footer_links') }}</h3>
            <nav aria-label="{{ __('newsletter.footer_links') }}">
                @foreach(__('store.navigation') as $key => $label)
                    <a href="{{ match ($key) {'home' => route('storefront.home'), 'shop' => route('storefront.shop'), 'about' => route('storefront.about'), 'blog' => route('storefront.blog.index'), 'contact' => route('storefront.contact'), default => route('storefront.home')} }}">{{ $label }}</a>
                @endforeach
            </nav>
        </div>
        <div class="footer-social-block">
            <h3>{{ __('newsletter.footer_follow') }}</h3>
            <a class="footer-contact-link" href="{{ route('storefront.contact') }}"><i data-lucide="mail"></i>{{ __('store.navigation.contact') }}</a>
        </div>
    </div>

    <div class="store-container footer-bottom"><span>{{ __('store.copyright') }}</span><em>{{ __('store.footer_note') }} <i data-lucide="leaf"></i></em></div>
</footer>
