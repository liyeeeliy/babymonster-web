<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Member;
use App\Models\News;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const PAID_STATUSES = ['paid', 'process', 'shipped', 'delivered'];

    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_sales' => Order::whereIn('status', self::PAID_STATUSES)->sum('total'),
            'total_orders' => Order::count(),
            'total_albums' => Album::count(),
            'total_news' => News::count(),
            'total_members' => Member::count(),
        ];

        // Grafik penjualan 12 bulan terakhir — di-group di PHP agar portabel (SQLite/MySQL).
        $since = now()->subMonths(11)->startOfMonth();
        $orders = Order::whereIn('status', self::PAID_STATUSES)
            ->where('created_at', '>=', $since)
            ->get(['total', 'created_at']);

        $monthlySales = collect(range(0, 11))->map(function ($i) use ($orders) {
            $month = now()->subMonths(11 - $i)->format('Y-m');
            return (object) [
                'month' => $month,
                'total' => $orders->filter(fn ($o) => $o->created_at->format('Y-m') === $month)->sum('total'),
            ];
        });

        // Produk terlaris (dari snapshot order_items).
        $bestSellers = DB::table('order_items')
            ->select('name', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // User aktif: punya order dalam 30 hari terakhir.
        $activeUsers = User::whereHas('orders', fn ($q) => $q->where('created_at', '>=', now()->subDays(30)))->count();

        return view('admin.dashboard', compact('stats', 'monthlySales', 'bestSellers', 'activeUsers'));
    }
}
