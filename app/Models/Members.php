<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Members extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
        'poin_member'
    ];


    public function selling()
    {
        return $this->hasMany(Members::class, 'member_id');
    }

}

