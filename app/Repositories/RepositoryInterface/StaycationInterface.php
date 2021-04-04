<?php


namespace App\Repositories\RepositoryInterface;


interface StaycationInterface
{
    /**
     * @param $start
     * @param $end
     * @return mixed
     */
    public function checkAvailability($start, $end);
}
