<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'mobileNo',
        'date_of_birth',
        'email',
        'username',
    ];
}
