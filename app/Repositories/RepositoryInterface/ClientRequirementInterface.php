<?php


namespace App\Repositories\RepositoryInterface;


use App\ClientRequirement;

interface ClientRequirementInterface
{
    /**
     * view all requirements linked to the sales
     * @param $sales_id
     * @return mixed
     */
    public function viewBySales($sales_id);

    /**
     * @param $sales_id
     * @return mixed
     */
    public function viewSpecifiedSale($sales_id);

    /**
     * @param ClientRequirement $clientRequirement
     * @param $requirements
     * @return mixed
     */
    public function save(ClientRequirement $clientRequirement, $requirements);
}
