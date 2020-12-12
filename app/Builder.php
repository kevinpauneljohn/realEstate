<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Builder extends Model
{
    use SoftDeletes;

    //set the builder name capital for every initial of the word
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }
}
