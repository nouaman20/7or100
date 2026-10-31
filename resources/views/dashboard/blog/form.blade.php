@extends('dashboard')

@section('content')
<div class="page">
    <header class="topbar"><div class="title-group"><a class="product-back-link" href="{{ route('dashboard.blog.index') }}" aria-label="{{ __('blog.cancel') }}"><i data-lucide="arrow-left"></i></a><div class="title-bee">🐝</div><div><h1>{{ $editing ? __('blog.edit') : __('blog.create') }}</h1><p>{{ __('blog.admin_subtitle') }}</p></div></div></header>
    @if($errors->any())<div class="commerce-alert" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form class="blog-editor-form" action="{{ $editing ? route('dashboard.blog.update', $post) : route('dashboard.blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf @if($editing)@method('PUT')@endif
        <div class="blog-editor-main">
            <section class="product-form-panel"><div class="product-panel-heading"><span>01</span><div><h2>{{ __('blog.title') }}</h2><p>{{ __('blog.slug_hint') }}</p></div></div>
                <div class="product-form-grid"><label>{{ __('blog.title') }}<input name="title" id="blogTitle" value="{{ old('title', $post->title) }}" required maxlength="255"></label><label>{{ __('blog.slug') }}<input name="slug" id="blogSlug" value="{{ old('slug', $post->slug) }}" required maxlength="255"></label><label class="wide">{{ __('blog.excerpt') }}<textarea name="excerpt" rows="3" maxlength="500">{{ old('excerpt', $post->excerpt) }}</textarea></label><label class="wide">{{ __('blog.body') }}<textarea name="body" rows="18" required maxlength="100000" class="blog-markdown-input">{{ old('body', $post->body) }}</textarea><small>{{ __('blog.body_help') }}</small></label></div>
            </section>
            <section class="product-form-panel"><div class="product-panel-heading"><span>02</span><div><h2>{{ __('blog.seo') }}</h2><p>{{ __('blog.page_description') }}</p></div></div><div class="product-form-grid"><label>{{ __('blog.meta_title') }}<input name="meta_title" value="{{ old('meta_title', $post->meta_title) }}" maxlength="70"></label><label class="wide">{{ __('blog.meta_description') }}<textarea name="meta_description" rows="3" maxlength="160">{{ old('meta_description', $post->meta_description) }}</textarea></label></div></section>
        </div>
        <aside class="blog-editor-aside">
            <section class="product-form-panel"><div class="product-panel-heading"><span>03</span><div><h2>{{ __('blog.status') }}</h2></div></div><div class="blog-publish-fields"><label>{{ __('blog.status') }}<select name="status"><option value="draft" @selected(old('status', $post->status ?: 'draft') === 'draft')>{{ __('blog.draft') }}</option><option value="published" @selected(old('status', $post->status) === 'published')>{{ __('blog.published_status') }}</option></select></label><label class="product-check"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $post->is_featured))>{{ __('blog.featured_toggle') }}</label></div></section>
            <section class="product-form-panel"><div class="product-panel-heading"><span>04</span><div><h2>{{ __('blog.cover') }}</h2></div></div><div class="blog-cover-field">@if($post->cover_image)<img class="blog-cover-preview" src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($post->cover_image) }}" alt="">@endif<label>{{ __('blog.cover') }}<input type="file" name="cover_image" accept="image/*"></label><label>{{ __('blog.cover_alt') }}<input name="cover_image_alt" value="{{ old('cover_image_alt', $post->cover_image_alt) }}" maxlength="255"></label></div></section>
            <div class="blog-editor-actions"><button class="gold-button" type="submit"><i data-lucide="save"></i>{{ __('blog.save') }}</button><a href="{{ route('dashboard.blog.index') }}">{{ __('blog.cancel') }}</a></div>
        </aside>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const title = document.querySelector('#blogTitle');
    const slug = document.querySelector('#blogSlug');
    let slugWasEdited = Boolean(slug.value);
    slug.addEventListener('input', () => slugWasEdited = slug.value.length > 0);
    title.addEventListener('input', () => {
        if (!slugWasEdited) slug.value = title.value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().replace(/[^\p{L}\p{N}]+/gu, '-').replace(/^-|-$/g, '');
    });
});
</script>
@endsection
