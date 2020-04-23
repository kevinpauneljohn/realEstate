<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Priority extends Model
{
    use SoftDeletes;

    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    public function thresholds()
    {
        return $this->hasMany(Threshold::class);
    }
}
