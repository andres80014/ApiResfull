<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return  $this->showAll($categories,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datos  = $request->all();
        $rules = [
            'name'        => 'required',
            'description' => 'required|min:10|'
        ];

        $this->validate($request,$rules);

        $category = Category::create($datos);

        //return response()->json(['data' => $category],201);
        return $this->showOne($category,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    
    //public function show($id)
    public function show(Category $category){
        //$categoria  = Category::FindOrFail($id);
        return  $this->showOne($category, 200);
    }
 /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category 
     * @return \Illuminate\Http\Response
     */
    
    
    public function update(Request $request, $id)
    { 
        $category = Category::findOrFail($id);
        //$category->fill($request->intersect('name','description'));
        //cambia el metodo intersec por 
        $category->fill($request->only([
            'name',
            'description',
        ]));
         
                
        if($category->isClean()){
            return $this->errorResponse("No hay cambios", 422);
        }
        //$category->name = $request->name;
        //$category->description = $request->description;
        $category->save();
        return $this->showOne($category);
    }
    
     

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->showOne($category);

    }
}
