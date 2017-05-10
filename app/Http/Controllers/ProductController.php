<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $product = product::all();
        return view('admin\product.list',compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.product.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    	$product = new product();
    	$product -> name = $request -> productName;
    	$product -> description = $request -> productDescription;
    	$product -> price = $request -> productPrice;
    	$product -> quantity = $request -> productQuantity;
    	$product -> save();
    	return redirect()-> route('product.index');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    	$data = product::FindOrFail($id);
    	return view('admin\product.edit', compact('data'));
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
        $product = product::findOrFail($id);
        $product -> name = $request -> productName;
        $product -> description = $request -> productDescription;
        $product -> price = $request -> productPrice;
        $product -> quantity = $request -> productQuantity;
        $product -> save();
        return redirect()-> route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    	$product = product::findOrFail($id);
    	$product -> delete();
    	
    	return redirect() -> route('product.index');
    }
}
