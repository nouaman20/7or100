<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardMarketingController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['all', NewsletterSubscriber::STATUS_SUBSCRIBED, NewsletterSubscriber::STATUS_UNSUBSCRIBED])],
        ]);

        $subscribers = NewsletterSubscriber::query()
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where('email', 'like', '%'.$search.'%'))
            ->when(($filters['status'] ?? 'all') !== 'all', fn ($query) => $query->where('status', $filters['status']))
            ->latest('subscribed_at')
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.marketing.index', [
            'locale' => app()->getLocale(),
            'subscribers' => $subscribers,
            'filters' => ['search' => $filters['search'] ?? '', 'status' => $filters['status'] ?? 'all'],
            'totalSubscribers' => NewsletterSubscriber::query()->count(),
            'activeSubscribers' => NewsletterSubscriber::query()->where('status', NewsletterSubscriber::STATUS_SUBSCRIBED)->count(),
            'unsubscribedSubscribers' => NewsletterSubscriber::query()->where('status', NewsletterSubscriber::STATUS_UNSUBSCRIBED)->count(),
        ]);
    }

    public function unsubscribe(NewsletterSubscriber $subscriber): RedirectResponse
    {
        if ($subscriber->status !== NewsletterSubscriber::STATUS_UNSUBSCRIBED) {
            $subscriber->update([
                'status' => NewsletterSubscriber::STATUS_UNSUBSCRIBED,
                'unsubscribed_at' => now(),
            ]);
        }

        return to_route('dashboard.marketing.index')->with('success', __('newsletter.unsubscribed_by_admin'));
    }

    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return to_route('dashboard.marketing.index')->with('success', __('newsletter.deleted'));
    }

    public function export(): StreamedResponse
    {
        $headers = [__('newsletter.email'), __('newsletter.status'), __('newsletter.consented_at'), __('newsletter.subscribed_at'), __('newsletter.source')];

        return response()->streamDownload(function () use ($headers): void {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, $headers);

            NewsletterSubscriber::query()
                ->where('status', NewsletterSubscriber::STATUS_SUBSCRIBED)
                ->orderBy('email')
                ->cursor()
                ->each(fn (NewsletterSubscriber $subscriber) => fputcsv($file, [
                    $subscriber->email,
                    $subscriber->status,
                    $subscriber->consented_at?->toIso8601String(),
                    $subscriber->subscribed_at?->toIso8601String(),
                    $subscriber->source,
                ]));

            fclose($file);
        }, '7or-newsletter-subscribers.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
