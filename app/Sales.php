<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use SoftDeletes;

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function modelUnit()
    {
        return $this->belongsTo(ModelUnit::class);
    }
}
