<?php


namespace App\Repositories;


use App\Rank;

class RankRepository
{
    /**
     * @since June 08, 2020
     * @author john kevin paunel
     * get the data of the rank by ID
     * @param int $id
     * @return object
     * */
    public function getRank($id)
    {
        $rank = Rank::find($id);
        return $rank;
    }
}
