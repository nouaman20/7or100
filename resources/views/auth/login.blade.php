
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#fbf8ef">

    <title>{{ __('login.title') }} | 7or 100</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;500;600;700;800;900&display=swap"
          rel="stylesheet">

    <style>
        :root {
            --honey: #ffbd2f;
            --honey-dark: #e99b00;
            --forest: #203e24;
            --cream: #fbf8ef;
            --text: #152419;
            --muted: #68706b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Nunito Sans', sans-serif;
            color: var(--text);
            background: var(--cream);
        }

        .login-layout {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 54.6% 45.4%;
        }

        /* LEFT PANEL */

        .hero-panel {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
            isolation: isolate;
            background: #e9d9ae;

            /*
             * Add your image here when ready:
             * background-image: url('/images/honey-hero.jpg');
             */
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }

        /* Soft overlay keeps text readable over your photo. */
        .hero-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            background: linear-gradient(
                110deg,
                rgba(255, 251, 232, .45) 0%,
                rgba(255, 251, 232, .17) 45%,
                rgba(255, 251, 232, 0) 100%
            );
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            width: min(100% - 64px, 470px);
            margin: 0 auto;
            padding-top: 78px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: var(--forest);
            text-decoration: none;
        }

        .auth-brand-logo {
            display: block;
            width: 168px;
            height: 112px;
            object-fit: contain;
        }

        .hero-copy {
            margin-top: 34px;
            max-width: 450px;
        }

        .hero-copy h1 {
            margin: 0;
            color: #292d17;
            font-size: clamp(30px, 2.2vw, 36px);
            font-weight: 900;
            line-height: 1.08;
            letter-spacing: -.8px;
        }

        .hero-underline {
            width: 48px;
            height: 3px;
            margin: 14px 0 12px;
            border-radius: 10px;
            background: var(--honey);
        }

        .hero-copy p {
            max-width: 390px;
            margin: 0;
            color: #5e604e;
            font-size: 15px;
            line-height: 1.9;
        }

        /* RIGHT PANEL */

        .form-panel {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 0;
            min-height: 100vh;
            overflow: hidden;
            padding: 55px 42px;
            background:
                radial-gradient(ellipse at 50% 40%, #fffdf8 0%, transparent 65%),
                var(--cream);
        }

        .login-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 486px;
            margin: auto;
        }

        .login-brand {
            display: flex;
            justify-content: center;
            margin-bottom: 26px;
        }

        .login-brand .auth-brand-logo {
            width: 160px;
            height: 107px;
        }

        .login-heading {
            margin-bottom: 30px;
            text-align: center;
        }

        .login-heading h2 {
            margin: 0 0 6px;
            color: #122318;
            font-size: 31px;
            font-weight: 900;
            letter-spacing: -.5px;
        }

        .login-heading p {
            margin: 0;
            color: #566067;
            font-size: 18px;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 17px;
            width: 22px;
            height: 22px;
            color: #424b50;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .form-input {
            display: block;
            width: 100%;
            height: 62px;
            padding: 0 52px;
            border: 1px solid #e2e0da;
            border-radius: 16px;
            outline: none;
            background: rgba(255, 255, 255, .55);
            color: #25312a;
            font: inherit;
            font-size: 16px;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        .form-input::placeholder {
            color: #96989c;
        }

        .form-input:focus {
            border-color: var(--honey);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(255, 189, 47, .15);
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 14px;
            display: grid;
            place-items: center;
            width: 34px;
            height: 34px;
            padding: 0;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: #596068;
            cursor: pointer;
            transform: translateY(-50%);
        }

        .toggle-password:hover {
            background: #f5f0e4;
        }

        .toggle-password svg {
            width: 21px;
            height: 21px;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: 7px 0 12px;
            font-size: 14px;
        }

        .remember-label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .remember-label input {
            width: 20px;
            height: 20px;
            margin: 0;
            accent-color: var(--honey-dark);
            cursor: pointer;
        }

        .forgot-link {
            color: #e58f00;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-link:hover {
            color: #b66d00;
            text-decoration: underline;
        }

        .login-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            width: 100%;
            min-height: 62px;
            padding: 15px 22px;
            border: 1px solid rgba(229, 155, 0, .2);
            border-radius: 16px;
            background: linear-gradient(110deg, #ffc333, #ffbb2d);
            color: #18230f;
            font: inherit;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s, filter .2s;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 9px 22px rgba(222, 156, 25, .2);
            filter: saturate(1.08);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .login-button svg {
            width: 21px;
            height: 21px;
            transition: transform .2s;
        }

        .login-button:hover svg {
            transform: translateX(4px);
        }

        .login-button:disabled {
            opacity: .7;
            cursor: wait;
        }

        .error-message {
            margin: 7px 2px 0;
            color: #bd3434;
            font-size: 13px;
        }

        .login-error {
            padding: 12px 14px;
            border: 1px solid #f2c6c6;
            border-radius: 12px;
            background: #fff0f0;
            color: #a72828;
            font-size: 14px;
        }

        .login-footer {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            margin-top: 58px;
            color: #686b68;
            font-size: 14px;
        }

        .footer-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            width: 100%;
        }

        .footer-divider::before,
        .footer-divider::after {
            content: "";
            width: 96px;
            max-width: 25%;
            height: 1px;
            background: #dedbd2;
        }

        .mini-bee {
            display: grid;
            place-items: center;
            width: 28px;
            height: 28px;
            color: #e9a800;
        }

        .mini-bee svg {
            width: 25px;
            height: 25px;
        }

        /* Decorative honeycomb outlines */

        .honeycomb {
            position: absolute;
            z-index: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 3px;
            width: 150px;
            opacity: .6;
            pointer-events: none;
        }

        .honeycomb-top {
            top: -20px;
            right: -25px;
        }

        .honeycomb-bottom {
            bottom: -30px;
            left: -26px;
        }

        .hexagon {
            width: 56px;
            height: 64px;
            border: 1.5px solid #ffc84d;
            clip-path: polygon(25% 0, 75% 0, 100% 25%, 100% 75%, 75% 100%, 25% 100%, 0 75%, 0 25%);
            background: rgba(255, 199, 65, .035);
        }

        /* Optional botanical decorations.
           Add decorative images as background-image in these classes. */

        .leaf-decoration {
            position: absolute;
            z-index: 0;
            width: 145px;
            height: 170px;
            opacity: .8;
            pointer-events: none;
            background-repeat: no-repeat;
            background-size: contain;
        }

        .leaf-decoration-top {
            top: -48px;
            left: -48px;
        }

        .leaf-decoration-bottom {
            right: -50px;
            bottom: -40px;
            transform: rotate(180deg);
        }

        /* Accessibility */

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        a:focus-visible,
        button:focus-visible,
        input:focus-visible {
            outline: 3px solid rgba(226, 154, 0, .6);
            outline-offset: 3px;
        }

        [dir="rtl"] .input-icon {
            right: 17px;
            left: auto;
        }

        [dir="rtl"] .form-input {
            text-align: right;
        }

        [dir="rtl"] .toggle-password {
            right: auto;
            left: 14px;
        }

        [dir="rtl"] .login-button svg {
            transform: rotate(180deg);
        }

        [dir="rtl"] .login-button:hover svg {
            transform: translateX(-4px) rotate(180deg);
        }

        /* Responsive layout */

        @media (max-width: 1100px) {
            .login-layout {
                grid-template-columns: 46% 54%;
            }

            .hero-content {
                width: calc(100% - 56px);
                padding-top: 55px;
            }

            .hero-copy h1 {
                font-size: 30px;
            }

            .form-panel {
                padding: 45px 30px;
            }

            .login-brand {
                margin-bottom: 25px;
            }

            .login-brand .auth-brand-logo {
                width: 150px;
                height: 100px;
            }
        }

        @media (max-width: 760px) {
            .login-layout {
                display: block;
            }

            .hero-panel {
                display: none;
            }

            .form-panel {
                min-height: 100vh;
                min-height: 100dvh;
                padding: 38px 22px;
            }

            .login-card {
                max-width: 460px;
            }

            .login-brand {
                margin-bottom: 25px;
            }

            .login-heading h2 {
                font-size: 28px;
            }

            .login-heading p {
                font-size: 16px;
            }

            .form-input {
                height: 58px;
                border-radius: 14px;
            }

            .login-button {
                min-height: 58px;
                border-radius: 14px;
            }

            .login-footer {
                margin-top: 42px;
            }

            .honeycomb-top {
                right: -75px;
            }

            .honeycomb-bottom {
                left: -75px;
            }
        }

        @media (max-width: 380px) {
            .form-panel {
                padding: 30px 17px;
            }

            .login-brand .auth-brand-logo {
                width: 150px;
                height: 100px;
            }

            .form-options {
                font-size: 12px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
            }
        }
    </style>
</head>

<body>
<div class="login-layout">

    <!-- LEFT: BRAND / HERO PANEL -->
    <aside class="hero-panel" aria-label="7or 100">
        <div class="leaf-decoration leaf-decoration-top"></div>

        <div class="hero-content">

            <a href="{{ url('/') }}" class="brand" aria-label="7or 100 - Accueil">
                <img class="auth-brand-logo" src="{{ asset('images/logo.png') }}" alt="7or 100">
            </a>

            <div class="hero-copy">
                <h1>{{ __('login.hero_title') }}</h1>
                <div class="hero-underline"></div>
                <p>
                    {{ __('login.hero_copy') }}
                </p>
            </div>
        </div>
    </aside>

    <!-- RIGHT: LOGIN PANEL -->
    <main class="form-panel">

        <!-- Honeycomb decoration -->
        <div class="honeycomb honeycomb-top" aria-hidden="true">
            <span class="hexagon"></span>
            <span class="hexagon"></span>
            <span class="hexagon"></span>
            <span class="hexagon"></span>
            <span class="hexagon"></span>
            <span class="hexagon"></span>
        </div>

        <div class="honeycomb honeycomb-bottom" aria-hidden="true">
            <span class="hexagon"></span>
            <span class="hexagon"></span>
            <span class="hexagon"></span>
            <span class="hexagon"></span>
        </div>

        <div class="leaf-decoration leaf-decoration-bottom" aria-hidden="true"></div>

        <section class="login-card">

            <!-- Login logo -->
            <div class="login-brand">
                <a href="{{ url('/') }}" class="brand" aria-label="7or 100">
                    <img class="auth-brand-logo" src="{{ asset('images/logo.png') }}" alt="7or 100">
                </a>
            </div>

            <header class="login-heading">
                <h2>{{ __('login.heading') }}</h2>
                <p>{{ __('login.subtitle') }}</p>
            </header>

            @if ($errors->any())
                <div class="login-error" role="alert">
                    <p>{{ $errors->first() }}</p>
                </div>
            @endif

            @if (session('status'))
                <div class="login-error"
                     role="status"
                     style="border-color:#b8dfc2;background:#f0fff4;color:#24633b">
                    {{ session('status') }}
                </div>
            @endif

            <!--
                Connect this form to your Laravel authentication route.
                Change url('/login') if your route uses a different URI.
            -->
            <form
                id="loginForm"
                class="login-form"
                method="POST"
                action="{{ route("login.store") }}"
            >
                @csrf

                <!-- Email -->
                <div>
                    <div class="input-wrapper">
                        <svg class="input-icon"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8"
                             aria-hidden="true">
                            <rect x="3" y="5" width="18" height="14" rx="2.5"/>
                            <path d="m4 7 8 6 8-6"/>
                        </svg>

                        <label class="sr-only" for="email">{{ __('login.email') }}</label>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            class="form-input"
                            placeholder="{{ __('login.email') }}"
                            value="{{ old('email') }}"
                            autocomplete="username"
                            required
                            autofocus
                            aria-describedby="email-error"
                        >
                    </div>

                    @error('email')
                        <p class="error-message" id="email-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="input-wrapper">
                        <svg class="input-icon"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8"
                             aria-hidden="true">
                            <rect x="5" y="10" width="14" height="11" rx="2"/>
                            <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                            <path d="M12 14v3"/>
                        </svg>

                        <label class="sr-only" for="password">
                            {{ __('login.password') }}
                        </label>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="form-input"
                            placeholder="{{ __('login.password') }}"
                            autocomplete="current-password"
                            required
                            aria-describedby="password-error"
                        >

                        <button
                            type="button"
                            id="togglePassword"
                            class="toggle-password"
                            aria-label="{{ __('login.show_password') }}"
                            aria-pressed="false"
                        >
                            <svg id="eyeIcon"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="1.8"
                                 aria-hidden="true">
                                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p class="error-message" id="password-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember me / Forgot password -->
                <div class="form-options">
                    <label for="remember" class="remember-label">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            value="1"
                            @checked(old('remember'))
                        >
                        <span>{{ __('login.remember') }}</span>
                    </label>

                    <a href="{{ url('/forgot-password') }}" class="forgot-link">
                        {{ __('login.forgot_password') }}
                    </a>
                </div>

                <!-- Submit -->
                <button type="submit" id="loginButton" class="login-button">
                    <span id="buttonText">{{ __('login.submit') }}</span>

                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8"
                         aria-hidden="true">
                        <path d="M4 12h15"/>
                        <path d="m13 5 7 7-7 7"/>
                    </svg>
                </button>
            </form>

            <footer class="login-footer">
                <div class="footer-divider">
                    <span>{{ __('login.footer') }}</span>
                </div>

                <span class="mini-bee" aria-hidden="true">
                    <svg viewBox="0 0 32 32" fill="none">
                        <ellipse cx="16" cy="18" rx="5" ry="7"
                                 fill="#ffc02f" stroke="#172819" stroke-width="1.8"/>
                        <ellipse cx="10" cy="12" rx="5" ry="3.5"
                                 transform="rotate(-35 10 12)"
                                 fill="#fff8df" stroke="#172819" stroke-width="1.5"/>
                        <ellipse cx="22" cy="12" rx="5" ry="3.5"
                                 transform="rotate(35 22 12)"
                                 fill="#fff8df" stroke="#172819" stroke-width="1.5"/>
                        <path d="M12 17h8M12 21h8M14 6l-2-3M18 6l2-3"
                              stroke="#172819" stroke-width="1.8" stroke-linecap="round"/>
                        <circle cx="14" cy="9" r="1" fill="#172819"/>
                        <circle cx="18" cy="9" r="1" fill="#172819"/>
                    </svg>
                </span>
            </footer>

        </section>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        const loginForm = document.getElementById('loginForm');
        const loginButton = document.getElementById('loginButton');
        const buttonText = document.getElementById('buttonText');

        // Show / hide password
        togglePassword?.addEventListener('click', () => {
            if (!passwordInput || !eyeIcon) return;

            const isPassword = passwordInput.type === 'password';

            passwordInput.type = isPassword ? 'text' : 'password';

            togglePassword.setAttribute('aria-pressed', String(isPassword));
            togglePassword.setAttribute(
                'aria-label',
                isPassword ? @json(__('login.hide_password')) : @json(__('login.show_password'))
            );

            eyeIcon.innerHTML = isPassword
                ? `
                    <path d="M3 3l18 18"/>
                    <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                    <path d="M9.9 5.2A10.8 10.8 0 0 1 12 5c6.4 0 10 7 10 7a15.6 15.6 0 0 1-3.1 3.9"/>
                    <path d="M6.2 6.2C3.5 8.1 2 12 2 12s3.6 7 10 7a10 10 0 0 0 4-.8"/>
                  `
                : `
                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/>
                    <circle cx="12" cy="12" r="3"/>
                  `;
        });

        // Prevent accidental double-click submissions.
        // Laravel remains responsible for validating credentials.
        loginForm?.addEventListener('submit', () => {
            if (!loginForm.checkValidity()) return;

            loginButton.disabled = true;
            buttonText.textContent = @json(__('login.submitting'));
        });
    });
</script>
</body>
</html>
