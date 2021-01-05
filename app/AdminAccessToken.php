<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminAccessToken extends Model
{
    protected $fillable = [
        'access_token','expires_in','description','key'
    ];

}
