<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use App\CartProduct;

class CartController extends Controller
{
    private function addItemToCart($cart_id, $product_id)
    {
        try {
            CartProduct::create([
                'cart_id' => $cart_id,
                'product_id' => $product_id,
            ]);

            return [ $this->getFullCart($cart_id), 200 ];
        } catch(\Exception $e) {
            return [
                [ 'message' => 'Bad Request' ],
                400,
            ];
        }
    }

    private function deleteItemFromCart($cart_id, $product_id)
    {
        CartProduct::where([
            [ 'cart_id', $cart_id ],
            [ 'product_id', $product_id ],
        ])->delete();

        return [ $this->getFullCart($cart_id), 200 ];
    }

    private function getFullCart($id)
    {
        $cart = Cart::find($id);
        $cart->products;

        return $cart;
    }

    public function create()
    {
        $cart = Cart::create();

        return response()
            ->json($cart, 201)
            ->header('Location', "/api/carts/$cart->id");
    }

    public function readOne($id)
    {
        $cart = Cart::find($id);
        if ($cart) {
            $cart->products;
            return response()->json($cart);
        }

        return response()->json([ 'message' => 'Not Found' ], 404);
    }

    public function update(Request $request, $id)
    {
        [ $res, $status ] = $request->get('action') === 'delete'
            ? $this->deleteItemFromCart($id, $request->product)
            : $this->addItemToCart($id, $request->product);

        return response()->json($res, $status);
    }
}
