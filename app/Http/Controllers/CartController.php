<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function index(Request $request): View
    {
        $items = $this->cartService->items($request->user()->id);
        $subtotal = $items->sum(fn ($c) => $c->purchasable->price * $c->quantity);

        return view('cart.index', compact('items', 'subtotal'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'album_id' => 'required|integer|exists:albums,id',
            'quantity' => 'nullable|integer|min:1|max:20',
        ]);

        try {
            $this->cartService->add($data['album_id'], $data['quantity'] ?? 1);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Album ditambahkan ke keranjang. 🛒');
    }

    public function update(Request $request, Cart $cart): RedirectResponse
    {
        $this->authorizeCart($cart, $request);

        $data = $request->validate(['quantity' => 'required|integer|min:1|max:20']);
        $this->cartService->updateQuantity($cart, $data['quantity']);

        return back()->with('success', 'Jumlah diperbarui.');
    }

    public function destroy(Request $request, Cart $cart): RedirectResponse
    {
        $this->authorizeCart($cart, $request);
        $this->cartService->remove($cart);

        return back()->with('success', 'Album dihapus dari keranjang.');
    }

    private function authorizeCart(Cart $cart, Request $request): void
    {
        abort_if($cart->user_id !== $request->user()->id, 403);
    }
}
