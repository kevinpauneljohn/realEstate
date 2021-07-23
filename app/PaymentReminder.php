<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentReminder extends Model
{
    protected $fillable = [
        'sales_id','completed','schedule','amount'
    ];

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }
}
