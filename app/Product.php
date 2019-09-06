<?php

namespace App;
use App\Transformers\ProductTransformer;
use App\Seller;
use App\Category;
use App\Transaction;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    const PRODUCTO_DISPONIBLE = 'disponible';
    const PRODUCTO_NO_DISPONIBLE = 'no disponible';
    public $transformer = ProductTransformer::class;
    
    protected $primaryKey =  'id'; 

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id'
    ];

    protected $hidden = ['pivot'];
    
    public function estaDisponible(){
        return $this->status == Product::PRODUCTO_DISPONIBLE;
    }

    public function categories(){
        
        return $this->belongsToMany(Category::class);
    }
    
    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}
