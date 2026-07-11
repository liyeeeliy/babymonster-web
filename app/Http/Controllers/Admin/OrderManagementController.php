<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderManagementController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::with('user')
            ->status($request->get('status'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load('items', 'payment', 'user');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|in:pending,paid,process,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $timestamps = [];
        if ($data['status'] === 'paid' && !$order->paid_at) $timestamps['paid_at'] = now();
        if ($data['status'] === 'shipped' && !$order->shipped_at) $timestamps['shipped_at'] = now();
        if ($data['status'] === 'delivered' && !$order->delivered_at) $timestamps['delivered_at'] = now();

        $order->update(array_merge($data, $timestamps));

        ActivityLog::record('order.status', "Ubah status pesanan {$order->order_number} → {$data['status']}", $order);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
