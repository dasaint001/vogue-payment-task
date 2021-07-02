<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionModel extends Model
{
    protected $table = "transaction";
    protected $hidden = [
        'updated_at'
    ];

    public function getCreatedAtAttribute($value)
    {
        return date('M d, Y',strtotime($value));
    }

    public function history(){
        return $this->hasMany('App\Models\TransactionHistory', 'user_id');
    }

}