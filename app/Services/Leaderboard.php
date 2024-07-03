<?php

namespace App\Services;

use App\User;

class Leaderboard
{
    public function userSales($start_date, $end_date): array
    {
        $users = array();
        $ctr = 0;
        foreach(User::all() as $user)
        {
            $users[$user->id] = $user->sales()->whereBetween('reservation_date',[$start_date, $end_date])->where('status','!=','cancelled')->sum('total_contract_price');
        }
        arsort($users);
        return $users;
    }

    public function userRankingBySales($start_date, $end_date): array
    {
        $ctr = 0;
        $data = array();
        foreach($this->userSales($start_date, $end_date) as $key => $value)
        {
            if($ctr < 3)
            {
                $data[$ctr] = collect(User::find($key))->merge(['sales' => number_format($value,2)]);
                $ctr++;
            }
        }
        return $data;
    }
}
