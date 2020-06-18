<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    public function users()
    {
        return $this->belongsToMany(User::class,'task_user');
    }

    public function childTasks()
    {
        return $this->hasMany(ChildTask::class);
    }
}
