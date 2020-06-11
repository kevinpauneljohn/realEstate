<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rank extends Model
{
    use SoftDeletes;

    public function userRankPoints()
    {
        return $this->hasMany(UserRankPoint::class);
    }

}
