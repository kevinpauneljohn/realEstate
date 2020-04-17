<?php


namespace App\Repositories;


use App\Action;

class ThresholdRepository
{
    /**
     * @since April 15, 2020
     * @author john kevin paunel
     * get the priority ID
     * @param string $name
     * @return object
     * */
    public function getThresholdPriority($name)
    {
        return Action::where('name',$name)->first()->priority_id;
    }
}
