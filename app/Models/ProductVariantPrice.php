<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantPrice extends Model
{
    protected $guarded=[];
    public function getVariantOne(){
        return $this->belongsTo(ProductVariant::class,"product_variant_one");
    }
    public function getVariantTwo(){
        return $this->belongsTo(ProductVariant::class,"product_variant_two");
    }
    public function getVariantThree(){
        return $this->belongsTo(ProductVariant::class,"product_variant_three");
    }
}
