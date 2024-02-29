<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Wallet extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'user_id','amount','details','category','status'
    ];

    protected $logAttributes = ['user_id','amount','details','category','status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function AmountWithdrawalRequests()
    {
        return $this->hasMany(AmountWithdrawalRequest::class);
    }

    public function setDetailsAttribute($value)
    {
        $this->attributes['details'] = json_encode($value);
    }

    public function getDetailsAttribute($value)
    {
        return json_decode($value);
    }

    public function getCategoryAttribute($value)
    {
        return ucfirst($value);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
