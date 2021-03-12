<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Task extends Model
{

    protected $fillable = [
        'created_by','title','description','status','due_date','time','assigned_to','priority_id'
    ];

    public function user()
    {
        return $this->hasOne(User::class,'id','assigned_to');
    }

    public function creator()
    {
        return $this->hasOne(User::class,'id','created_by');
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }
}
