<?php

namespace App\Models;

use App\Scope\SellerScope;
use App\Models\Product;

class Seller extends User
{
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new SellerScope);
    }
    public function products(){
        return $this->hasMany(Product::class);
    }
}
