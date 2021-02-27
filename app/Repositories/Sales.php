<?php


namespace App\Repositories;


use App\Repositories\RepositoryInterface\SalesInterface;

class Sales implements SalesInterface
{
    public function profile($sales_id)
    {
        return \App\Sales::findOrFail($sales_id);
    }
}
