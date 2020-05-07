<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogTouch extends Model
{
    protected $fillable = ['lead_id','medium','date','time','resolution',
        'description'];

    protected $dates = [
        'date'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
