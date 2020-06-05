<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AmountWithdrawalRequest extends Model
{
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
