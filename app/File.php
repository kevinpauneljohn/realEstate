<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'extension',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
