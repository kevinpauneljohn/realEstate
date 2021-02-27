<?php


namespace App\Repositories\RepositoryInterface;


interface SalesInterface
{
    /**
     * @param $sales_id
     * @return mixed
     */
    public function profile($sales_id);
}
