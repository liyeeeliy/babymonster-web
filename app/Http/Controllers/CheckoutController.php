<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /** Tarif ongkir per kurir — dihitung DI SERVER, tidak pernah dipercaya dari klien. */
    public const SHIPPING_RATES = [
        'JNE' => 20000,
        'J&T' => 18000,
        'SiCepat' => 15000,
        'AnterAja' => 22000,
    ];

    /** Metode pembayaran simulasi (tanpa gateway asli). */
    public const PAYMENT_METHODS = [
        'bank_transfer' => 'Transfer Bank',
        'ewallet' => 'E-Wallet (OVO / DANA / GoPay)',
        'qris' => 'QRIS',
    ];

    public function __construct(private CartService $cartService) {}

    /** Halaman form checkout (alamat + kurir) + ringkasan keranjang. */
    public function index(Request $request): View
    {
        $items = $this->cartService->items($request->user()->id);
        abort_if($items->isEmpty(), 400, 'Keranjang kamu masih kosong.');

        $subtotal = $items->sum(fn ($c) => $c->purchasable->price * $c->quantity);

        return view('checkout.index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'couriers' => self::SHIPPING_RATES,
        ]);
    }

    /** Buat pesanan (status pending), kurangi stok, kosongkan keranjang. */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'address' => 'required|string|max:1000',
            'phone' => 'required|string|max:20',
            'courier' => 'required|in:' . implode(',', array_keys(self::SHIPPING_RATES)),
        ]);

        $items = $this->cartService->items($request->user()->id);
        abort_if($items->isEmpty(), 400, 'Keranjang kamu masih kosong.');

        // Validasi stok final sebelum membuat pesanan.
        foreach ($items as $item) {
            if ($item->purchasable->stock < $item->quantity) {
                return back()->with('error', "Stok \"{$item->purchasable->name}\" tidak mencukupi.");
            }
        }

        $order = DB::transaction(function () use ($items, $data, $request) {
            $subtotal = $items->sum(fn ($c) => $c->purchasable->price * $c->quantity);
            $shipping = self::SHIPPING_RATES[$data['courier']]; // sumber tepercaya: server

            $order = Order::create([
                'user_id' => $request->user()->id,
                'recipient_name' => $data['recipient_name'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'courier' => $data['courier'],
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'total' => $subtotal + $shipping,
                'status' => 'pending',
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'purchasable_type' => $item->purchasable_type,
                    'purchasable_id' => $item->purchasable_id,
                    'name' => $item->purchasable->name,
                    'price' => $item->purchasable->price,
                    'quantity' => $item->quantity,
                    'line_total' => $item->purchasable->price * $item->quantity,
                ]);

                $item->purchasable->decrement('stock', $item->quantity);
                $item->delete();
            }

            return $order;
        });

        return redirect()->route('checkout.pay', $order)
            ->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran.');
    }

    /** Halaman pembayaran simulasi. */
    public function pay(Request $request, Order $order): View
    {
        abort_if($order->user_id !== $request->user()->id, 403);
        abort_if($order->status !== 'pending', 400, 'Pesanan ini sudah tidak menunggu pembayaran.');

        return view('checkout.pay', [
            'order' => $order->load('items'),
            'methods' => self::PAYMENT_METHODS,
        ]);
    }

    /** Konfirmasi pembayaran (SIMULASI) — langsung menandai pesanan lunas. */
    public function confirm(Request $request, Order $order): RedirectResponse
    {
        abort_if($order->user_id !== $request->user()->id, 403);
        abort_if($order->status !== 'pending', 400, 'Pesanan ini sudah diproses.');

        $data = $request->validate([
            'payment_method' => 'required|in:' . implode(',', array_keys(self::PAYMENT_METHODS)),
        ]);

        DB::transaction(function () use ($order, $data) {
            $order->update([
                'status' => 'paid',
                'payment_method' => $data['payment_method'],
                'paid_at' => now(),
            ]);

            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'method' => $data['payment_method'],
                    'amount' => $order->total,
                    'status' => 'paid',
                    'reference' => 'SIM-' . strtoupper(Str::random(12)),
                    'paid_at' => now(),
                ]
            );
        });

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pembayaran berhasil (simulasi)! Pesananmu sedang diproses. 🎉');
    }
}
