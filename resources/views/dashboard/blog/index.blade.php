@extends('dashboard')

@section('content')
<div class="page">
    <header class="topbar"><div class="title-group"><button class="mobile-menu" type="button" data-menu-toggle aria-label="Menu"><i data-lucide="menu"></i></button><div class="title-bee">🐝</div><div><h1>{{ __('blog.admin_title') }}</h1><p>{{ __('blog.admin_subtitle') }}</p></div></div><a class="gold-button" href="{{ route('dashboard.blog.create') }}"><i data-lucide="plus"></i>{{ __('blog.create') }}</a></header>
    @if(session('status'))<p class="language-status" role="status">{{ session('status') }}</p>@endif
    <section class="orders-table-wrap blog-admin-wrap">
        <form class="blog-admin-filters" method="GET" action="{{ route('dashboard.blog.index') }}"><input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="{{ __('blog.search') }}"><select name="status"><option value="">{{ __('blog.all_statuses') }}</option><option value="draft" @selected(($filters['status'] ?? '') === 'draft')>{{ __('blog.draft') }}</option><option value="published" @selected(($filters['status'] ?? '') === 'published')>{{ __('blog.published_status') }}</option></select><button type="submit">{{ __('blog.filter') }}</button></form>
        <div class="overflow-x-auto"><table class="orders-table blog-admin-table"><thead><tr><th>{{ __('blog.title') }}</th><th>{{ __('blog.status') }}</th><th>{{ __('blog.created') }}</th><th>{{ __('blog.actions') }}</th></tr></thead><tbody>
            @forelse($posts as $post)<tr><td><div class="blog-admin-title-cell">@if($post->cover_image)<img src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($post->cover_image) }}" alt="">@else<span aria-hidden="true">🍯</span>@endif<div><strong>{{ $post->title }}</strong><small>{{ $post->slug }} @if($post->is_featured) · {{ __('blog.featured') }} @endif</small></div></div></td><td><span class="blog-status-pill {{ $post->status }}">{{ $post->status === 'published' ? __('blog.published_status') : __('blog.draft') }}</span></td><td>{{ $post->updated_at->format('d/m/Y') }}</td><td><div class="product-row-actions">@if($post->status === 'published')<a href="{{ route('storefront.blog.show', $post->slug) }}" target="_blank" rel="noopener">{{ __('blog.read_article') }}</a>@endif<a href="{{ route('dashboard.blog.edit', $post) }}">{{ __('blog.edit') }}</a><form action="{{ route('dashboard.blog.destroy', $post) }}" method="POST" onsubmit="return confirm(@js(__('blog.confirm_delete')))" >@csrf @method('DELETE')<button type="submit">{{ __('blog.delete') }}</button></form></div></td></tr>
            @empty<tr><td colspan="4" class="orders-empty">{{ __('blog.no_posts') }} <a href="{{ route('dashboard.blog.create') }}">{{ __('blog.create') }}</a></td></tr>@endforelse
        </tbody></table></div><div class="orders-pagination">{{ $posts->links() }}</div>
    </section>
</div>
@endsection
