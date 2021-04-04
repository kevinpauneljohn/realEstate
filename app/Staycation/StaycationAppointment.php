<?php

namespace App\Staycation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaycationAppointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'staycation_client_id','check_in','check_out','amount','pax'
    ];

    protected $casts = ['pax'];

    protected $dates = [
        'check_in','check_out'
    ];
}
