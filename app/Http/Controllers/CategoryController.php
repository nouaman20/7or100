<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->with('products')
            ->when(request('search'), fn ($query, $search) => $query->where('name', 'like', '%'.$search.'%'))
            ->when(request()->filled('status'), fn ($query) => $query->where('is_active', request('status') === '1'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.categories', [
            'locale' => app()->getLocale(),
            'categories' => $categories,
            'totalCategories' => Category::query()->count(),
            'activeCategories' => Category::query()->where('is_active', true)->count(),
            'inactiveCategories' => Category::query()->where('is_active', false)->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:180', 'alpha_dash', 'unique:categories,slug'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:3072'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::query()->create($validated);

        return to_route('dashboard.categories.index')->with('success', __('categories.created'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:180', 'alpha_dash', Rule::unique('categories', 'slug')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:3072'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($validated);

        return to_route('dashboard.categories.index')->with('success', __('categories.updated'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return to_route('dashboard.categories.index')
                ->withErrors(['category' => __('categories.delete_has_products')]);
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return to_route('dashboard.categories.index')->with('success', __('categories.deleted'));
    }
}
