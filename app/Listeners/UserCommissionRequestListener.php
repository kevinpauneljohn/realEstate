<?php

namespace App\Listeners;

use App\Events\UserCommissionRequestEvent;
use App\Repositories\ThresholdRepository;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserCommissionRequestListener
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
     * @param  UserCommissionRequestEvent  $event
     * @return mixed
     */
    public function handle(UserCommissionRequestEvent $event)
    {
        $data = array(
            'user_id' => $event->user['user_id'],
            'id' => $event->user['commission_id'],
            'project_id' => $event->user['project'],
            'commission_rate' => $event->user['commission_rate']
        );

        $extra_data = array(
            'action' => $event->user['action'].' User commission',
            'original_data' => $this->userRepository->getUsersOriginalCommissionData($data)
        );
        $reason = $event->user['reason'];
        $priority = $this->thresholdRepository->getThresholdPriority($event->user['action'].' user commission');
        ///save the request to the threshold first for approval
        $this->thresholdRepository->saveThreshold($event->user['action'],$reason,$data,$extra_data,
            'commissions',$event->user['commission_id'],'pending',$priority);

    }
}
