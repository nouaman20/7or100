<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;

class StorefrontProductController extends Controller
{
    public function show(string $slug): View
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->whereHas('product_variants', function ($query): void {
                $query->where('is_active', true)
                    ->where(fn ($stockQuery) => $stockQuery->whereNull('stock_quantity')->orWhere('stock_quantity', '>', 0));
            })
            ->with([
                'category',
                'packItems',
                'product_images' => fn ($query) => $query->orderByDesc('is_primary')->orderBy('sort_order'),
                'product_variants' => function ($query): void {
                    $query->where('is_active', true)
                        ->where(fn ($stockQuery) => $stockQuery->whereNull('stock_quantity')->orWhere('stock_quantity', '>', 0))
                        ->orderBy('weight_grams');
                },
            ])
            ->firstOrFail();

        return view('storefront.products.show', [
            'locale' => app()->getLocale(),
            'product' => $product,
        ]);
    }
}
