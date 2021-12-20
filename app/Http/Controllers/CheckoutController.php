<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Checkout;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $checkouts = DB::table('checkouts')
        ->join('carts', 'checkouts.cart_id', '=', 'carts.id')
        ->join('products', 'carts.product_id', '=', 'products.id')
        ->join('users', 'carts.user_id', '=', 'users.id')
        ->select('checkouts.id', 'products.image', 'users.fullname', 'products.name', 'carts.quantity', 'carts.duration', 'carts.total', 'checkouts.proof', 'checkouts.status', 'carts.created_at')
        ->get();
        
        $response= [
            "message" => "get all checkout success",
            "data" => $checkouts
        ];
        return response($response,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'cart_id' => 'required|integer',
            'status' => 'required|string',
            'proof' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096'
        ]);

        $cart = Cart::where('id', $request->cart_id)->first();
        $product = Product::where('id', $cart->product_id)->first();
        
        $product->update(['stock' => ($product->stock - 1)]);

        $image = $request->file('proof');
        $image->storeAs('public/images/',$image->hashName());

        $checkout = Checkout::create([
            'cart_id' => $validate['cart_id'],
            'proof' => $image->hashName(),
            'status' => $validate['status'],
        ]);

        $response = [
            'message' => 'Create checkout Successful',
            'data' => $checkout,
        ];

        return response($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $checkouts = DB::table('checkouts')
        ->join('carts', 'checkouts.cart_id', '=', 'carts.id')
        ->join('products', 'carts.product_id', '=', 'products.id')
        ->join('users', 'carts.user_id', '=', 'users.id')
        ->where('carts.user_id', $id)
        ->select('checkouts.id', 'products.image', 'users.fullname', 'products.name', 'carts.quantity', 'carts.duration', 'carts.total', 'checkouts.proof', 'checkouts.status', 'carts.created_at')
        ->first();
        // $checkouts = Checkout::all();
        $response= [
            "message" => "get checkout success",
            "data" => $checkouts
        ];
        return response($response,200);
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
        Checkout::destroy($id);
        return response([
            'message' => 'Delete checkout success'
        ], 202);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request, $id)
    {
        $checkout = Checkout::find($id)->first();
        $checkout->update(['status' => 'success']);

        return response([
            'message' => 'confirm successful',
            'data' => $checkout
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request, $id)
    {
        $checkout = Checkout::find($id)->first();
        $checkout->update(['status' => 'failed']);

        $cart = Cart::where('id', $checkout->cart_id)->first();
        $product = Product::where('id', $cart->product_id)->first();
        
        $product->update(['stock' => ($product->stock + 1)]);

        return response([
            'message' => 'failed successful',
            'data' => $checkout
        ],200);
    }

    public function getSuccess(){
        $checkouts = DB::table('checkouts')
        ->join('carts', 'checkouts.cart_id', '=', 'carts.id')
        ->join('products', 'carts.product_id', '=', 'products.id')
        ->join('users', 'carts.user_id', '=', 'users.id')
        ->where('checkouts.status', 'success')
        ->select('checkouts.id', 'products.image',  'users.fullname', 'products.name', 'carts.quantity', 'carts.duration', 'carts.total', 'checkouts.proof', 'checkouts.status', 'carts.created_at')
        ->get();

        $response= [
            "message" => "get success checkout successful",
            "data" => $checkouts
        ];
        return response($response,200);
    }

    public function getProcess(){
        $checkouts = DB::table('checkouts')
        ->join('carts', 'checkouts.cart_id', '=', 'carts.id')
        ->join('products', 'carts.product_id', '=', 'products.id')
        ->join('users', 'carts.user_id', '=', 'users.id')
        ->where('checkouts.status', 'process')
        ->select('checkouts.id', 'products.image', 'users.fullname', 'products.name', 'carts.quantity', 'carts.duration', 'carts.total', 'checkouts.proof', 'checkouts.status', 'carts.created_at')
        ->get();

        $response= [
            "message" => "get process checkout successful",
            "data" => $checkouts
        ];
        return response($response,200);
    }

    public function getFailed(){
        $checkouts = DB::table('checkouts')
        ->join('carts', 'checkouts.cart_id', '=', 'carts.id')
        ->join('products', 'carts.product_id', '=', 'products.id')
        ->join('users', 'carts.user_id', '=', 'users.id')
        ->where('checkouts.status', 'failed')
        ->select('checkouts.id', 'products.image', 'users.fullname', 'products.name', 'carts.quantity', 'carts.duration', 'carts.total', 'checkouts.proof', 'checkouts.status', 'carts.created_at')
        ->get();

        $response= [
            "message" => "get failed checkout successful",
            "data" => $checkouts
        ];
        return response($response,200);
    }

    public function getWaiting(){
        $checkouts = DB::table('checkouts')
        ->join('carts', 'checkouts.cart_id', '=', 'carts.id')
        ->join('products', 'carts.product_id', '=', 'products.id')
        ->join('users', 'carts.user_id', '=', 'users.id')
        ->where('checkouts.status', 'waiting')
        ->select('checkouts.id', 'products.image', 'users.fullname', 'products.name', 'carts.quantity', 'carts.duration', 'carts.total', 'checkouts.proof', 'checkouts.status', 'carts.created_at')
        ->get();

        $response= [
            "message" => "get waiting checkout successful",
            "data" => $checkouts
        ];
        return response($response,200);
    }
}
