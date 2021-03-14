<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TaskChecklist extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'task_id','description','status'
    ];


    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
