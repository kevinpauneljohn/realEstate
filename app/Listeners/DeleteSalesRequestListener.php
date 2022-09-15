<?php

namespace App\Listeners;

use App\Events\DeleteSalesRequestEvent;
use App\Repositories\ThresholdRepository;
use App\Repositories\SalesRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DeleteSalesRequestListener
{
    public $salesRepository, $thresholdRepository;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SalesRepository $salesRepository, ThresholdRepository $thresholdRepository)
    {
        $this->salesRepository = $salesRepository;
        $this->thresholdRepository = $thresholdRepository;
    }

    /**
     * Handle the event.
     *
     * @param  DeleteSalesRequestEvent  $event
     * @return mixed
     */
    public function handle(DeleteSalesRequestEvent $event)
    {
        $data = array(
            'user_id' => $event->user['user_id'],
            'id' => $event->user['sales_id']
        );

        $extra_data = array(
            'action' => $event->user['action'].' sales',
            'original_data' => $this->salesRepository->getSalesData($data)
        );

        $reason = $event->user['reason'];
        $priority = $this->thresholdRepository->getThresholdPriority($event->user['action'].' sales');
        ///save the request to the threshold first for approval
        $this->thresholdRepository->saveThreshold($event->user['action'],$reason,$data,$extra_data,
            'sales',$event->user['sales_id'],'pending',$priority);

    }
}
