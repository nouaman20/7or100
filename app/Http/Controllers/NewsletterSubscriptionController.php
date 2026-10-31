<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterSubscriptionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('newsletter', [
            'newsletter_email' => ['required', 'email', 'max:255'],
            'newsletter_consent' => ['required', 'accepted'],
        ]);

        $email = Str::lower(trim($validated['newsletter_email']));
        $subscriber = NewsletterSubscriber::query()->firstOrNew(['email' => $email]);
        $subscriber->consented_at = now();
        $subscriber->source = 'footer';

        if (! $subscriber->exists || $subscriber->status !== NewsletterSubscriber::STATUS_SUBSCRIBED) {
            $subscriber->status = NewsletterSubscriber::STATUS_SUBSCRIBED;
            $subscriber->subscribed_at = now();
            $subscriber->unsubscribed_at = null;
        }

        $subscriber->save();

        return back()->with('newsletter_status', __('newsletter.subscription_success'));
    }
}
