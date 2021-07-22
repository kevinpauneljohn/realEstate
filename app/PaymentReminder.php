<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentReminder extends Model
{
    protected $fillable = [
        'sales_id','completed','schedule','amount'
    ];
}
