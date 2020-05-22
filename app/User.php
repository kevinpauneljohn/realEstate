<?php

namespace App;

use App\Traits\UsesUuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, UsesUuid, HasRoles, LogsActivity, SoftDeletes;

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

    public function adminlte_image()
    {
        //return 'https://picsum.photos/300/300';
        return asset('/images/avatar.png');
    }

    public function adminlte_desc()
    {
        return auth()->user()->username;
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
}
