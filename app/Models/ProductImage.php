<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CommonTrait;
class ProductImage extends Model
{
    //
    protected $guarded=[];
    public function getFilePathAttribute($value)
    {
        return CommonTrait::getImageFileBasePath()."".$value;
    }
}
