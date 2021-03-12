<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Priority extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','description','days','color','created_at','updated_at'
    ];

    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    public function thresholds()
    {
        return $this->hasMany(Threshold::class);
    }

    public function task()
    {
        return $this->hasOne(Task::class);
    }
}
