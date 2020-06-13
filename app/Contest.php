<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contest extends Model
{
    use SoftDeletes;

    protected $dates = ['date_working'];

    public function setExtraFieldAttribute($value)
    {
        $this->attributes['extra_field'] = json_encode($value);
    }

    public function getExtraFieldAttribute($value)
    {
        return json_decode($value);
    }
}
