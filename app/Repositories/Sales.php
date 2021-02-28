<?php


namespace App\Repositories;


use App\Repositories\RepositoryInterface\SalesInterface;

class Sales implements SalesInterface
{
    public function profile($sales_id)
    {
        return \App\Sales::findOrFail($sales_id);
    }

    public function viewById($sales_id)
    {
        return auth()->user()->sales->where('id',$sales_id)->first();
    }
}
