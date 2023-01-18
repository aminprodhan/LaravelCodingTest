<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Variant;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantsRepository;
use Illuminate\Http\Request;
use App\Traits\CommonTrait;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    private ProductRepository $productRepository;
    private ProductVariantsRepository $productVariantsRepository;
    public function __construct(ProductRepository $productRepository
        ,ProductVariantsRepository $productVariantsRepository)
    {
        $this->productRepository=$productRepository;
        $this->productVariantsRepository=$productVariantsRepository;
    }
    public function index()
    {
        $products=$this->productRepository->list();
        $variants=$this->productVariantsRepository->list();
        //dd($products->toArray()); //perPage,
        return view('products.index',compact('products','variants'));
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

        //dd($request->file->getClientOriginalExtension());
        $pid=$this->productRepository->createOrUpdate($request->all());
        $files=CommonTrait::uploadMultipleFiles($request->file,'product-images');
        $path=CommonTrait::getImageFileBasePath();
        if(!empty($pid)){
            foreach($files['file'] as $file){
                if(!empty($file)){
                    ProductImage::updateOrCreate(
                        ["id" => null],
                        ['product_id' => $pid,"file_path" => $path."".$file]
                    );
                }
            }
        }
        return response()->json(['message' => "success","pid" => $pid]);
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
        $variants = Variant::all();
        //dd($product->variants());
        //$product=$this->productRepository->find($product)
        $product=Product::with("getVariantsPrice.getVariantOne","getVariantsPrice.getVariantTwo","getVariantsPrice.getVariantThree")->find($product->id);
        return view('products.edit', compact('variants','product'));
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
        //
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
