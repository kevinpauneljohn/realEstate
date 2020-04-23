<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Threshold extends Model
{
    use SoftDeletes;

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDataAttribute($value)
    {
        return ($value != null) ? json_decode($value) : '';
    }

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }


    public function getRequestAttribute()
    {
        return ucfirst("{$this->type} {$this->table}");
    }

    public function getDescriptionAttribute($value)
    {
        return ucfirst($value);
    }

    public function getTypeAttribute($value)
    {
        return ucfirst($value);
    }
}
