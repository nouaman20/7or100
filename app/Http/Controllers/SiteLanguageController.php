<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SiteLanguageController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'locale' => ['required', 'in:fr,ar'],
        ]);

        SiteSetting::query()->updateOrCreate(
            ['key' => 'site_locale'],
            ['value' => $validated['locale']],
        );

        app()->setLocale($validated['locale']);

        return to_route('dashboard.settings.index')->with('language_status', __('dashboard.language_updated'));
    }
}
