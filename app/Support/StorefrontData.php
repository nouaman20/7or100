<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class StorefrontData
{
    /** Build the storefront homepage sections and catalog from database records. */
    public function home(?string $search = null, ?string $categorySlug = null): array
    {
        $categoryArt = [
            'amlou' => 'amlou',
            'produits-naturels' => 'natural',
            'coffrets-cadeaux' => 'gift',
        ];
        $categoryIcons = [
            'amlou' => 'nut',
            'produits-naturels' => 'leaf',
            'coffrets-cadeaux' => 'gift',
        ];

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(fn (Category $category): array => [
                'slug' => $category->slug,
                'name' => $category->name,
                'description' => $category->description,
                'image' => $categoryArt[$category->slug] ?? 'miel',
                'image_url' => $category->image ? Storage::disk('public')->url($category->image) : null,
                'icon' => $categoryIcons[$category->slug] ?? 'flower-2',
            ]);

        $catalogQuery = static fn () => Product::query()
            ->where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->whereHas('product_variants', function ($query): void {
                $query->where('is_active', true)
                    ->where(fn ($stockQuery) => $stockQuery->whereNull('stock_quantity')->orWhere('stock_quantity', '>', 0));
            })
            ->with([
                'category',
                'product_variants' => fn ($query) => $query->where('is_active', true)
                    ->where(fn ($stockQuery) => $stockQuery->whereNull('stock_quantity')->orWhere('stock_quantity', '>', 0))
                    ->orderBy('weight_grams'),
                'product_images' => fn ($query) => $query->orderByDesc('is_primary')->orderBy('sort_order'),
                'packItems',
            ]);

        $products = $catalogQuery()
            ->when($categorySlug, fn ($query) => $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $categorySlug)))
            ->when($search, fn ($query) => $query->where(function ($productQuery) use ($search): void {
                $productQuery->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            }))
            ->latest()
            ->get()
            ->map(fn (Product $product): array => $this->productCard($product));

        $bestPacks = $catalogQuery()->where('is_pack', true)->where('is_featured', true)->latest()->limit(5)->get()
            ->map(fn (Product $product): array => $this->productCard($product));
        $featuredProducts = $catalogQuery()->where('is_pack', false)->where('is_featured', true)->latest()->limit(5)->get()
            ->map(fn (Product $product): array => $this->productCard($product));
        $newestProducts = $catalogQuery()->latest()->limit(5)->get()
            ->map(fn (Product $product): array => $this->productCard($product));

        return [
            'categories' => $categories->all(),
            'products' => $products->all(),
            'bestPacks' => $bestPacks,
            'featuredProducts' => $featuredProducts,
            'newestProducts' => $newestProducts,
            'search' => $search,
            'testimonials' => [
                ['initials' => 'FB', 'name' => 'Fatima B.', 'quote' => 'Le miel est d’une qualité exceptionnelle ! On sent vraiment la différence. Je recommande à 100%.'],
                ['initials' => 'ML', 'name' => 'Mohamed L.', 'quote' => 'L’amalou est délicieux, un vrai goût authentique et naturel. Livraison rapide et bien emballé.'],
                ['initials' => 'SK', 'name' => 'Sara K.', 'quote' => 'Produits naturels de très bonne qualité. On sent le soin et la passion derrière chaque pot.'],
            ],
        ];
    }

    /** @return array<string, mixed> */
    public function productCard(Product $product): array
    {
        $image = $product->product_images->first();
        $availableVariants = $product->product_variants->filter(fn ($variant): bool => $variant->stock_quantity === null || $variant->stock_quantity > 0);
        $artKey = match (true) {
            Str::contains($product->slug, 'eucalyptus') => 'eucalyptus',
            Str::contains($product->slug, 'amlou') => 'amandier',
            Str::contains($product->slug, 'thym') => 'thym',
            Str::contains($product->slug, 'argan') => 'argan',
            Str::contains($product->slug, 'montagne') => 'mountain',
            default => 'eucalyptus',
        };

        return [
            'id' => $product->id,
            'slug' => $product->slug,
            'name' => $product->name,
            'description' => $product->description,
            'category' => $product->category?->name,
            'is_pack' => $product->is_pack,
            'is_featured' => $product->is_featured,
            'pack_items' => $product->packItems->map(fn (Product $packItem): array => [
                'id' => $packItem->id,
                'name' => $packItem->name,
            ])->all(),
            'image' => $artKey,
            'image_url' => $image ? Storage::disk('public')->url($image->image) : null,
            'starting_price' => (float) ($availableVariants->min('price') ?? $product->product_variants->min('price')),
            'variants' => $product->product_variants->map(fn ($variant): array => [
                'id' => $variant->id,
                'name' => $variant->name,
                'price' => (float) $variant->price,
                'stock_quantity' => $variant->stock_quantity,
                'available' => $variant->stock_quantity === null || $variant->stock_quantity > 0,
            ])->all(),
        ];
    }
}
