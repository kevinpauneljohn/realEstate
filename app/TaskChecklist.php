<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TaskChecklist extends Model
{

    protected $fillable = [
        'task_id','description','status'
    ];


    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
