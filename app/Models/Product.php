<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Seller;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;
    const PRODUCT_AVAILABLE = 'disponible';
    const PRODUCT_NOT_AVAILABLE = 'no disponible';

    protected $fillable =[
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',
    ];
    protected $dates = ['deleted_at'];

    public function isStateEnable(){
        return $this->status == Product::PRODUCT_AVAILABLE;

    }

    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function transactions(){
        $this->hasMany(Transaction::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

}
