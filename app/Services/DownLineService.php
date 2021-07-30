<?php


namespace App\Services;


use App\User;

class DownLineService
{
    private $users = array(), $key = 0;
    public function getDownLines($userId)
    {
        return User::whereIn('upline_id',(array)$userId);
    }

    public function extractDownLines($userId)
    {
        $upLineIds = [];//container of all up Line Ids
        if($this->getDownLines($userId)->count() > 0)
        {
            //this will loop until it detects all users with down lines   t 6yt
            foreach ($this->getDownLines($userId)->get() as $downLine){
                $this->users[$this->key] = $downLine;
                $upLineIds[$this->key] = $downLine->id;
                $this->key++;
            }
            $this->extractDownLines($upLineIds);
        }
        return collect($this->users);
    }
}