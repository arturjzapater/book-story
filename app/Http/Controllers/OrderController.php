<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use App\Order;
use App\OrderProduct;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $order = Order::create($request->all());

        $products = Cart::find($request->cart)
            ->products()
            ->select('product_id')
            ->get()
            ->map(function($product) use ($order) {
                return [
                    'order_id' => $order->id,
                    'product_id' => $product->product_id,
                ];
            })
            ->toArray();

        OrderProduct::insert($products);
        $order->products;

        return response()
            ->json($order, 201)
            ->header('Location', "/api/orders/$order->id");
    }
}
