<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        return response()->view('storefront.sitemap', [
            'posts' => BlogPost::query()->where('status', BlogPost::STATUS_PUBLISHED)->where('published_at', '<=', now())->latest('published_at')->get(['slug', 'updated_at']),
            'products' => Product::query()->where('is_active', true)->latest('updated_at')->get(['slug', 'updated_at']),
        ], 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
