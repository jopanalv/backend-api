<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Product::all();
        $response = [
            'message' => 'all product success',
            'data' => $data
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
        $validate = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string',
            // 'image' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'specification' => 'required|string',
            'function' => 'required|string',
            'utility' => 'required|string',
            'commodity' => 'required|string'
        ]);

        $image = $request->file('image');
        $image->storeAs('public/images/',$image->hashName());

        $product = Product::create([
            'name' => $validate['name'],
            'slug' => $validate['slug'],
            // 'image' => $validate['image'],
            'image' => $image->hashName(),
            'price' => $validate['price'],
            'stock' => $validate['stock'],
            'specification' => $validate['specification'],
            'function' => $validate['function'],
            'utility' => $validate['utility'],
            'commodity' => $validate['commodity']
            
        ]);

        $response = [
            'message' => 'Create product Successful',
            'data' => $product,
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
        Product::destroy($id);
        return response([
            'message' => 'Delete product success'
        ], 202);
    }
}
