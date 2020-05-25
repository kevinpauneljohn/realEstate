<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function sale()
    {
        return $this->hasOne(Sales::class);
    }

    public function computations()
    {
        return $this->hasMany(Computation::class);
    }
}
