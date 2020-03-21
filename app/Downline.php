<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Downline extends Model
{
    use SoftDeletes;

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
