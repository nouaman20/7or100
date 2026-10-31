<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Support\StorefrontData;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StorefrontShopController extends Controller
{
    public function __invoke(Request $request, StorefrontData $storefrontData): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:150'],
            'category' => ['nullable', 'string', 'max:180'],
            'min_price' => ['nullable', 'numeric', 'min:0', 'max:999999999'],
            'max_price' => ['nullable', 'numeric', 'gte:min_price', 'max:999999999'],
            'min_weight' => ['nullable', 'integer', 'min:1', 'max:4294967295'],
            'max_weight' => ['nullable', 'integer', 'gte:min_weight', 'max:4294967295'],
            'availability' => ['nullable', 'in:available,all'],
            'sort' => ['nullable', 'in:newest,price_asc,price_desc,name_asc'],
        ]);

        $filters = [
            'q' => trim($validated['q'] ?? ''),
            'category' => $validated['category'] ?? '',
            'min_price' => $validated['min_price'] ?? null,
            'max_price' => $validated['max_price'] ?? null,
            'min_weight' => $validated['min_weight'] ?? null,
            'max_weight' => $validated['max_weight'] ?? null,
            'availability' => $validated['availability'] ?? 'available',
            'sort' => $validated['sort'] ?? 'newest',
        ];

        $applyVariantFilters = static function ($query) use ($filters): void {
            $query->where('is_active', true)
                ->when($filters['availability'] === 'available', fn ($query) => $query->where(function ($stockQuery): void {
                    $stockQuery->whereNull('stock_quantity')->orWhere('stock_quantity', '>', 0);
                }))
                ->when($filters['min_price'] !== null, fn ($query) => $query->where('price', '>=', $filters['min_price']))
                ->when($filters['max_price'] !== null, fn ($query) => $query->where('price', '<=', $filters['max_price']))
                ->when($filters['min_weight'] !== null, fn ($query) => $query->where('weight_grams', '>=', $filters['min_weight']))
                ->when($filters['max_weight'] !== null, fn ($query) => $query->where('weight_grams', '<=', $filters['max_weight']));
        };

        $products = Product::query()
            ->where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true)
                ->when($filters['category'] !== '', fn ($categoryQuery) => $categoryQuery->where('slug', $filters['category'])))
            ->when($filters['q'] !== '', fn ($query) => $query->where(function ($productQuery) use ($filters): void {
                $productQuery->where('name', 'like', '%'.$filters['q'].'%')
                    ->orWhere('description', 'like', '%'.$filters['q'].'%');
            }))
            ->whereHas('product_variants', $applyVariantFilters)
            ->with([
                'category',
                'packItems',
                'product_variants' => function ($query) use ($applyVariantFilters): void {
                    $applyVariantFilters($query);
                    $query->orderBy('weight_grams');
                },
                'product_images' => fn ($query) => $query->orderByDesc('is_primary')->orderBy('sort_order'),
            ]);

        if (in_array($filters['sort'], ['price_asc', 'price_desc'], true)) {
            $priceQuery = ProductVariant::query()
                ->select('price')
                ->whereColumn('product_id', 'products.id');
            $applyVariantFilters($priceQuery);
            $priceQuery->orderBy('price')->limit(1);
            $products->orderBy($priceQuery, $filters['sort'] === 'price_asc' ? 'asc' : 'desc');
        } elseif ($filters['sort'] === 'name_asc') {
            $products->orderBy('name');
        } else {
            $products->latest();
        }

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        $availableVariants = ProductVariant::query()
            ->where('is_active', true)
            ->where(fn ($query) => $query->whereNull('stock_quantity')->orWhere('stock_quantity', '>', 0))
            ->whereHas('product', fn ($query) => $query->where('is_active', true)
                ->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('is_active', true)));

        $resultCount = (clone $products)->count();

        return view('storefront.shop.index', [
            'locale' => app()->getLocale(),
            'products' => $products->paginate(12)->withQueryString()
                ->through(fn (Product $product): array => $storefrontData->productCard($product)),
            'categories' => $categories,
            'filters' => $filters,
            'resultCount' => $resultCount,
            'priceRange' => [
                'min' => (float) (clone $availableVariants)->min('price'),
                'max' => (float) (clone $availableVariants)->max('price'),
            ],
        ]);
    }
}
