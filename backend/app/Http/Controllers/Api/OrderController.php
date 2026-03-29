<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.product'])->get();
        return response()->json(['orders' => $orders], 200);
    }
    public function PlaceOrder(string $id)
    {
        $cart_item = DB::table('carts_items')->where('id', $id)->get();
        Order::create([
            'carts_items_id' => $cart_item->id,
            'status' => 'in progress'
        ]);
    }
}
