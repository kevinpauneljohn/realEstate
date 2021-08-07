<?php

namespace App\Listeners;

use App\Events\UserRequestEvent;
use App\Repositories\ThresholdRepository;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserRequestListener
{
    public $userRepository, $thresholdRepository;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, ThresholdRepository $thresholdRepository)
    {
        $this->userRepository = $userRepository;
        $this->thresholdRepository = $thresholdRepository;
    }

    /**
     * Handle the event.
     *
     * @param  UserRequestEvent  $event
     * @return mixed
     */
    public function handle(UserRequestEvent $event)
    {
        $data = array(
            'upline_id' => auth()->user()->id,
            'firstname' => $event->user->firstname,
            'middlename' => $event->user->middlename,
            'lastname' => $event->user->lastname,
            'mobileNo' => $event->user->mobileNo,
            'address' => $event->user->address,
            'date_of_birth' => $event->user->date_of_birth,
            'email' => $event->user->email,
            'username' => $event->user->username,
            'password' => $event->user->password,
            'role' => json_encode($event->user->role)
        );


        $extra_data = array(
            'action' => 'Create new user',
            'original_data' => $this->userRepository->getUsersOriginalData($data)
        );
        $reason = 'create new user';
        $priority = $this->thresholdRepository->getThresholdPriority('create new user');
        ///save the request to the threshold first for approval
        $this->thresholdRepository->saveThreshold('insert',$reason,$data,$extra_data,
            'users','','pending',$priority);

    }
}
