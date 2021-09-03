<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommissionRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sales_id','user_id','commission','status','remarks','approved_rate','approval'
    ];

    protected $casts = [
        'approval' => 'array',
        'remarks' => 'array'
    ];

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
