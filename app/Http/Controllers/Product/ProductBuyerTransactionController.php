<?php

namespace App\Http\Controllers\Product;

use App\Product;
use App\User;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
class ProductBuyerTransactionController extends ApiController
{
    public function store(Request $request, Product $product, User $buyer)
    {
        //$rules = ['quantity' => 'required | integer | min:1'];
        //$this->validate($request, $rules);
        $request->validate([
            'quantity' => 'required | integer | min:2',
        ]);
        
        
        if($product->seller_id == $buyer->id){
            return $this->errorResponse("El comprador debe ser difernete del comprador ");
        }
        
        if(!$buyer->esVerificado()){
            return $this->errorResponse("El comprador debe un usuario verificado! ",409);
        }
        
        if(!$product->seller->esVerificado()){
            return $this->errorResponse("El vendedor debe un usuarios verificado! ",409);
        }
        if(!$product->estaDisponible()){
            return $this->errorResponse("El producto no esta disponible! ",409);
        }
        
        if($product->quantity < $request->quantity ){
            return $this->errorResponse("La cantidad del producto no esta disponible! ",409);
        }
        
        return DB::transaction( function() use ($request, $product, $buyer){
            $product->quantity -= $request->quantity;
            $product->save();
            
            $transaction = Transaction::create([
                'quantity'   => $request->quantity,
                'buyer_id'   => $buyer->id,
                'product_id' => $product->id,
            ]);
            
            return $this->showOne($transaction,201);
            
        });
    }
}
