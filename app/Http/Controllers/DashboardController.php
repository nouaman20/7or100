<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $locale = app()->getLocale();

        return view('dashboard.home', compact('locale'));
    }
}
