<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carts = DB::table('carts')
        ->join('users', 'carts.user_id', '=', 'users.id')
        ->join('products', 'carts.product_id', '=', 'products.id')
        ->select('carts.id', 'users.fullname', 'products.name', 'carts.quantity', 'carts.duration', 'carts.total', 'carts.created_at')
        ->get();

        $response = [
            'message' => 'all cart success',
            'data' => $carts
        ];
        return response($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = Product::where('id', $request->product_id)->first();
        $price = $product->price;
        $total = $price * $request->quantity * $request->duration;

        $cart = Cart::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'duration' => $request->duration,
            'total' => $total
        ]);

        $response = [
            'message' => 'add to cart Successful',
            'data' => $cart,
        ];

        return response($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $carts = DB::table('carts')
        ->join('users', 'carts.user_id', '=', 'users.id')
        ->join('products', 'carts.product_id', '=', 'products.id')
        ->where('user_id', $user_id)
        ->select('users.fullname', 'products.name', 'carts.quantity', 'carts.duration', 'carts.total')
        ->get();

        $response = [
            'message' => 'cart success',
            'data' => $carts
        ];
        return response($response, 200);        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cart::destroy($id);
        return response([
            'message' => 'Delete cart success'
        ], 202);
    }
}
