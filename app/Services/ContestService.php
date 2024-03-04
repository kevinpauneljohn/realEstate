<?php

namespace App\Services;

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
}
