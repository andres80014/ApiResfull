<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Product;
class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'identificador' => (int)$product->id,
            'titulo' => (string)$product->name,
            'detalles' => (string)$product->description,
            'disponible' => (int)$product->quantity,
            'estado' => (int)$product->status,
            //'imagen' => url("img/"{$product->image}),    
            'vendedor' => (int)$product->seller_id,
            'fechaCreacion'  => (string)$product->created_at,      
            'fechaActualizacion' => (string)$product->update_at,      
            'fechaEliminacion' => isset($product->deleted_at) ? (string) $product->deleted_at : null, 
            
            //HATEOAS
            'links'=>[
                'rel' => 'self',
                'href'=> route('products.show',$product->id),
            ],
            [
                'rel' =>'product.Buyer',
                'href'=>route('products.Buyer.index',$product->id),
            ],
            [
                'rel' =>'product.categories',
                'href'=>route('products.categories.index',$product->id),
            ],
            [
                'rel' =>'product.transactions',
                'href'=>route('products.transactions.index',$product->id),
            ],
            
            [
                'rel' =>'seller',
                'href'=>route('sellers.show',$product->seller_id),
            ],
        ];
    }
}

    