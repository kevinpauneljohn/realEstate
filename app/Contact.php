<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contact extends Model
{
    use SoftDeletes, LogsActivity;
    protected $fillable = [
        'id',
        'user_id',
        'title',
        'contact_person',
        'contact_details',
    ];

    protected static $logAttributes = [
        'id',
        'user_id',
        'title',
        'contact_person',
        'contact_details',
    ];
}
