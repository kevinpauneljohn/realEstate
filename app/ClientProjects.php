<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientProjects extends Model
{
    use SoftDeletes;


    #this will get the data of the client
    public function client()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    #this will get the data of the project creator
    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    #this will get the data of the project agent
    public function agent()
    {
        return $this->belongsTo(User::class,'agent_id');
    }

    //because our architect_id column can accept null values
    //we will set the value automatically to null if there is no submitted value on our client_projects table
    public function setArchitectIdAttribute($value)
    {
        $this->attributes['architect_id'] = $value ?: null;
    }

    //set the status initial letter to capital
    public function getStatusAttribute($value)
    {
        return ucfirst($value);
    }
}
