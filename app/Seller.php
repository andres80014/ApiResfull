<?php

namespace App;

use App\Transformers\SellerTransformer;
use App\Product;
use Illuminate\Database\Eloquent\Model;

class Seller extends User
{
    
    public $transformer = SellerTransformer::class;
    
    public function products(){
        return $this->hasMany(Product::class);
    }
}
