<?php


namespace App\Repositories;


use App\Lead;
use App\User;

class LeadRepository
{
    /**
     * @since May 04, 2020
     * @author john kevin paunel
     * @param string $leadId
     * @return object
     * */
    public function getLeadById($leadId)
    {
        return Lead::findOrFail($leadId);
    }

    /**
     * @since May 05, 2020
     * @author john kevin paunel
     * transform the user_id to user fullname and lead id to lead fullname
     * @param string $leadId
     * @return object
     * */
    public function getTransformedLeadById($leadId)
    {
        $collection = collect($this->getLeadById($leadId));

        $collection->transform(function ($item, $key) {

            if ($key == 'user_id')
            {
                $user = User::find($item)->fullname;
                $item = ucfirst($user);
            }elseif ($key == 'id'){
                $lead = Lead::find($item)->fullname;
                $item = ucfirst($lead);
            }
            return $item;
        });

        return $collection->all();
    }

    /**
     * @since May 05, 2020
     * @author john kevin paunel
     * update the lead status
     * @param string $id
     * @param string $status
     * @return array
     * */
    public function updateStatus($id, $status)
    {
        $lead = Lead::findOrFail($id);
        $lead->lead_status = $status;

        if($lead->idDirty())
        {
            $lead->save();
            return array("success" => true,"message" => "Lead Status Successfully Updated!");
        }
        return array("success" => false,"message" => "No Changes Occurred!");
    }
}
