<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallet_details';
    protected $hidden = ['created_at','updated_at','credit','debit'];

    public function transaction()
    {
        return $this->belongsTo('App\Models\TransactionModel','user_id');
    }
}