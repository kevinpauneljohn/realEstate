<?php


namespace App\Services;


use App\Repositories\UserRepository;
use App\User;

class UpLineService
{
    public $user;
    public function __construct(
        UserRepository $userRepository
    )
    {
        $this->user = $userRepository;
    }

    public function sellers($userId)
    {
        $upLines = [];
        $ctr = 0;
        while ($this->getUpLineIds($userId)->upline_id !== null)
        {
            $upLines[$ctr] = $this->getUpLineIds($userId);
            $userId = $this->getUpLineIds($userId)->upline_id;
            $ctr++;
        }
        return $upLines;
//        return !auth()->user()->hasRole('super admin') ? collect($upLines)->concat(User::role(['super admin'])->get()) : auth()->user();

    }

    /**
     * @param $userId
     * @return mixed
     */
    private function getUpLineIds($userId)
    {
        $user = $this->user->getUserById($userId);
        return $user;
    }
}