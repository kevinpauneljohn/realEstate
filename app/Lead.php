<?php

namespace App;

use App\Services\RandomCodeGenerator;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Lead extends Model
{
    use SoftDeletes, LogsActivity, UsesUuid;
    public $extraData;

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
        'point_of_contact','birthday','important','project',
        'remarks','lead_status','online_warrior_id'
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
        'lead_status','online_warrior_id'
    ];

    protected $dates = [
        'date_inquired'
    ];
    protected $casts = ['extra_attribute' => 'array'];

    protected static function booted()
    {
        static::saving(function($lead){
            $lead->extra_attribute = ["facebook_page_code" => 'dhs-'.RandomCodeGenerator::runRandomCode()];
        });
    }

    public function scopeOnlineWarrior($query)
    {
        return auth()->user()->hasRole("online warrior") ? $query->where('online_warrior_id',auth()->user()->id) : $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }

    public function LeadActivities()
    {
        return $this->hasMany(LeadActivity::class,'lead_id');
    }

    public function LogTouches()
    {
        return $this->hasMany(LogTouch::class);
    }

    public function leadNotes()
    {
        return $this->hasMany(LeadNote::class);
    }

    public function getFullNameAttribute()
    {
        $firstname = ucfirst($this->firstname);
        $middlename = ucfirst($this->middlename);
        $lastname = ucfirst($this->lastname);
        return "{$firstname} {$middlename} {$lastname}";
    }

    public function websiteLinks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WebsiteLink::class);
    }

    public function warrior(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class,'id','online_warrior_id');
    }


}
