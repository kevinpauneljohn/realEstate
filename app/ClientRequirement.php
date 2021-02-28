<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientRequirement extends Model
{
    use SoftDeletes;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function templates(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sales(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Sales::class);
    }
}
