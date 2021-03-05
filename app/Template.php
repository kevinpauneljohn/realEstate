<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Template extends Model
{
    use SoftDeletes, LogsActivity;

    public $fillable = ['id','name','type'];

    protected static $logAttributes = ['id','name','type'];

    public function requirements()
    {
        return $this->hasMany(Requirement::class);
    }

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientRequirements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientRequirement::class);
    }

}
