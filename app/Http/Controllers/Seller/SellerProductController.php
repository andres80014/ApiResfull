<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use App\User;
use App\Product;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;



class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;
        return $this->showAll($products);
    }
    
    public function store(Request $request, User $seller)
    { 
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' =>'required|image'
        ];
        $this->validate($request ,$rules);
        
        $data              = $request->all();
        $data['status']    = Product::PRODUCTO_NO_DISPONIBLE;
        $data['image']     = $request->image->store('');
        $data['seller_id'] = $seller->id;
        
        $product = Product::create($data);
        return $this->showOne($product , 201);
    }
    
    public function update(Request $request, Seller $seller, Product $product)
    {
         $rules = [
            'quantity' => 'integer|min:1',
            'status' => 'in:'.Product::PRODUCTO_NO_DISPONIBLE . ',' . Product::PRODUCTO_DISPONIBLE,
            'image' =>'image'
        ];
         
         
         $this->verificarVendedor($seller, $product);
         if($seller->id != $product->seller_id){
             $this->errorResponse("El vendeder no es el dueño del producto ",422);
         }
        $product->fill($request->only('name','description','quantity'));
        
        if($request->has('status')){
             $product->status = $request->status; 
             if($product->estaDisponible() && $product->categories()->count() ==0){
                 $this->errorResponse("un producto activo debe tener una categoria  ",409);
             }
         }
          
          if ($request->hasFile('image')) {
            Storage::delete($product->image);
            $product->image = $request->image->store('');
        }
         if($product->isClean()){
             $this->errorResponse("no se ha realizado ningun cambio  ",422);
         }
         $product->save();
         return $this->showOne($product);
         
    }
    
    public function destroy(Seller $seller, Product $product) 
    {
        $this->verificarVendedor($seller, $product);
        Storage::delete($product->image);
        $product->delete();
        return $this->showOne($product);
    }
    
    protected function verificarVendedor(Seller $seller, Product $product)
    {
        if($seller->id != $product->seller_id){
            throw new HttpException(422, 'El vendedor no es el dueño del producto ');
        }
    }
}
