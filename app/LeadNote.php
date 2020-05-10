<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class LeadNote extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
        // your other new column
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function getUpdatedAtAttribute($value)
    {
        $date = Carbon::parse($value);
        return $date->diffForHumans();
    }
    public function getCreatedAtAttribute($value)
    {
        $date = Carbon::parse($value);
        return $date->diffForHumans();
    }

}
