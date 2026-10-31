<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DashboardSettingsController extends Controller
{
    public function index(): View
    {
        return view('dashboard.settings.index', [
            'locale' => app()->getLocale(),
            'user' => request()->user(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $request->merge(['email' => Str::lower(trim((string) $request->input('email')))]);

        $validated = $request->validateWithBag('profile', [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password' => ['required', 'current_password'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        return to_route('dashboard.settings.index')->with('profile_status', __('settings.profile_updated'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $validated = $request->validateWithBag('password', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ]);

        $user->update(['password' => $validated['password']]);
        $request->session()->regenerate();

        return to_route('dashboard.settings.index')->with('password_status', __('settings.password_updated'));
    }
}
