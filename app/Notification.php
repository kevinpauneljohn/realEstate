<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }
}
