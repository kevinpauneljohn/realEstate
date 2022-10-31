<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Attendance extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable =[
        'user_id','timein','breakout','breakin','timeout','created_at',
    ];
}
