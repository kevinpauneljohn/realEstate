<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Task extends Model
{
    use SoftDeletes;
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

    public function checklists()
    {
        return $this->hasMany(TaskChecklist::class);
    }
}
