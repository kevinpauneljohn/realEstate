<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelUnit extends Model
{
    use SoftDeletes;

    public function sale()
    {
        return $this->hasOne(Sales::class);
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = json_encode($value);
    }
    public function getDescriptionAttribute($value)
    {
        return ($value != null) ? json_decode($value) : '';
    }
}
