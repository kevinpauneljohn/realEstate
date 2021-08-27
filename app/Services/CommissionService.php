<?php


namespace App\Services;


use App\Project;
use App\Repositories\SalesRepository;
use App\User;

class CommissionService
{
    public $sales,
            $salesId,
            $userId,
            $upLine,
            $downLines;
    public function __construct(
        SalesRepository $salesRepository,
        UpLineService $upLineService,
        DownLineService $downLineService
    )
    {
        $this->sales = $salesRepository;
        $this->upLine = $upLineService;
        $this->downLines = $downLineService;
    }

    /**
     * determine if the requester is the main seller
     * @return bool
     */
    public function isMainSeller()
    {
        return $this->sales->getSalesById($this->salesId)->user_id === $this->userId ? true : false;
    }

    /**
     * @param $salesId
     * @param $userId
     * @return mixed
     */
    public function getAllowedCommission($salesId, $userId)
    {
        $this->salesId = $salesId;
        $this->userId = $userId;
//
        $sales = $this->sales->getSalesById($this->salesId);

        if($this->isMainSeller() === true)
        {
            // the requester is the main seller of the sales which will retrieve the indicated commission rate from the sales
            return [
                'commission' => (float)$this->sales->setCommissionRate($sales->project_id, $this->userId),
                'upLines' => collect(collect($this->upLine->sellers(auth()->user()->id))->whereNotIn('id',[auth()->user()->id]))->map(function($item, $key){
                    return collect($item)->merge(['approval' => null,'remarks' => null,'byPass' => null, 'is_by_passed' => false])->only(['id','approval','remarks','byPass','is_by_passed']);
                })
            ];
        }
        return $this->getRequesterRightFulCommission($sales->project_id);
    }

    /**
     * get the main seller of the sales
     * @return mixed
     */
    public function getMainSeller()
    {
        $sales = $this->sales->getSalesById($this->salesId);
        return $sales->user_id;
    }

    /**
     * @param $projectId
     * @return array
     */
    private function getRequesterRightFulCommission($projectId)
    {
        //retrieve all seller that are connected to the sales
        $sellers = $this->upLine->sellers($this->getMainSeller());
        $commission = array();
        $key = 0;
        $teamLeader = collect($sellers)->last()->commissions()->where('project_id',$projectId)->first() !== null
            ? collect($sellers)->last()->commissions()->where('project_id',$projectId)->first()
            : collect($sellers)->last()->commissions()->where('project_id',null)->first();

        if(auth()->user()->hasRole('super admin'))
        {
            return [
                'commission' => Project::find($projectId)->commission_rate - $teamLeader->commission_rate,
                'upLines' => $this->upLine->sellers(auth()->user()->id)
            ];
        }else{
            foreach($sellers as $key => $seller){
                $sellersCommission = $seller->commissions()->where('project_id',$projectId)->first();
                $commission[$key] = [
                    'agent' => $seller->fullname,
                    'commission' => $sellersCommission !== null
                        ? $sellersCommission->commission_rate
                        : $seller->commissions()->where('project_id',null)->first()->commission_rate
                ];

                //if the user matches the requester ID wil stop the loop
                if($seller->id === auth()->user()->id)
                {
                    break;
                }
            }
        }
        return [
            'commission' => $commission[$key]['commission'] - $commission[$key - 1]['commission'],
            'upLines' => collect(collect($this->upLine->sellers(auth()->user()->id))->whereNotIn('id',[auth()->user()->id]))->map(function($item, $key){
                return collect($item)->merge(['approval' => null,'remarks' => null,'byPass' => null, 'is_by_passed' => false])->only(['id','approval','remarks','byPass','is_by_passed']);
            })
        ];
    }
}