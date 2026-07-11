<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoOrderSeeder extends Seeder
{
    public function run(): void
    {
        $fan = User::where('username', 'monsterluv_id')->first();
        $albums = Album::take(2)->get();
        if (!$fan || $albums->count() < 2) {
            return;
        }

        // Pesanan 1 — sudah selesai (mengisi grafik penjualan & produk terlaris)
        $this->makeOrder($fan, [[$albums[0], 2], [$albums[1], 1]], 'delivered', now()->subDays(10), 'ewallet');

        // Pesanan 2 — menunggu pembayaran
        $this->makeOrder($fan, [[$albums[0], 1]], 'pending', now()->subDay(), null);
    }

    private function makeOrder(User $user, array $lines, string $status, $when, ?string $method): void
    {
        $subtotal = collect($lines)->sum(fn ($l) => $l[0]->price * $l[1]);
        $shipping = 20000;

        $order = Order::create([
            'user_id' => $user->id,
            'recipient_name' => $user->name,
            'address' => 'Jl. Monster Raya No. 7, Jakarta Selatan',
            'phone' => '08123456789',
            'courier' => 'JNE',
            'payment_method' => $method,
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping,
            'total' => $subtotal + $shipping,
            'status' => $status,
            'paid_at' => $status === 'pending' ? null : $when,
        ]);

        foreach ($lines as [$album, $qty]) {
            $order->items()->create([
                'purchasable_type' => $album->getMorphClass(),
                'purchasable_id' => $album->id,
                'name' => $album->name,
                'price' => $album->price,
                'quantity' => $qty,
                'line_total' => $album->price * $qty,
            ]);
        }

        if ($status !== 'pending') {
            Payment::create([
                'order_id' => $order->id,
                'method' => $method,
                'amount' => $order->total,
                'status' => 'paid',
                'reference' => 'SIM-DEMO' . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT),
                'paid_at' => $when,
            ]);
        }
    }
}
