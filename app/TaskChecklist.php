<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TaskChecklist extends Model
{
    use LogsActivity;

    protected $fillable = [
        'task_id','description','status'
    ];

    protected static $logFillable = true;

    protected static $logAttributes  = [
        'task_id','description','status'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
