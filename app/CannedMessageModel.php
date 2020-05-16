<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class CannedMessageModel extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'user_id','title','body','status'
    ];

    protected $logsAttribute = ['user_id','title','body','status'];

}
