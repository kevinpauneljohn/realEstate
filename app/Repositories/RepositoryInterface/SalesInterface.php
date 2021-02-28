<?php


namespace App\Repositories\RepositoryInterface;


interface SalesInterface
{
    /**
     * @param $sales_id
     * @return mixed
     */
    public function profile($sales_id);

    /**
     * view a specified sales
     * @param $sales_id
     * @param $lead_id
     * @return mixed
     */
    public function viewById($sales_id);
}
