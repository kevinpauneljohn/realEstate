<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Project extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'id',
        'name',
        'address',
        'remarks',
        'commission_rate',
        'shortcode'
    ];

    protected static $logAttributes = [
        'id',
        'name',
        'address',
        'remarks',
        'commission_rate'
    ];

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function sale()
    {
        return $this->hasOne(Sales::class);
    }

    public function computations()
    {
        return $this->hasMany(Computation::class);
    }
}
