<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Variant;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantsRepository;
use Illuminate\Http\Request;
use App\Traits\CommonTrait;
use Illuminate\Support\Facades\File;
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
        $variants_data=$this->productVariantsRepository->productVariants()->groupBy("variant_id");
        $variants=[];
        foreach($variants_data as $key => $val){
            $vinfo=Variant::find($key);
            $ara_variants=[
                "id" => $key,
                "title" => $vinfo->title,
                "list" => $val,
            ];

            array_push($variants,$ara_variants);
        }
        //dd($variants); //perPage,
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

        //dd($request->all());

        $this->validate($request, [
            'product_name' => 'required',
            'product_sku' => 'required|unique:products,sku,' . $request->updateId,
         ]);

        $pid=$this->productRepository->createOrUpdate($request->all());
        $path=CommonTrait::getImageFileBasePath();
        if($request->hasFile('file') && !empty($pid)){
            $files=CommonTrait::uploadMultipleFiles($request->file,'product-images');
            foreach($files['file'] as $file){
                if(!empty($file)){
                    ProductImage::updateOrCreate(
                        ["id" => null],
                        ['product_id' => $pid,"file_path" => $file]
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
        $product=Product::with("getProductImages","getVariantsPrice.getVariantOne","getVariantsPrice.getVariantTwo","getVariantsPrice.getVariantThree")->find($product->id);
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
    public function handleFileDelete(Request $request){
        $image=ProductImage::find($request->id);
        $image_name=$image->getRawOriginal('file_path');
        $path="product-images/";
        if (File::exists(public_path($path."".$image_name))) {
            File::delete(public_path($path."".$image_name));
        }
        $image->delete();
        return response()->json(public_path($path."".$image_name));
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
