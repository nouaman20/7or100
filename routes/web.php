<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardBlogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardMarketingController;
use App\Http\Controllers\DashboardOrderController;
use App\Http\Controllers\DashboardSettingsController;
use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SiteLanguageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StorefrontBlogController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\StorefrontPagesController;
use App\Http\Controllers\StorefrontProductController;
use App\Http\Controllers\StorefrontShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', StorefrontController::class)
    ->name('storefront.home');
Route::get('/boutique', StorefrontShopController::class)
    ->name('storefront.shop');
Route::get('/blog', [StorefrontBlogController::class, 'index'])->name('storefront.blog.index');
Route::get('/blog/{slug}', [StorefrontBlogController::class, 'show'])->name('storefront.blog.show');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', fn () => response("User-agent: *\nAllow: /\nSitemap: ".route('sitemap')."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']))->name('robots');
Route::get('/products/{slug}', [StorefrontProductController::class, 'show'])
    ->name('storefront.products.show');
Route::get('/a-propos', [StorefrontPagesController::class, 'about'])->name('storefront.about');
Route::get('/contact', [StorefrontPagesController::class, 'contact'])->name('storefront.contact');
Route::post('/contact', [StorefrontPagesController::class, 'sendContact'])
    ->middleware('throttle:5,1')
    ->name('storefront.contact.send');
Route::post('/newsletter/subscribe', [NewsletterSubscriptionController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('newsletter.subscribe');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/items', [CartController::class, 'store'])->name('cart.items.store');
Route::patch('/cart/items/{variantId}', [CartController::class, 'update'])->name('cart.items.update');
Route::delete('/cart/items/{variantId}', [CartController::class, 'destroy'])->name('cart.items.destroy');
Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/order-confirmation/{orderNumber}', [CheckoutController::class, 'success'])
    ->middleware('signed')
    ->name('checkout.success');

Route::get('/dashboard', DashboardController::class)
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {

        Route::resource('products', ProductController::class)->except(['show']);
        Route::resource('blog', DashboardBlogController::class)->except(['show'])->parameters(['blog' => 'blogPost']);

        Route::resource('categories', CategoryController::class)
            ->except(['create', 'show', 'edit']);

        Route::get('orders', [DashboardOrderController::class, 'index'])->name('orders.index');
        Route::patch('orders/{order}/status', [DashboardOrderController::class, 'updateStatus'])->name('orders.status.update');
        Route::get('marketing/subscribers/export', [DashboardMarketingController::class, 'export'])->name('marketing.export');
        Route::get('marketing', [DashboardMarketingController::class, 'index'])->name('marketing.index');
        Route::patch('marketing/subscribers/{subscriber}/unsubscribe', [DashboardMarketingController::class, 'unsubscribe'])->name('marketing.unsubscribe');
        Route::delete('marketing/subscribers/{subscriber}', [DashboardMarketingController::class, 'destroy'])->name('marketing.destroy');

        Route::get('settings', [DashboardSettingsController::class, 'index'])->name('settings.index');
        Route::put('settings/profile', [DashboardSettingsController::class, 'updateProfile'])->name('settings.profile.update');
        Route::put('settings/password', [DashboardSettingsController::class, 'updatePassword'])->name('settings.password.update');
        Route::put('settings/language', SiteLanguageController::class)->name('settings.language.update');

    });

Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
