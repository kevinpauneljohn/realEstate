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

    /**
     * @since May 11, 2020
     * @author john kevin paunel
     * set the timeline icon label
     * @param string $action
     * @return string
     * */
    public function getTimelineIcon($action)
    {
        $icon = "";
        if($action === 'Phone Call')
        {
            $icon = '<i class="fas fa-phone-volume bg-blue"></i>';
        }
        elseif ($action === 'SMS')
        {
            $icon = '<i class="fas fa-sms bg-success"></i>';
        }elseif ($action === 'Email')
        {
            $icon = '<i class="fas fa-envelope bg-purple"></i>';
        }
        elseif ($action === 'Meeting')
        {
            $icon = '<i class="fas fa-handshake bg-yellow"></i>';
        }
        elseif ($action === 'Social Network')
        {
            $icon = '<i class="fas fa-users bg-maroon"></i>';
        }
        elseif ($action === 'Others')
        {
            $icon = '<i class="fas fa-exchange bg-red"></i>';
        }

        return $icon;
    }

    /**
     * @since May 11, 2020
     * @author john kevin paunel
     * set the timeline icon label
     * @param string $action
     * @return string
     * */
    public function getDateClassLabel($action)
    {
        $icon = "";
        if($action === 'Phone Call')
        {
            $icon = 'bg-blue';
        }
        elseif ($action === 'SMS')
        {
            $icon = 'bg-success';
        }elseif ($action === 'Email')
        {
            $icon = 'bg-purple';
        }
        elseif ($action === 'Meeting')
        {
            $icon = 'bg-yellow';
        }
        elseif ($action === 'Social Network')
        {
            $icon = 'bg-maroon';
        }
        elseif ($action === 'Others')
        {
            $icon = 'bg-red';
        }

        return $icon;
    }

}
