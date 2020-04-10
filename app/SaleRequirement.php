<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleRequirement extends Model
{
    use SoftDeletes;

    public function requirement()
    {
        return $this->belongsTo(Requirement::class);
    }
}
