<?php

namespace App\Http\Controllers;

use App\Support\StorefrontData;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function __invoke(Request $request, StorefrontData $storefrontData): View
    {
        $locale = app()->getLocale();

        return view('storefront.home', [
            'locale' => $locale,
            ...$storefrontData->home(
                $request->string('q')->trim()->toString() ?: null,
                $request->string('category')->trim()->toString() ?: null,
            ),
        ]);
    }
}
