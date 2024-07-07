<?php


namespace App\Services;


use App\User;

class DownLineService
{
    private int $key = 0;
    private array $users = array();

    public function getDownLines($userId)
    {
        return User::whereIn('upline_id',(array)$userId);
    }

    public function extractDownLines($userId): \Illuminate\Support\Collection
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
