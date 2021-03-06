<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRankPoint extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id','rank_id','sales_points','extra_points'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    public function userRankPoint()
    {
        return $this->belongsTo(UserRankPoint::class);
    }
}
