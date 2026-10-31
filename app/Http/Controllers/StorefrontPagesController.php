<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use App\Support\AdminMailRecipient;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class StorefrontPagesController extends Controller
{
    public function about(): View
    {
        return view('storefront.about', ['locale' => app()->getLocale()]);
    }

    public function contact(): View
    {
        return view('storefront.contact', ['locale' => app()->getLocale()]);
    }

    public function sendContact(Request $request, AdminMailRecipient $adminMailRecipient): RedirectResponse
    {
        $contact = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $adminEmail = $adminMailRecipient->resolve();

        if (! $adminEmail) {
            Log::warning('Contact form submitted without a valid admin email recipient.');

            return to_route('storefront.contact')->withInput()->with('contact_error', __('store.contact_send_error'));
        }

        try {
            Mail::to($adminEmail)->send(new ContactMessage($contact));
        } catch (Throwable $exception) {
            Log::error('Contact form email could not be sent.', ['exception' => $exception->getMessage()]);

            return to_route('storefront.contact')->withInput()->with('contact_error', __('store.contact_send_error'));
        }

        return to_route('storefront.contact')->with('contact_success', __('store.contact_success'));
    }
}
