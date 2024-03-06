<?php

namespace App\Services;

use App\Contest;
use App\User;
use Illuminate\Support\Facades\DB;

class ContestService
{
    public function saveRankToContest($contest_id, $ranks): void
    {
        foreach ($ranks as $rank){
            DB::table('contest_rank')->insert([
                'contest_id' => $contest_id,
                'rank_id'   => $rank
            ]);
        }
    }

    public function getRanksByContestId($contestId): \Illuminate\Support\Collection
    {
        return DB::table('contest_rank')->where('contest_id',$contestId)->get();
    }

    public function removeRanksByContestId($contestId): int
    {
        return DB::table('contest_rank')->where('contest_id',$contestId)->delete();
    }

    public function joinContest($contest_id, $user_id): bool
    {
        if($this->checkUserIfAllowedToJoin($user_id, $contest_id))
        {
            return DB::table('contest_user')->insert([
                'contest_id' => $contest_id,
                'user_id' => $user_id
            ]);
        }
        return false;
    }

    public function getUserRank($user_id)
    {
        $user = User::findOrFail($user_id);
        return $user->userRankPoint;
    }

    public function checkUserIfAllowedToJoin($user_id, $contest_id): bool
    {
        return DB::table('contest_rank')
                ->where('contest_id',$contest_id)
                ->where('rank_id',$this->getUserRank($user_id)->rank_id)->count() > 0;
    }

    public function checkIfUserAlreadyJoinedContest($user_id, $contest_id): bool
    {
        return DB::table('contest_user')
            ->where('contest_id',$contest_id)
            ->where('user_id',$user_id)
            ->count() > 0;
    }

    public function saveContestWinner($contest_id, $user_id): bool
    {
        $contest = Contest::findOrFail($contest_id);
        $contest->user_id = $user_id;
        return (bool)$contest->save();
    }

}
