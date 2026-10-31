<x-layouts.store :locale="$locale" :meta-title="$metaTitle" :meta-description="$metaDescription" :canonical-url="$canonicalUrl" :og-image="$ogImage" :json-ld="$jsonLd">
    <x-store.header />
    <main class="store-container blog-article-page">
        <nav class="blog-breadcrumbs" aria-label="{{ __('store.breadcrumbs') }}"><a href="{{ route('storefront.home') }}">{{ __('store.home') }}</a><i data-lucide="chevron-right"></i><a href="{{ route('storefront.blog.index') }}">{{ __('blog.page_title') }}</a><i data-lucide="chevron-right"></i><span>{{ $post->title }}</span></nav>
        <article class="blog-article">
            <header class="blog-article-header">
                @if($post->is_featured)<span class="blog-article-featured"><i data-lucide="star"></i>{{ __('blog.featured') }}</span>@endif
                <h1>{{ $post->title }}</h1>
                <div class="blog-article-meta"><span>{{ __('blog.by', ['author' => $post->author?->name ?: '7or']) }}</span><span>·</span><time datetime="{{ $post->published_at->toDateString() }}">{{ __('blog.published', ['date' => $post->published_at->translatedFormat('d M Y')]) }}</time><span>·</span><span>{{ __('blog.read_time', ['minutes' => $post->reading_time_minutes]) }}</span></div>
                @if($post->excerpt)<p class="blog-article-excerpt">{{ $post->excerpt }}</p>@endif
            </header>
            @if($post->cover_image)<figure class="blog-article-cover"><img src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($post->cover_image) }}" alt="{{ $post->cover_image_alt ?: $post->title }}"></figure>@endif
            <div class="blog-article-body">{!! Illuminate\Support\Str::markdown($post->body, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}</div>
            <a class="blog-back-link" href="{{ route('storefront.blog.index') }}"><i data-lucide="arrow-left"></i>{{ __('blog.back') }}</a>
        </article>
        @if($relatedPosts->isNotEmpty())
            <section class="blog-related"><div class="blog-related-heading"><span>{{ __('blog.eyebrow') }}</span><h2>{{ __('blog.related') }}</h2></div><div class="blog-grid blog-related-grid">
                @foreach($relatedPosts as $related)<article class="blog-card">
                    <a class="blog-card-image" href="{{ route('storefront.blog.show', $related->slug) }}">@if($related->cover_image)<img src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($related->cover_image) }}" alt="{{ $related->cover_image_alt ?: $related->title }}" loading="lazy">@else<span class="blog-card-placeholder" aria-hidden="true">🍯</span>@endif</a>
                    <div class="blog-card-content"><div class="blog-card-meta"><time datetime="{{ $related->published_at->toDateString() }}">{{ $related->published_at->translatedFormat('d M Y') }}</time></div><h3><a href="{{ route('storefront.blog.show', $related->slug) }}">{{ $related->title }}</a></h3><a class="blog-read-link" href="{{ route('storefront.blog.show', $related->slug) }}">{{ __('blog.read_article') }} <i data-lucide="arrow-right"></i></a></div>
                </article>@endforeach
            </div></section>
        @endif
    </main>
    <x-store.footer />
</x-layouts.store>
