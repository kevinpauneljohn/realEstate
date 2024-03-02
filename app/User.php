<?php

namespace App;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use Notifiable, UsesUuid, HasRoles, LogsActivity, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'mobileNo',
        'date_of_birth',
        'email',
        'username',
    ];

    protected static $logAttributes = [
        'firstname',
        'middlename',
        'lastname',
        'mobileNo',
        'date_of_birth',
        'email',
        'username',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function childTasks()
    {
        return $this->hasMany(ChildTask::class);
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class,'task_user');
    }

    public function userRankPoint()
    {
        return $this->hasOne(UserRankPoint::class);
    }

//    public function point()
//    {
//        return $this->hasOne(Point::class);
//    }

    public function promotion()
    {
        return $this->hasOne(Promotion::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function cashRequests()
    {
        return $this->hasMany(CashRequest::class);
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function adminlte_image()
    {
        //return 'https://picsum.photos/300/300';
        return asset('/images/avatar.png');
    }

    public function adminlte_desc()
    {
        return auth()->user()->username;
    }
    public function adminlte_profile_url()
    {
        return '/dashboard';
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function downlines()
    {
        return $this->hasMany(Downline::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }

    public function thresholds()
    {
        return $this->hasMany(Threshold::class);
    }

    public function getFirstnameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getMiddlenameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getLastnameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getFullNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function getUsernameAttribute($value)
    {
        return ucfirst($value);
    }

    public function documentations()
    {
        return $this->hasMany(Documentation::class);
    }

    #1 dream home guide client can have many projects availed
    public function clientProjects()
    {
        return $this->hasMany(ClientProjects::class,'user_id');
    }

    #this is another foreign key in the client_projects table which
    #determines who created the project in the application
    public function createdProjects()
    {
        return $this->hasMany(ClientProjects::class,'created_by');
    }

    #this is another foreign key in the client_projects table which
    #determines who is the agent of the project created
    public function projectAgent()
    {
        return $this->hasMany(ClientProjects::class,'agent_id');
    }


    #get all the projects related to an architect
    public function projectArchitect()
    {
        return $this->hasMany(ClientProjects::class,'architect_id');
    }

    public function builders()
    {
        return $this->belongsToMany(Builder::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function dhsClientLead(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(Lead::class, DhsClient::class);
    }

    public function lead(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lead::class,'online_warrior_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class,'id','assigned_to');
    }
    public function taskCreated()
    {
        return $this->belongsTo(Task::class,'created_by');
    }

    public function actions()
    {
        return $this->hasMany(ActionTaken::class);
    }

    public function testRemarks()
    {
        return $this->hasMany(TaskRemark::class);
    }

    public function commissionRequests()
    {
        return $this->hasMany(CommissionRequest::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function computations()
    {
        return $this->hasMany(Computation::class);
    }
}
