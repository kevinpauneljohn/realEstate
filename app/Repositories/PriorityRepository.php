<?php


namespace App\Repositories;


use App\Priority;

class PriorityRepository
{
    /**
     * @since May 03, 2020
     * @author john kevin paunel
     * get the priority objects bu number of days
     * @param string $day
     * @return object
     * */
    public function getPriorityByNumberOfDay($day)
    {
        $priority = Priority::where('days',$day)->firstOrFail();
        return $priority;
    }

    /**
     * @since May 03, 2020
     * @author john kevin paunel
     * check the priority if exists
     * @param string $day
     * @return int
     * */
    public function countPriorityByDays($day)
    {
        $priority = Priority::where('days',$day);
        return $priority->count();
    }
}
