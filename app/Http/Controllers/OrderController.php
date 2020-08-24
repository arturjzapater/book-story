<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use App\Order;
use App\OrderProduct;

class OrderController extends Controller
{
    private function addProducts($cart_id, $order_id) {
        $products = Cart::find($cart_id)
            ->products()
            ->select('product_id')
            ->get()
            ->map(function($product) use ($order_id) {
                return [
                    'order_id' => $order_id,
                    'product_id' => $product->product_id,
                ];
            })
            ->toArray();

        OrderProduct::insert($products);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'c_o' => 'nullable|max:255',
            'address' => 'required|max:255',
            'postal_code' => 'required|digits:5',
            'email' => 'required|email',
            'phone' => 'required|max:255',
        ]);

        $cart = Cart::find($request->cart);
        if (!$cart) {
            return response()->json([ 'message' => 'Cart does not exist' ], 400);
        }

        $order = Order::create($request->all());
        $this->addProducts($request->cart, $order->id);
        $cart->delete();
        
        $order->products;

        return response()
            ->json($order, 201)
            ->header('Location', "/api/orders/$order->id");
    }
}
