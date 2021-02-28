<?php


namespace App\Repositories\RepositoryInterface;


interface ClientRequirementInterface
{
    /**
     * view all requirements linked to the sales
     * @param $sales_id
     * @return mixed
     */
    public function viewBySales($sales_id);
}
