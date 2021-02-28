<?php


namespace App\Repositories\RepositoryInterface;


interface LeadInterface
{
    /**
     * @param $lead_id
     * @return mixed
     */
    public function viewReservedUnits($lead_id);

    /**
     * @param $lead_id
     * @return mixed
     */
    public function viewById($lead_id);

}
