<?php


namespace App\Repositories;


use App\Lead;
use App\User;

class LeadRepository
{

    /**
     * @since May 04, 2020
     * @author john kevin paunel
     * @param $leadId
     * @return mixed
     */
    public function getLeadById($leadId)
    {
        return Lead::findOrFail($leadId);
    }


    /**
     * @since May 05, 2020
     * @author john kevin paunel
     * transform the user_id to user fullname and lead id to lead fullname
     * @param $leadId
     * @return array
     */
    public function getTransformedLeadById($leadId): array
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
     * @param $id
     * @param $status
     * @return array
     */
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
            $icon = '<i class="fas fa-exchange-alt bg-red"></i>';
        }

        return $icon;
    }


    /**
     * @since May 11, 2020
     * @author john kevin paunel
     * set the timeline icon label
     * @param $action
     * @return string
     */
    public function getDateClassLabel($action): string
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

    /**
     * @since May 13, 2020
     * @author john kevin paunel
     * set the badge label of lead status
     * @param string $status
     * @return string
     * */
    public function setStatusBadge($status): string
    {
        $badge = "";
        if($status === 'Hot')
        {
            $badge = '<span class="badge bg-red role-badge">'.$status.'</span>';
        }
        elseif($status === 'Warm')
        {
            $badge = '<span class="badge bg-warning role-badge">'.$status.'</span>';
        }
        elseif($status === 'Cold')
        {
            $badge = '<span class="badge bg-info role-badge">'.$status.'</span>';
        }
        elseif($status === 'Qualified')
        {
            $badge = '<span class="badge bg-primary role-badge">'.$status.'</span>';
        }
        elseif($status === 'Not qualified')
        {
            $badge = '<span class="badge bg-gray role-badge">'.$status.'</span>';
        }
        elseif($status === 'For tripping')
        {
            $badge = '<span class="badge bg-purple role-badge">'.$status.'</span>';
        }
        elseif($status === 'For reservation')
        {
            $badge = '<span class="badge bg-pink role-badge">'.$status.'</span>';
        }
        elseif($status === 'Inquiry Only')
        {
            $badge = '<span class="badge bg-gray-dark role-badge">'.$status.'</span>';
        }
        elseif($status === 'Not Interested Anymore')
        {
            $badge = '<span class="badge bg-orange role-badge">'.$status.'</span>';
        }
        elseif($status === 'Reserved')
        {
            $badge = '<span class="badge bg-success role-badge">'.$status.'</span>';
        }
        return $badge;
    }

}
