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

    protected $appends = ['request_number'];

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRequestNumberAttribute()
    {
        return str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}
