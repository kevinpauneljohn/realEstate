<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebsiteLink extends Model
{
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
