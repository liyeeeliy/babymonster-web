<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user()->orders()->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View
    {
        abort_if($order->user_id !== $request->user()->id && !$request->user()->isAdmin(), 403);
        $order->load('items', 'payment');

        return view('orders.show', compact('order'));
    }

    /** Halaman invoice siap-cetak (browser print / save as PDF). */
    public function invoice(Request $request, Order $order): View
    {
        abort_if($order->user_id !== $request->user()->id && !$request->user()->isAdmin(), 403);
        abort_if($order->status === 'pending', 400, 'Invoice tersedia setelah pembayaran berhasil.');

        $order->load('items', 'payment', 'user');

        return view('orders.invoice', compact('order'));
    }
}
