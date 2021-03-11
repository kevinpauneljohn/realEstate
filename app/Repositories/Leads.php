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

    public function assignLeadsToWarrior($lead_id, $warrior_id)
    {
        $lead = Lead::find($lead_id);
        $lead->online_warrior_id = !empty($warrior_id) ? $warrior_id : null;
        return $lead->save();
    }

}
