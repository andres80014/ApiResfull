<?php

namespace App;

use App\Transformers\TransactionTransformer;
use App\Buyer;
use App\Product;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use SoftDeletes;
    
    public $transformer = TransactionTransformer::class;
    protected $table = 'transactions';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id'
    ];

    public function buyer(){
        return $this->BelongsTo(Buyer::class);
    }

    public function product(){
        return $this->BelongsTo(Product::class);
    }

}
