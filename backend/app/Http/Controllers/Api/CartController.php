<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $user = auth('sanctum')->user();

        $cart = $user->cart()->firstOrCreate([
            'user_id' => $user->id
        ]);

        $cart->load('items.product');

        return response()->json([
            'cart' => $cart
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = auth('sanctum')->user();
        $cart = $user->cart()->firstOrCreate(['user_id' => $user->id]);

        // Check if the product is already in the cart
        $cartItem = $cart->items()->where('product_id', $request->product_id)->first();

        if ($cartItem) {
            // If it exists, just add to the existing quantity
            $cartItem->increment('quantity', $request->quantity);
        } else {
            // Otherwise, create a new cart item
            $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json(['message' => 'Produit ajouté au panier avec succès.'], 200);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $userCartId = auth('sanctum')->user()->cart->id;

        $cartItem = CartItem::where('cart_id', $userCartId)
            ->where('product_id', $id)
            ->firstOrFail();

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Quantité mise à jour.'], 200);
    }

    public function destroy(string $id)
    {
        $userCartId = auth('sanctum')->user()->cart->id;

        $cartItem = CartItem::where('cart_id', $userCartId)
            ->where('product_id', $id)
            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'message' => 'The product has been removed from the cart'
        ], 200);
    }
}
