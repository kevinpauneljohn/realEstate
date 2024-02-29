<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = [
        'name','guard_name'
    ];

    protected static $logAttributes = [
        'name','guard_name'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
