<x-layouts.store :locale="$locale" :meta-title="$metaTitle" :meta-description="$metaDescription" :canonical-url="$canonicalUrl" :json-ld="$jsonLd">
    <x-store.header />
    <main class="store-container blog-page">
        <header class="blog-heading">
            <span class="blog-eyebrow"><i data-lucide="sparkles"></i>{{ __('blog.eyebrow') }}</span>
            <h1>{{ __('blog.page_title') }}</h1>
            <p>{{ __('blog.intro') }}</p>
            <form class="blog-search" action="{{ route('storefront.blog.index') }}" method="GET">
                <i data-lucide="search"></i><input type="search" name="q" value="{{ $search }}" placeholder="{{ __('blog.search') }}" aria-label="{{ __('blog.search') }}">
                <button type="submit">{{ __('blog.search_button') }}</button>
            </form>
        </header>
        <section class="blog-listing" aria-label="{{ __('blog.page_title') }}">
            @if($posts->isNotEmpty())<p class="blog-result-count">{{ $posts->total() }} {{ __('blog.results') }}</p>@endif
            <div class="blog-grid">
                @forelse($posts as $post)
                    <article class="blog-card">
                        <a class="blog-card-image" href="{{ route('storefront.blog.show', $post->slug) }}" aria-label="{{ __('blog.read_article') }}: {{ $post->title }}">
                            @if($post->cover_image)<img src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($post->cover_image) }}" alt="{{ $post->cover_image_alt ?: $post->title }}" loading="lazy">@else<span class="blog-card-placeholder" aria-hidden="true">🍯</span>@endif
                            @if($post->is_featured)<span class="blog-featured-badge"><i data-lucide="star"></i>{{ __('blog.featured') }}</span>@endif
                        </a>
                        <div class="blog-card-content">
                            <div class="blog-card-meta"><time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('d M Y') }}</time><span>·</span><span>{{ __('blog.read_time', ['minutes' => $post->reading_time_minutes]) }}</span></div>
                            <h2><a href="{{ route('storefront.blog.show', $post->slug) }}">{{ $post->title }}</a></h2>
                            @if($post->excerpt)<p>{{ $post->excerpt }}</p>@endif
                            <a class="blog-read-link" href="{{ route('storefront.blog.show', $post->slug) }}">{{ __('blog.read_article') }} <i data-lucide="arrow-right"></i></a>
                        </div>
                    </article>
                @empty
                    <div class="blog-empty"><i data-lucide="notebook-text"></i><h2>{{ $search ? __('blog.no_results') : __('blog.empty') }}</h2></div>
                @endforelse
            </div>
            @if($posts->hasPages())<nav class="blog-pagination" aria-label="Pagination">{{ $posts->links() }}</nav>@endif
        </section>
    </main>
    <x-store.footer />
</x-layouts.store>
