<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('dashboard.products.index', [
            'locale' => app()->getLocale(),
            'products' => Product::query()
                ->with(['category', 'product_variants', 'product_images' => fn ($query) => $query->orderBy('sort_order')])
                ->when(request('search'), fn ($query, $search) => $query->where('name', 'like', '%'.$search.'%'))
                ->latest()
                ->paginate(12)
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('dashboard.products.form', [
            'locale' => app()->getLocale(),
            'product' => new Product(['is_active' => true]),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProduct($request);

        $product = DB::transaction(function () use ($request, $validated): Product {
            $product = Product::query()->create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'category_id' => $validated['category_id'],
                'is_active' => $request->boolean('is_active'),
                'is_pack' => $request->boolean('is_pack'),
                'is_featured' => $request->boolean('is_featured'),
            ]);

            $this->syncImages($request, $product);
            $this->syncVariants($validated['variants'] ?? [], $product);

            return $product;
        });

        return to_route('dashboard.products.edit', $product)->with('success', __('products.created'));
    }

    public function edit(Product $product): View
    {
        $product->load([
            'product_images' => fn ($query) => $query->orderBy('sort_order'),
            'product_variants' => fn ($query) => $query->orderBy('weight_grams'),
        ]);

        return view('dashboard.products.form', [
            'locale' => app()->getLocale(),
            'product' => $product,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validateProduct($request, $product);
        $removedImagePaths = [];

        DB::transaction(function () use ($request, $validated, $product, &$removedImagePaths): void {
            $product->update([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'category_id' => $validated['category_id'],
                'is_active' => $request->boolean('is_active'),
                'is_pack' => $request->boolean('is_pack'),
                'is_featured' => $request->boolean('is_featured'),
            ]);

            $removedImagePaths = $this->syncImages($request, $product);
            $this->syncVariants($validated['variants'] ?? [], $product, $request->input('remove_variants', []));
        });

        Storage::disk('public')->delete($removedImagePaths);

        return to_route('dashboard.products.edit', $product)->with('success', __('products.updated'));
    }

    public function destroy(Product $product): RedirectResponse
    {
        $imagePaths = $product->product_images()->pluck('image')->all();
        $product->delete();
        Storage::disk('public')->delete($imagePaths);

        return to_route('dashboard.products.index')->with('success', __('products.deleted'));
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('products', 'slug')->ignore($product?->id)],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'uuid', 'exists:categories,id'],
            'is_active' => ['nullable', 'boolean'],
            'is_pack' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['required', 'image', 'max:5120'],
            'image_alts' => ['nullable', 'array'],
            'image_alts.*' => ['nullable', 'string', 'max:255'],
            'image_orders' => ['nullable', 'array'],
            'image_orders.*' => ['required', 'integer', 'min:0', 'max:9999'],
            'primary_image' => ['nullable', 'string', 'max:100'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['uuid'],
            'variants' => ['nullable', 'array'],
            'variants.*.name' => ['required', 'string', 'max:255'],
            'variants.*.weight_grams' => ['required', 'integer', 'min:1', 'max:4294967295'],
            'variants.*.price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'variants.*.stock_quantity' => ['nullable', 'integer', 'min:0', 'max:4294967295'],
            'variants.*.is_active' => ['nullable', 'boolean'],
            'remove_variants' => ['nullable', 'array'],
            'remove_variants.*' => ['uuid'],
        ]);

        $variantKeys = array_keys($validated['variants'] ?? []);
        if ($product) {
            $existingIds = $product->product_variants()->pluck('id')->all();
            $removedVariantIds = $validated['remove_variants'] ?? [];

            if (array_diff($removedVariantIds, $existingIds) !== []) {
                throw ValidationException::withMessages(['variants' => __('products.invalid_variant')]);
            }

            foreach ($variantKeys as $key) {
                if (! Str::startsWith($key, 'new_') && ! in_array($key, $existingIds, true)) {
                    throw ValidationException::withMessages(['variants' => __('products.invalid_variant')]);
                }
            }

        }

        return $validated;
    }

    /** @return array<int, string> Paths that can be deleted after commit. */
    private function syncImages(Request $request, Product $product): array
    {
        $removedPaths = [];
        $removedIds = $request->input('remove_images', []);
        $existingImages = $product->product_images()->get();

        foreach ($existingImages as $image) {
            if (in_array($image->id, $removedIds, true)) {
                $removedPaths[] = $image->image;
                $image->delete();
            } else {
                $image->update([
                    'alt' => $request->input("image_alts.existing_{$image->id}", $image->alt),
                    'sort_order' => $request->input("image_orders.existing_{$image->id}", $image->sort_order),
                ]);
            }
        }

        $uploads = $request->file('images', []);
        $primaryChoice = $request->input('primary_image');
        $newImages = [];

        foreach ($uploads as $index => $upload) {
            $newImages[$index] = $product->product_images()->create([
                'image' => $upload->store('products', 'public'),
                'alt' => $request->input("image_alts.$index"),
                'sort_order' => $request->input("image_orders.$index", 0),
                'is_primary' => false,
            ]);
        }

        $remainingImages = $product->product_images()->get();
        if ($remainingImages->isEmpty()) {
            return $removedPaths;
        }

        $remainingImages->each(fn (ProductImage $image) => $image->update(['is_primary' => false]));

        if (is_string($primaryChoice) && Str::startsWith($primaryChoice, 'new:')) {
            $primary = $newImages[Str::after($primaryChoice, 'new:')] ?? null;
        } else {
            $primary = $remainingImages->firstWhere('id', $primaryChoice);
        }

        ($primary ?? $remainingImages->first())->update(['is_primary' => true]);

        return $removedPaths;
    }

    private function syncVariants(array $variants, Product $product, array $removedIds = []): void
    {
        if ($removedIds !== []) {
            $product->product_variants()->whereIn('id', $removedIds)->delete();
        }

        foreach ($variants as $key => $variantData) {
            $data = [
                'name' => $variantData['name'],
                'weight_grams' => $variantData['weight_grams'],
                'price' => $variantData['price'],
                'stock_quantity' => $variantData['stock_quantity'] ?? null,
                'is_active' => filter_var($variantData['is_active'] ?? false, FILTER_VALIDATE_BOOLEAN),
            ];

            if (Str::startsWith((string) $key, 'new_')) {
                $product->product_variants()->create($data);
            } else {
                $product->product_variants()->whereKey($key)->update($data);
            }
        }
    }
}
