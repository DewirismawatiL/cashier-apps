<?php

namespace App\Models;

use App\Models\Members;
use Illuminate\Database\Eloquent\Model;

class Selling extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $table = 'sellings';
    protected $fillable = [
        'kembalian',
        'total_pay',
        'member_id',
        'total_price',
        'user_id',
    ];

    public function member(){
        return $this->belongsTo(Members::class, 'member_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
{
    return $this->hasMany(detail_transact::class, 'transaction_id');
}

}
