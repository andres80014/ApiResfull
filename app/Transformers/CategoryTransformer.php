<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Category;
class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'identificador' => (int)$category->id,
            'titulo' => (string)$category->name,
            'detalles' => (int)$category->description,
            'fechaCreacion'  => (string)$category->created_at,      
            'fechaActualizacion' => (string)$category->update_at,      
            'fechaEliminacion' => isset($category->deleted_at) ? (string) $category->deleted_at : null,    
            
            //HATEOAS
            'links'=>[
                'rel' => 'self',
                'href'=> route('categories.show',$category->id),
            ],
            [
                'rel' =>'category.buyers',
                'href'=>route('categories.buyers.index',$category->id),
            ],
            [
                'rel' =>'category.products',
                'href'=>route('categories.products.index',$category->id),
            ],
            [
                'rel' =>'category.sellers',
                'href'=>route('categories.sellers.index',$category->id),
            ],
            [
                'rel' =>'category.transactions',
                'href'=>route('categories.transactions.index',$category->id),
            ]
            
        ];
    }
}
