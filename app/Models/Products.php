<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Products extends Model
{
    protected $fillable = [
        'name',
        'price',
        'image',
        'stock'
    ];

    public function cart(){
        return $this->hasMany(Products::class, 'product_id');
    }

    public function details(){
        return $this->HasMany(detail_transact::class, 'product_id');
    }
}
