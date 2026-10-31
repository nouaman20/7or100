<?php

namespace App\Providers;

use App\Support\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.store.cart-sidebar', function (ViewInstance $view): void {
            $items = app(CartService::class)->contents();

            $view->with('cartItems', $items)
                ->with('cartSubtotal', $items->sum('line_total'));
        });
    }
}
