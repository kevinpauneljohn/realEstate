<?php


namespace App\Repositories;


use App\Lead;
use App\Repositories\RepositoryInterface\LeadInterface;

class Leads implements LeadInterface
{
    public function viewReservedUnits($lead_id)
    {
        return $this->viewById($lead_id)->sales;
    }

    public function viewById($lead_id)
    {
        return Lead::findOrFail($lead_id);
    }
}
