<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Computation extends Model
{
    use SoftDeletes;
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
