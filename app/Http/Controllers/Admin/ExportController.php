<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /** Ekspor daftar pesanan ke CSV (native, tanpa dependency eksternal). */
    public function ordersCsv(Request $request): StreamedResponse
    {
        $orders = Order::with('user')
            ->status($request->get('status'))
            ->latest()
            ->get();

        $filename = 'laporan-pesanan-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($orders) {
            $out = fopen('php://output', 'w');
            // BOM agar Excel membaca UTF-8 dengan benar.
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, ['No. Order', 'Pelanggan', 'Email', 'Total', 'Status', 'Metode Bayar', 'Tanggal']);

            foreach ($orders as $o) {
                fputcsv($out, [
                    $o->order_number,
                    $o->recipient_name,
                    $o->user?->email,
                    $o->total,
                    $o->status,
                    $o->payment_method,
                    $o->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
