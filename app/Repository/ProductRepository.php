<?php
    namespace App\Repository;
    use App\Interfaces\CrudInterface;
    use App\Models\Product;
    use App\Models\ProductVariant;
    use App\Models\ProductVariantPrice;
    use App\Traits\CommonTrait;
use Illuminate\Support\Facades\DB;

    class ProductRepository implements CrudInterface{
        public function createOrUpdate(array $data=[]){
            $product_variant=$data['product_variant'];
            $count_variant=1;
            $product_preview=$data['product_preview'] ?? [];
            try{
                DB::beginTransaction();
                $product=[
                    "title" => $data['product_name'],
                    "sku" => $data['product_sku'],
                    "description" => $data['product_description'],
                ];
                $product_id=Product::updateOrCreate(
                    ["id" => $data['updateId'] ?? null],
                    $product
                )->id;

                if(count($product_preview) > 0){
                    foreach($product_variant as $key => $val)
                    {
                        if(!empty($val['value']) && count($val['value']) > 0)
                            $count_variant=$key + 1;
                    }
                    $inc_product_preview=0;
                    foreach($product_variant[0]['value'] as $val_step){
                        $product_variant_one=null;$product_variant_two=null;$product_variant_three=null;
                        $data_variant=[
                            "variant" => $val_step,
                            "variant_id" => $product_variant[0]['option'],
                            "product_id" => $product_id
                        ];
                        $product_variant_one=$this->insertVariant($data_variant);
                        $price_variant=[
                            'product_variant_one' => $product_variant_one,
                            'product_variant_two' => null,
                            'product_variant_three' => null,
                            'product_id' => $product_id,
                        ];
                        if($count_variant == 1)
                            {
                                $price_variant['price']=$product_preview[$inc_product_preview]['price'];
                                $price_variant['stock']=$product_preview[$inc_product_preview]['stock'];
                                if(!empty($data['updateId']))
                                    $price_variant['id']=$product_preview[$inc_product_preview]['variant'];

                                $this->insertVariantPrice($price_variant);
                                $inc_product_preview++;

                            }
                        if(!empty($product_variant[1]['value']) && count($product_variant[1]['value']) > 0){
                            foreach($product_variant[1]['value'] as $val_step2){
                                $data_variant=[
                                    "variant" => $val_step2,
                                    "variant_id" => $product_variant[1]['option'],
                                    "product_id" => $product_id
                                ];
                                $price_variant['product_variant_two']=$this->insertVariant($data_variant);
                                if($count_variant == 2)
                                    {
                                        $price_variant['price']=$product_preview[$inc_product_preview]['price'];
                                        $price_variant['stock']=$product_preview[$inc_product_preview]['stock'];
                                        if(!empty($data['updateId']))
                                            $price_variant['id']=$product_preview[$inc_product_preview]['variant'];
                                        $this->insertVariantPrice($price_variant);
                                        $inc_product_preview++;
                                    }

                                    if(!empty($product_variant[2]['value']) && count($product_variant[2]['value']) > 0){
                                        foreach($product_variant[2]['value'] as $val_step3){
                                            $data_variant=[
                                                "variant" => $val_step3,
                                                "variant_id" => $product_variant[2]['option'],
                                                "product_id" => $product_id
                                            ];
                                            $price_variant['product_variant_three']=$this->insertVariant($data_variant);
                                            if($count_variant == 3)
                                                {
                                                    $price_variant['price']=$product_preview[$inc_product_preview]['price'];
                                                    $price_variant['stock']=$product_preview[$inc_product_preview]['stock'];
                                                    if(!empty($data['updateId']))
                                                        $price_variant['id']=$product_preview[$inc_product_preview]['variant'];

                                                    $this->insertVariantPrice($price_variant);
                                                    $inc_product_preview++;
                                                }
                                        }
                                    }
                            }
                        }
                    }
                }
                DB::commit();
                return $product_id;
            }
            catch(\Throwable $ex){
                dd($ex);
                DB::rollback();
                return null;
            }
            return $product_id;
        }
        public function insertVariantPrice($data){
            //echo json_encode($data)."<br>";
            //return 1;
            return ProductVariantPrice::updateOrCreate(["id" => $data["id"] ?? null],$data)->id;
        }
        public function insertVariant($data){
            //return 1;
            return ProductVariant::updateOrCreate($data,$data)->id;
        }
        public function find($id){

        }
        public function list(){
            $request=request();
            $list=Product::with(["getProductImages","getVariantsPrice" => function($q) use($request){
                if(!empty($request->price_from))
                    $q->where("price",">=",$request->price_from);
                if(!empty($request->price_to))
                    $q->where("price","<=",$request->price_to);

                $q->whereHas("getVariantOne")->orWhereHas("getVariantTwo")->orWhereHas("getVariantThree");
                $q->with([
                        "getVariantOne" => function($qq) use($request){
                            if(!empty($request->variant))
                                $qq->where("product_variants.id",$request->variant);
                            },
                        "getVariantTwo" => function($qq) use($request){
                            if(!empty($request->variant))
                                $qq->where("product_variants.id",$request->variant);
                            },
                        "getVariantThree" => function($qq) use($request){
                            if(!empty($request->variant))
                                $qq->where("product_variants.id",$request->variant);
                            }
                    ]);
                }])
            ->whereHas("variants",function($q) use($request){
                if(!empty($request->variant))
                    $q->where("product_variants.id",$request->variant);
            })
            ->where(function($q) use($request){
                if(!empty($request->title))
                    $q->where("title",$request->title);
                if(!empty($request->date))
                    {
                        $q->whereBetween("created_at",[CommonTrait::convDateToDateTime($request->date),
                                CommonTrait::convDateToDateTime($request->date,'23:59:59')])
                            ->orWhereBetween("updated_at",[CommonTrait::convDateToDateTime($request->date),
                                CommonTrait::convDateToDateTime($request->date,'23:59:59')]);
                    }
            })
            ->whereHas("getVariantsPrice",function($q) use($request){
                if(!empty($request->price_from))
                    $q->where("price",">=",$request->price_from);
                if(!empty($request->price_to))
                    $q->where("price","<=",$request->price_to);
                //$q->where("price",">",0);
            })
            ->paginate(5)
            ->appends(request()
            ->query());
            return $list;
        }
    }
?>
