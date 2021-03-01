<?php


namespace App\Repositories;


use App\ClientRequirement;
use App\Repositories\RepositoryInterface\ClientRequirementInterface;

class ClientRequirementRepository implements ClientRequirementInterface
{
    public function viewBySales($sales_id)
    {
        return ClientRequirement::where('sales_id',$sales_id)->get();
    }

    public function viewSpecifiedSale($sales_id)
    {
        return ClientRequirement::where('sales_id',$sales_id)->first();
    }

    public function save(ClientRequirement $clientRequirement, $requirements)
    {
        return $clientRequirement::create($requirements);
    }
}
