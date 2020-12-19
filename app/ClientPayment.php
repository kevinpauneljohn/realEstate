<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientPayment extends Model
{
    use SoftDeletes;

//    protected $dates = [
//        'date_received'
//    ];

    public function clientProject()
    {
        return $this->belongsTo(ClientProjects::class,'client_project_id');
    }
}
