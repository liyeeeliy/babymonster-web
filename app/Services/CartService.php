<?php

namespace App\Services;

use App\Models\Album;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /** Tambah album ke keranjang; gabung quantity bila album yang sama sudah ada. */
    public function add(int $albumId, int $quantity = 1): Cart
    {
        $album = Album::findOrFail($albumId);

        if ($album->stock < $quantity) {
            throw new \RuntimeException('Stok album tidak mencukupi.');
        }

        $cart = Cart::where('user_id', Auth::id())
            ->where('purchasable_type', $album->getMorphClass())
            ->where('purchasable_id', $album->id)
            ->first();

        if ($cart) {
            $cart->update(['quantity' => min($cart->quantity + $quantity, $album->stock)]);
            return $cart->fresh();
        }

        return Cart::create([
            'user_id' => Auth::id(),
            'purchasable_type' => $album->getMorphClass(),
            'purchasable_id' => $album->id,
            'quantity' => $quantity,
        ]);
    }

    public function updateQuantity(Cart $cart, int $quantity): Cart
    {
        $quantity = max(1, $quantity);
        $cart->update(['quantity' => min($quantity, $cart->purchasable->stock)]);
        return $cart;
    }

    public function remove(Cart $cart): void
    {
        $cart->delete();
    }

    public function items(int $userId)
    {
        return Cart::with('purchasable')->where('user_id', $userId)->latest()->get();
    }

    public function subtotal(int $userId): int
    {
        return $this->items($userId)->sum(fn (Cart $c) => $c->purchasable->price * $c->quantity);
    }
}
