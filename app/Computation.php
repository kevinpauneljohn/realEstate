<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Computation extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'project_id',
        'model_unit_id',
        'location_type',
        'financing',
        'computation',
    ];

    protected static $logAttributes = [
        'project_id',
        'model_unit_id',
        'location_type',
        'financing',
        'computation',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
