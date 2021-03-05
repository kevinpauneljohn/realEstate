<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class LeadActivity extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'id',
        'user_id',
        'lead_id',
        'schedule',
        'start_date',
        'end_date',
        'parent',
        'category',
        'status',
    ];

    protected static $logAttributes = [
        'id',
        'user_id',
        'lead_id',
        'schedule',
        'start_date',
        'end_date',
        'parent',
        'category',
        'status',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at','schedule'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

}
