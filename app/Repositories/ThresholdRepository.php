<?php


namespace App\Repositories;


use App\Action;
use App\Threshold;

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

    public function saveThreshold($type, $reason, $data, $table, $status,$priority)
    {
        $threshold = new Threshold();
        $threshold->type = $type;
        $threshold->description = $reason;
        $threshold->data = $data;
        $threshold->table = $table;
        $threshold->status = $status;
        $threshold->priority_id = $priority;

        $threshold->save();
    }
}
