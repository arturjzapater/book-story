<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;

class CartController extends Controller
{
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
        return $cart
            ? response()->json($cart)
            : response()->json([ 'message' => 'Not Found' ], 404);
    }
}
