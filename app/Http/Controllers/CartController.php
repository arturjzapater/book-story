<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use App\CartProduct;
use App\Product;

class CartController extends Controller
{
    private function addItemToCart($cart_id, $product_id)
    {
        $cart = Cart::find($cart_id);
        $product = Product::find($product_id);
        if (!$cart || !$product) {
            return[
                [ 'message' => 'Bad Request' ],
                400,
            ];
        }

        CartProduct::create([
            'cart_id' => $cart_id,
            'product_id' => $product_id,
        ]);

        return [ $this->getFullCart($cart), 200 ];
    }

    private function deleteItemFromCart($cart_id, $product_id)
    {
        CartProduct::where([
            [ 'cart_id', $cart_id ],
            [ 'product_id', $product_id ],
        ])->delete();

        $cart = Cart::find($cart_id);

        return [ $this->getFullCart($cart), 200 ];
    }

    private function getFullCart($cart)
    {
        $cart->products = $this->getProducts($cart);
        $cart->count = $cart->products()->count();

        return $cart;
    }

    private function getProducts($cart)
    {
        return $cart->products()
            ->select('products.id', 'title', 'author', 'cover', 'price')
            ->orderBy('title')
            ->get()
            ->makeHidden('pivot');
    }

    public function create()
    {
        $cart = Cart::create();
        $result = $this->getFullCart($cart);

        return response()
            ->json($result, 201)
            ->header('Location', "/api/carts/$cart->id");
    }

    public function readOne($id)
    {
        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json([ 'message' => 'Not Found' ], 404);
        }

        $result = $this->getFullCart($cart);
        return response()->json($result);   
    }

    public function update(Request $request, $id)
    {
        [ $res, $status ] = $request->get('action') === 'delete'
            ? $this->deleteItemFromCart($id, $request->product)
            : $this->addItemToCart($id, $request->product);

        return response()->json($res, $status);
    }

    public function delete($id)
    {
        Cart::destroy($id);

        return response()->json([ 'message' => 'Successfully deleted' ]);
    }

    public function getCount($id)
    {
        return response()->json([
            'count' => CartProduct::where('cart_id', $id)->count(),
        ]);
    }
}
