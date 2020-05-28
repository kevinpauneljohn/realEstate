<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $fillable = [
        'user_id','data','viewed','type'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }

    public function getDataAttribute($value)
    {
        return ($value != null) ? json_decode($value) : '';
    }
}
