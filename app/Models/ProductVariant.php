<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $guarded=[];
    public function getVariant(){
        return $this->belongsTo(Variant::class,"variant_id");
    }
    public function getVariantPrice(){
        return $this->hasOne(ProductVariantPrice::class,"id");
    }
}
