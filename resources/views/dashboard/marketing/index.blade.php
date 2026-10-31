@extends('dashboard')

@section('content')
    <div class="page">
        <header class="topbar">
            <div class="title-group"><button class="mobile-menu" type="button" data-menu-toggle aria-label="Menu"><i data-lucide="menu"></i></button><div class="title-bee" aria-hidden="true">🐝</div><div><h1>{{ __('newsletter.dashboard_title') }}</h1><p>{{ __('newsletter.dashboard_subtitle') }}</p></div></div>
            <a class="gold-button" href="{{ route('dashboard.marketing.export') }}"><i data-lucide="download"></i>{{ __('newsletter.export') }}</a>
        </header>

        @if(session('success'))<p class="language-status" role="status">{{ session('success') }}</p>@endif

        <section class="marketing-metrics" aria-label="{{ __('newsletter.dashboard_title') }}">
            <article><span class="marketing-metric-icon amber"><i data-lucide="users-round"></i></span><div><small>{{ __('newsletter.total') }}</small><strong>{{ number_format($totalSubscribers) }}</strong></div></article>
            <article><span class="marketing-metric-icon green"><i data-lucide="mail-check"></i></span><div><small>{{ __('newsletter.active') }}</small><strong>{{ number_format($activeSubscribers) }}</strong></div></article>
            <article><span class="marketing-metric-icon gray"><i data-lucide="mail-x"></i></span><div><small>{{ __('newsletter.unsubscribed') }}</small><strong>{{ number_format($unsubscribedSubscribers) }}</strong></div></article>
        </section>

        <section class="orders-table-wrap marketing-list-panel">
            <form class="marketing-filters" method="GET" action="{{ route('dashboard.marketing.index') }}">
                <label><span class="sr-only">{{ __('newsletter.search') }}</span><input type="search" name="search" value="{{ $filters['search'] }}" placeholder="{{ __('newsletter.search') }}"></label>
                <label><span class="sr-only">{{ __('newsletter.status_filter') }}</span><select name="status"><option value="all" @selected($filters['status'] === 'all')>{{ __('newsletter.all_statuses') }}</option><option value="subscribed" @selected($filters['status'] === 'subscribed')>{{ __('newsletter.status_subscribed') }}</option><option value="unsubscribed" @selected($filters['status'] === 'unsubscribed')>{{ __('newsletter.status_unsubscribed') }}</option></select></label>
                <button class="order-status-form-button" type="submit"><i data-lucide="search"></i>{{ __('newsletter.apply_filters') }}</button>
            </form>

            <div class="overflow-x-auto">
                <table class="orders-table marketing-table">
                    <thead><tr><th>{{ __('newsletter.email') }}</th><th>{{ __('newsletter.status') }}</th><th>{{ __('newsletter.consented_at') }}</th><th>{{ __('newsletter.subscribed_at') }}</th><th>{{ __('newsletter.source') }}</th><th>{{ __('newsletter.actions') }}</th></tr></thead>
                    <tbody>
                        @forelse($subscribers as $subscriber)
                            <tr>
                                <td><strong>{{ $subscriber->email }}</strong></td>
                                <td><span class="subscriber-status {{ $subscriber->status }}">{{ __('newsletter.status_'.$subscriber->status) }}</span></td>
                                <td>{{ $subscriber->consented_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                <td>{{ $subscriber->subscribed_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                <td>{{ $subscriber->source === 'footer' ? __('newsletter.source_footer') : $subscriber->source }}</td>
                                <td><div class="subscriber-actions">
                                    @if($subscriber->status === 'subscribed')<form action="{{ route('dashboard.marketing.unsubscribe', $subscriber) }}" method="POST" onsubmit="return confirm(@js(__('newsletter.confirm_unsubscribe')))">@csrf @method('PATCH')<button class="subscriber-unsubscribe" type="submit">{{ __('newsletter.unsubscribe') }}</button></form>@endif
                                    <form action="{{ route('dashboard.marketing.destroy', $subscriber) }}" method="POST" onsubmit="return confirm(@js(__('newsletter.confirm_delete')))">@csrf @method('DELETE')<button class="subscriber-delete" type="submit">{{ __('newsletter.delete') }}</button></form>
                                </div></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="orders-empty">{{ __('newsletter.empty') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="orders-pagination">{{ $subscribers->links() }}</div>
        </section>
    </div>
@endsection
