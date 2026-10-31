<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DashboardBlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:100'], 'status' => ['nullable', Rule::in([BlogPost::STATUS_DRAFT, BlogPost::STATUS_PUBLISHED])]]);
        $posts = BlogPost::query()->with('author')->when($filters['q'] ?? null, fn ($query, $term) => $query->where(fn ($search) => $search->where('title', 'like', "%{$term}%")->orWhere('slug', 'like', "%{$term}%")))->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))->latest('updated_at')->paginate(12)->withQueryString();

        return view('dashboard.blog.index', compact('posts', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('dashboard.blog.form', ['post' => new BlogPost, 'editing' => false]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['user_id'] = $request->user()->id;
        $data['published_at'] = $data['status'] === BlogPost::STATUS_PUBLISHED ? now() : null;
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('blog', 'public');
        }

        BlogPost::create($data);

        return to_route('dashboard.blog.index')->with('status', __('blog.saved'));
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogPost $blogPost): RedirectResponse
    {
        return to_route('dashboard.blog.edit', $blogPost);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogPost $blogPost): View
    {
        return view('dashboard.blog.form', ['post' => $blogPost, 'editing' => true]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BlogPost $blogPost): RedirectResponse
    {
        $data = $this->validatedData($request, $blogPost);
        if ($data['status'] === BlogPost::STATUS_PUBLISHED && ! $blogPost->published_at) {
            $data['published_at'] = now();
        }
        if ($data['status'] === BlogPost::STATUS_DRAFT) {
            $data['published_at'] = null;
        }
        if ($request->hasFile('cover_image')) {
            $oldImage = $blogPost->cover_image;
            $data['cover_image'] = $request->file('cover_image')->store('blog', 'public');
            $blogPost->update($data);
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
        } else {
            $blogPost->update($data);
        }

        return to_route('dashboard.blog.index')->with('status', __('blog.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        if ($blogPost->cover_image) {
            Storage::disk('public')->delete($blogPost->cover_image);
        }
        $blogPost->delete();

        return to_route('dashboard.blog.index')->with('status', __('blog.deleted'));
    }

    /** @return array<string, mixed> */
    private function validatedData(Request $request, ?BlogPost $post = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash:unicode', Rule::unique('blog_posts', 'slug')->ignore($post?->id)],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:100000'],
            'cover_image' => ['nullable', 'image', 'max:5120'],
            'cover_image_alt' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'status' => ['required', Rule::in([BlogPost::STATUS_DRAFT, BlogPost::STATUS_PUBLISHED])],
            'is_featured' => ['nullable', 'boolean'],
        ]) + ['is_featured' => $request->boolean('is_featured')];
    }
}
