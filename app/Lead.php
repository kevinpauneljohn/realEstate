<?php

namespace App;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Lead extends Model
{
    use SoftDeletes, LogsActivity, UsesUuid;

    protected $fillable = [
        'user_id',
        'date_inquired',
        'firstname',
        'middlename',
        'lastname',
        'address',
        'mobileNo',
        'email',
        'status',
        'income_range',
        'point_of_contact',
        'remarks','lead_status'
    ];

    protected static $logAttributes = [
        'user_id',
        'date_inquired',
        'firstname',
        'middlename',
        'lastname',
        'address',
        'mobileNo',
        'email',
        'status',
        'income_range',
        'point_of_contact',
        'remarks',
        'lead_status'
    ];

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }

    public function LeadActivities()
    {
        return $this->hasMany(LeadActivity::class);
    }

    public function LogTouches()
    {
        return $this->hasMany(LogTouch::class);
    }

    public function getFullNameAttribute()
    {
        $firstname = ucfirst($this->firstname);
        $middlename = ucfirst($this->middlename);
        $lastname = ucfirst($this->lastname);
        return "{$firstname} {$middlename} {$lastname}";
    }
}
