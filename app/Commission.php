<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Commission extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'id',
        'user_id',
        'commission_rate',
    ];

    protected static $logAttributes = [
        'id',
        'user_id',
        'commission_rate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
