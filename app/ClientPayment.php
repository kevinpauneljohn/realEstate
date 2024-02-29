<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ClientPayment extends Model
{
    use SoftDeletes, LogsActivity;

    protected $dates = [
        'date_received'
    ];

    protected $fillable = [
        'id','client_project_id','date_received','amount','details','remarks'
    ];

    protected static $logAttributes = [
        'id','client_project_id','date_received','amount','details','remarks'
    ];

    public function clientProject()
    {
        return $this->belongsTo(ClientProjects::class,'client_project_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
