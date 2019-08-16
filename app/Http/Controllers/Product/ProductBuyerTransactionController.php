<?php

namespace App\Http\Controllers\Product;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductBuyerTransactionController extends Controller
{
    public function store(Request $request, Product $product, User $buyer)
    {
        //
    }
}
