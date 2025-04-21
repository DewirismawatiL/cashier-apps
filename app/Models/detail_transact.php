<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detail_transact extends Model
{
    protected $table = 'detail_transacts';
    protected $fillable = [
        'transaction_id',
        'product_id',
        'qty'
    ];


    public function transaction()
    {
        return $this->belongsTo(Selling::class, 'transaction_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class,'product_id');
    }

}
