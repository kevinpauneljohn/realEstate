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
}
