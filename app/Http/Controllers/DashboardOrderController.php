<?php

namespace App\Http\Controllers;

use App\Enums\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DashboardOrderController extends Controller
{
    public function index(): View
    {
        return view('dashboard.orders.index', [
            'locale' => app()->getLocale(),
            'orders' => Order::query()->with('items')->latest()->paginate(15),
            'statuses' => OrderStatus::cases(),
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(OrderStatus::class)],
        ]);

        $order->update(['status' => $validated['status']]);

        return to_route('dashboard.orders.index')->with('success', __('orders.status_updated'));
    }
}
