<?php


namespace App\Repositories;


use App\Lead;
use App\Repositories\RepositoryInterface\LeadInterface;
use App\User;
use Yajra\DataTables\DataTables;

class Leads implements LeadInterface
{
    private $leadRepository;

    public function __construct(LeadRepository $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }
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

    public function leadsTable($leads,array $rawColumns)
    {
        return DataTables::of($leads)
            ->editColumn('date_inquired',function($lead){
                ///
                return $lead->date_inquired->format('M d, Y');
            })
            ->addColumn('last_contacted',function($lead){
                if($lead->LogTouches->count() > 0){
                    return $lead->LogTouches->pluck('date')->last()->diffForHumans();
                }
            })
            ->addColumn('fullname',function($lead){
                $lead = '<a href="'.route("leads.show",["lead" => $lead->id]).'">'.$lead->fullname.'</a>';
                return $lead;
            })
            ->editColumn('mobileNo',function($lead){
                return '<a href="tel:'.$lead->mobileNo.'">'.$lead->mobileNo.'</a>';
            })
            ->editColumn('email',function($lead){
                return '<a href="mailto:'.$lead->email.'">'.$lead->email.'</a>';
            })
            ->editColumn('important',function($lead){
                if($lead->important === 1)
                {
                    return '<div align="center"><img src="'.asset('/images/filled-star.svg').'" class="star" height="25"></div>';
                }
                return "";
            })
            ->editColumn('lead_status', function($lead){
                return $this->leadRepository->setStatusBadge($lead->lead_status);
            })
            ->editColumn('assigned_to', function($lead){

                if(auth()->user()->hasRole(['admin','account manager','super admin']))
                {
                    $action = '<select class="form-control select2 assigned_to" id="'.$lead->id.'">';
                    $action .= '<option value=""></option>';
                    foreach (User::whereHas("roles",function($role){$role->where("name","online warrior");})->get() as $warrior){
                        $selected = $lead->online_warrior_id === $warrior->id? "selected" :"";
                        $action .= '<option value="'.$warrior->id.'" '.$selected.'>'.$warrior->username.'</option>';
                    }
                    $action .= '</select>';
                    return $action;
                }
                return $lead->warrior !== null ? $lead->warrior->fullname:"";

            })
            ->addColumn('action', function ($lead)
            {
                $action = "";
                if(auth()->user()->can('view lead'))
                {
                    $action .= '<button class="btn btn-xs btn-info view-details" id="'.$lead->id.'" data-toggle="modal" data-target="#lead-details" title="View Details"><i class="fa fa-info-circle"></i> </button>';
                }
                if((auth()->user()->hasRole(['online warrior']) && (auth()->user()->id === $lead->online_warrior_id)) || auth()->user()
                        ->hasRole(['super admin','account manager','admin','team leader','referral','manager','agent']))
                {
                    if(auth()->user()->can('view lead'))
                    {
                        $action .= '<a href="'.route("leads.show",["lead" => $lead->id]).'" class="btn btn-xs btn-success view-btn" id="'.$lead->id.'" title="Manage Leads"><i class="fas fa-folder-open"></i></a>';
                    }
                    if(auth()->user()->can('edit lead'))
                    {
                        $action .= '<a href="'.route("leads.edit",["lead" => $lead->id]).'" class="btn btn-xs btn-primary view-btn" id="'.$lead->id.'" title="Edit Leads"><i class="fa fa-edit"></i></a>';
                    }
                    if(auth()->user()->can('delete lead') && $lead->sales()->count() < 1)
                    {
                        $action .= '<a href="#" class="btn btn-xs btn-danger delete-lead-btn" id="'.$lead->id.'" title="Delete Leads"><i class="fa fa-trash"></i></a>';
                    }
                    if(auth()->user()->can('edit lead'))
                    {
                        if($lead->lead_status !== 'Reserved')
                        {
                            $action .= '<button class="btn btn-xs bg-yellow set-status" id="'.$lead->id.'" title="Change Status" data-toggle="modal" data-target="#set-status"><i class="fa fa-thermometer-three-quarters"></i></button>';
                        }
                    }
                }

                return $action;
            })
            ->rawColumns($rawColumns)
            ->make(true);
    }

}
