<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
//        dd($request->date);
//        if (!$request->date) {
//            $date = Carbon::createFromFormat('Y-m-d', $request->date);
//        }


        $data['productVariants']=ProductVariant::get();
        if($request->title && $request->variant && $request->price_from && $request->price_to){
            $productIds = Product::where('title', 'like', '%'.$request->title.'%')->pluck('id')->toArray();
            $productIds = ProductVariant::whereIn('product_id', $productIds)->where('variant', $request->variant)->pluck('product_id')->toArray();
            $productIds = ProductVariantPrice::whereIn('product_id', $productIds)->whereBetween('price', [$request->price_from, $request->price_to])->pluck('product_id')->toArray();
            $data['products'] = Product::whereIn('id', $productIds)->whereDate('created_at', date('Y-m-d', strtotime($request->date)))->get();

        }
        else{
            $data['products']=Product::with('product_variant','product_variant_price')->get();
        }



//        $return = [
//            'status' => true,
//            'messages' => 'Data successfully pull from database',
//            'data' => $data,
//        ];
//
//        return response()->json($return, 200);

        return view('products.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data['productStore']=new Product();
        $data['productStore']->title= $request->title;
        $data['productStore']->sku= $request->sku;
        $data['productStore']->description= $request->description;
        $data['productStore']->save();


//        dd($request->product_variant);

        foreach ($request->product_variant as $variant_data){


            $data['productStore']=new ProductVariant();
            $data['productStore']->variant= $variant_data->variant;
            $data['productStore']->variant_id= $variant_data->variant_id;
            $data['productStore']->product_id= 1;
            $data['productStore']->save();
        }


        $return = [
            'status' => true,
            'messages' => 'Data successfully pull from database',
            'data' => $data,

        ];
        return response()->json($return, 200);

    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $data['variants'] = Variant::all();
        $data['productEdit']=Product::find($product->id);
        return view('products.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $data['productUpdate']=Product::find($request->id);
        $data['productUpdate']->title= $request->title;
        $data['productUpdate']->sku= $request->sku;
        $data['productUpdate']->description= $request->description;
        $data['productUpdate']->save();
        $return = [
            'status' => true,
            'messages' => 'Data successfully pull from database',
            'data' => $data,

        ];
        return response()->json($return, 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
