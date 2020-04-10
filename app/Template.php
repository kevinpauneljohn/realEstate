<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use SoftDeletes;

    public function requirements()
    {
        return $this->hasMany(Requirement::class);
    }

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }
}
