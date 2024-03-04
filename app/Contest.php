<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contest extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','description','active','ranks','date_working','extra_field'];

    protected $dates = ['date_working'];

    public function setExtraFieldAttribute($value): void
    {
        $this->attributes['extra_field'] = json_encode($value);
    }

    public function getExtraFieldAttribute($value)
    {
        return json_decode($value);
    }

    public function setRanksAttribute($value): void
    {
        $this->attributes['ranks'] = json_encode($value);
    }

    public function getRanksAttribute($value)
    {
        return json_decode($value);
    }
}
