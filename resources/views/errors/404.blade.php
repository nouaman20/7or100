@php
    $storeLocale = \App\Models\SiteSetting::locale();
    app()->setLocale($storeLocale);
@endphp

<x-layouts.store
    :locale="$storeLocale"
    :meta-title="__('errors.not_found_title').' · 7or'"
    :meta-description="__('errors.not_found_copy')"
    robots="noindex,follow"
>
    <x-store.header />

    <main class="store-container flex min-h-[68vh] items-center py-10 sm:py-14">
        <section class="relative isolate grid w-full overflow-hidden rounded-[2rem] border border-amber-100 bg-gradient-to-br from-[#fffdf7] via-white to-amber-50 px-6 py-9 shadow-[0_20px_70px_rgba(86,62,17,0.08)] sm:px-10 lg:min-h-[510px] lg:grid-cols-[1fr_0.95fr] lg:px-14">
            <div class="pointer-events-none absolute -start-14 -top-16 -z-10 h-56 w-56 rounded-full bg-amber-100/60 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-20 end-[38%] -z-10 h-64 w-64 rounded-full bg-emerald-100/40 blur-3xl"></div>

            <div class="relative z-10 flex flex-col items-start justify-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-100 bg-emerald-50 px-3.5 py-2 text-xs font-bold text-emerald-800">
                    <i data-lucide="flower-2" class="h-4 w-4"></i>{{ __('errors.not_found_eyebrow') }}
                </span>
                <p class="mb-1 mt-6 text-7xl font-black leading-none tracking-[-0.08em] text-amber-500/90 sm:text-8xl">404</p>
                <h1 class="max-w-xl text-3xl font-black leading-tight text-slate-800 sm:text-4xl lg:text-[2.7rem]">{{ __('errors.not_found_title') }}</h1>
                <p class="mt-4 max-w-lg text-sm leading-7 text-slate-600 sm:text-base">{{ __('errors.not_found_copy') }}</p>

                <div class="mt-7 flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
                    <a class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 text-sm font-extrabold text-white shadow-lg shadow-emerald-900/10 transition hover:-translate-y-0.5 hover:bg-emerald-800 hover:shadow-xl focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-emerald-200" href="{{ route('storefront.home') }}">
                        <i data-lucide="house" class="h-4 w-4"></i>{{ __('errors.back_home') }}
                    </a>
                    <a class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl border border-amber-200 bg-white/80 px-5 text-sm font-extrabold text-amber-800 transition hover:-translate-y-0.5 hover:border-amber-300 hover:bg-amber-50 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-amber-100" href="{{ route('storefront.shop') }}">
                        {{ __('errors.browse_shop') }}<i data-lucide="arrow-up-left" class="h-4 w-4 rtl:rotate-180"></i>
                    </a>
                </div>
            </div>

            <div class="relative mx-auto mt-8 flex w-full max-w-md items-center justify-center lg:my-0 lg:max-w-none">
                <div class="absolute aspect-square w-[88%] rounded-full border border-amber-200/60 bg-[radial-gradient(circle,#fff4d6_0%,rgba(255,249,235,0.58)_56%,transparent_70%)]"></div>
                <span class="absolute end-[8%] top-[8%] text-5xl text-amber-300/60" aria-hidden="true">⬡</span>
                <img class="relative z-10 block w-full max-w-[470px] object-contain drop-shadow-[0_20px_22px_rgba(112,76,16,0.16)]" src="{{ asset('images/hero.png') }}" alt="{{ __('errors.not_found_image_alt') }}" loading="lazy">
                <span class="absolute bottom-4 start-1 rounded-2xl border border-white/80 bg-white/90 px-4 py-3 text-xs font-bold text-emerald-800 shadow-lg shadow-stone-900/5 backdrop-blur sm:start-4">
                    <i data-lucide="heart" class="me-1 inline h-4 w-4 text-amber-500"></i>{{ __('errors.natural_goodness') }}
                </span>
            </div>
        </section>
    </main>

    <x-store.footer />
</x-layouts.store>
