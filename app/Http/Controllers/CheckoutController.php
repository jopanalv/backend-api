<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
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
        $checkouts = Checkout::all();
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
            'proof' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096'
        ]);

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
        //
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
}
