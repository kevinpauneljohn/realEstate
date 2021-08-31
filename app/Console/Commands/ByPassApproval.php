<?php

namespace App\Console\Commands;

use App\Services\CommissionRequestService;
use App\User;
use Illuminate\Console\Command;

class ByPassApproval extends Command
{
    public $commissionRequest;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bypass:approval';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CommissionRequestService $commissionRequestService)
    {
        $this->commissionRequest = $commissionRequestService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $requests = [];
        foreach ($this->commissionRequest->getCommissionRequests() as $key => $value){
            $requests[$value['id']] = collect(collect($this->commissionRequest->check_by_pass($value['id']))->filter(function($value, $key){
                return $value['daysPasses'] <= $value['daysByPass'] && $value['is_by_passed'] == false;
            })->first());
        }


        $data = [];
        foreach ($requests as $key => $request){
            if(collect($request)->count()){
                $data2 = [];
                $lastByPassed = collect($this->commissionRequest->getSpecifiedRequest($request['request_id'])['approval'])->where('is_by_passed',true)->last();
                $upLine = collect(User::find($lastByPassed['id']))->count() > 0 ? User::find($lastByPassed['id'])->upline_id : 0;
                foreach (collect($this->commissionRequest->getSpecifiedRequest($request['request_id']))['approval'] as $key2 => $value){

                    $data2[$key2] = $value;
                    if($value['id'] == $request['upLine_id'] && $value['approval'] == null && $value['is_by_passed'] == false)
                    {
                        $data2[$key2] = [
                            "id" => $request['upLine_id'],
                            "approval" => "approved",
                            "remarks" => null,
                            "byPass" => now(),
                            "is_by_passed" => true,
                            "action" => "bypassed"
                        ];

                    }

                    if($upLine != 0)
                    {
                        if ($value['id'] == $upLine && $value['approval'] == null && $value['is_by_passed'] == false){
                            $data2[$key2] = [
                                "id" => $value['id'],
                                "approval" => "approved",
                                "remarks" => null,
                                "byPass" => now(),
                                "is_by_passed" => true,
                                "action" => "bypassed"
                            ];
                        }


                    }

                }

                $this->commissionRequest->updateCommissionRequest($key,$data2);
                $data[$key] = $data2;
            }
        }

//        return collect($data);
        return true;
    }
}
