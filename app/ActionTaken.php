<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ActionTaken extends Model
{
    use LogsActivity;

    protected $fillable = [
        'task_checklist_id','action','user_id'
    ];

    protected static $logFillable = true;

    protected static $logAttributes  = [
        'task_checklist_id','action','user_id'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }
}
