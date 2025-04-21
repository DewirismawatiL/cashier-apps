<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'product_id',
        'qty'
    ];


    public function product() {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
