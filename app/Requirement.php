<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Requirement extends Model
{
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_id',
        'description',
    ];

    protected static $logAttributes = [
        'template_id',
        'description',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function saleRequirements()
    {
        return $this->hasMany(SaleRequirement::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
