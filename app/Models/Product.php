<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'sku', 'description'
    ];
    public function variantTransaction(){
        return $this->hasMany(ProductVariant::class,"product_id");
    }
    public function variants()
    {
        return $this->belongsToMany(Variant::class,"product_variants");
    }
    public function getCreatedAtAttribute($value)
    {
        return date('d-M-Y',strtotime($value));
    }
    public function getVariantsPrice(){
        return $this->hasMany(ProductVariantPrice::class,"product_id");
    }
}
