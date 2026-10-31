<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View
    {
        $locale = app()->getLocale();

        return view('auth.login', compact('locale'));
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => __('login.invalid_credentials'),
            ]);
        }

        // Prevent session fixation after successful authentication.
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Log out the authenticated user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        // Invalidate the old session and rotate the CSRF token.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('status', __('login.logged_out'));
    }
}
