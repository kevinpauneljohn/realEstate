<?php


namespace App\Repositories;


use App\Repositories\RepositoryInterface\StaycationInterface;
use App\Staycation\StaycationAppointment;

class StaycationRepository implements StaycationInterface
{
    public function checkAvailability($start, $end)
    {
        return StaycationAppointment::whereBetween('check_in',[$start, $end])
            ->orWhereBetween('check_out',[$start, $end]);
    }
}
