<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductPruebaController extends Controller
{
     public function listado(Request $request)
    {
        $request->validate([
           'quantity' => 'required| min:2'
        ]);
        
        return response()->json("Valor quantity -> ".$request->quantity.' | '.$request->name);
    }
}
