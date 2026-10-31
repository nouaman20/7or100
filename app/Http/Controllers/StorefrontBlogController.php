<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorefrontBlogController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->trim()->limit(100)->toString();
        $posts = BlogPost::query()->where('status', BlogPost::STATUS_PUBLISHED)->where('published_at', '<=', now())->when($search, fn ($query) => $query->where(fn ($items) => $items->where('title', 'like', "%{$search}%")->orWhere('excerpt', 'like', "%{$search}%")))->orderByDesc('is_featured')->latest('published_at')->paginate(9)->withQueryString();
        $locale = app()->getLocale();

        return view('storefront.blog.index', [
            'posts' => $posts,
            'search' => $search,
            'locale' => $locale,
            'metaTitle' => __('blog.page_title').' · 7or',
            'metaDescription' => __('blog.page_description'),
            'canonicalUrl' => route('storefront.blog.index'),
            'jsonLd' => ['@context' => 'https://schema.org', '@type' => 'Blog', 'name' => __('blog.page_title').' · 7or', 'url' => route('storefront.blog.index'), 'inLanguage' => $locale],
        ]);
    }

    public function show(string $slug): View
    {
        $post = BlogPost::query()->with('author')->where('slug', $slug)->where('status', BlogPost::STATUS_PUBLISHED)->where('published_at', '<=', now())->firstOrFail();
        $relatedPosts = BlogPost::query()->where('status', BlogPost::STATUS_PUBLISHED)->where('published_at', '<=', now())->where('id', '!=', $post->id)->orderByDesc('is_featured')->latest('published_at')->limit(3)->get();
        $locale = app()->getLocale();
        $canonicalUrl = route('storefront.blog.show', $post->slug);
        $description = $post->meta_description ?: Str::limit(trim(strip_tags($post->excerpt ?: Str::markdown($post->body, ['html_input' => 'strip', 'allow_unsafe_links' => false]))), 160, '');
        $jsonLd = ['@context' => 'https://schema.org', '@type' => 'BlogPosting', 'headline' => $post->meta_title ?: $post->title, 'description' => $description, 'datePublished' => $post->published_at?->toIso8601String(), 'dateModified' => $post->updated_at->toIso8601String(), 'author' => ['@type' => 'Person', 'name' => $post->author?->name ?: '7or'], 'publisher' => ['@type' => 'Organization', 'name' => '7or'], 'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canonicalUrl], 'inLanguage' => $locale];
        if ($post->cover_image) {
            $jsonLd['image'] = [Storage::disk('public')->url($post->cover_image)];
        }

        return view('storefront.blog.show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'locale' => $locale,
            'metaTitle' => $post->meta_title ?: $post->title.' · 7or',
            'metaDescription' => $description,
            'canonicalUrl' => $canonicalUrl,
            'ogImage' => $post->cover_image ? Storage::disk('public')->url($post->cover_image) : null,
            'jsonLd' => $jsonLd,
        ]);
    }
}
