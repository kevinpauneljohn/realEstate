<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
