<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finding extends Model
{
    use HasFactory;

    protected $fillable = ['commission_request_id','description','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
