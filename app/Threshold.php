<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Threshold extends Model
{
    use SoftDeletes;

    public function getDataAttribute($value)
    {
        return ($value != null) ? json_decode($value) : '';
    }

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }

    public function setUserIdAttribute()
    {
        $this->attributes['user_id'] = auth()->user()->id;
    }
}
