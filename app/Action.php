<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Action extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','description','priority_id'];

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }
}
