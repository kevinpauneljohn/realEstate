<?php


namespace App\Repositories;


use App\Events\UserRankPointsEvent;
use App\Project;
use App\Sales;
use App\Services\AccountManagerService;
use App\Services\DownLineService;
use App\Threshold;
use App\User;
use App\UserRankPoint;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalesRepository
{
    private $accountManagerService, $downLine;
    public function __construct(
        AccountManagerService $accountManagerService,
        DownLineService $downLineService
    )
    {
        $this->accountManagerService = $accountManagerService;
        $this->downLine = $downLineService;
    }
    /**
     * @since April 23, 2020
     * @author john kevin paunel
     * get sales object by id
     * @param int $id
     * @return object
     * */
    public function getSalesById($id)
    {
        return Sales::findOrFail($id);
    }

    /**
     * @since April 23, 2020
     * @author john kevin paunel
     * get the seller full name by id
     * @param int $id
     * @return object
     * */
    public function getSaleUserById($id)
    {
        return $this->getSalesById($id)->user;
    }


    /**
     * @since April 23, 2020
     * @author john kevin paunel
     * get all the sales object with seller full name
     * @param int $id
     * @return object
     * */
    public function getUserSaleCollectionBySaleId($id)
    {
        $sale = collect(['sale' => $this->getSalesById($id)]);
        $merged  = $sale->merge([
            'fullName' => $this->getSaleUserById($id)->fullname,
            'role' => $this->getSaleUserById($id)->getRoleNames(),
        ]);
        return $merged->all();
    }

    /**
     * @since April 04, 2020
     * @author john kevin paunel
     * create sales original data template
     * @param int $id
     * @return string
     * */
    public function getSalesOriginalData($id,$status, $changes = null)
    {
        $reservation_date = isset($changes['reservation_date'])?$changes['reservation_date']:'';
        $buyer = isset($changes['lead_id'])?$changes['lead_id']:'';
        $project = isset($changes['project_id'])?$changes['project_id']:'';
        $modeUnit = isset($changes['model_unit_id'])?$changes['model_unit_id']:'';
        $lot_area = isset($changes['lot_area'])?$changes['lot_area']:'';
        $floor_area = isset($changes['floor_area'])?$changes['floor_area']:'';
        $phase = isset($changes['phase'])?$changes['phase']:'';
        $block = isset($changes['block'])?$changes['block']:'';
        $lot = isset($changes['lot'])?$changes['lot']:'';
        $total_contract_price = isset($changes['total_contract_price'])?$changes['total_contract_price']:'';
        $discount = isset($changes['discount'])?$changes['discount']:'';
        $processing_fee = isset($changes['processing_fee'])?$changes['processing_fee']:'';
        $reservation_fee = isset($changes['reservation_fee'])?$changes['reservation_fee']:'';
        $equity = isset($changes['equity'])?$changes['equity']:'';
        $loanable_amount = isset($changes['loanable_amount'])?$changes['loanable_amount']:'';
        $financing = isset($changes['financing'])?$changes['financing']:'';
        $terms = isset($changes['terms'])?$changes['terms']:'';
        $details = isset($changes['details'])?$changes['details']:'';

        $sale = $this->getSalesById($id);
        $originalData = '<table class="table table-sm table-bordered table-hover">';
        $originalData .= '<tr><td>Reservation Date</td><td>'.$sale->reservation_date.'</td><td>'.$reservation_date.'</td></tr>';
        $originalData .= '<tr><td>Client Name</td><td>'.$sale->lead->fullname.'</td><td>'.$buyer.'</td></tr>';
        $originalData .= '<tr><td>Project</td><td>'.$sale->project->name.'</td><td>'.$project.'</td></tr>';
        $originalData .= '<tr><td>Model Unit</td><td>'.$sale->modelUnit->name.'</td><td>'.$modeUnit.'</td></tr>';
        $originalData .= '<tr><td>Lot Area</td><td>'.$sale->lot_area.'</td><td>'.$lot_area.'</td></tr>';
        $originalData .= '<tr><td>Floor Area</td><td>'.$sale->floor_area.'</td><td>'.$floor_area.'</td></tr>';
        $originalData .= '<tr><td>Phase</td><td>'.$sale->phase.'</td><td>'.$phase.'</td></tr>';
        $originalData .= '<tr><td>Block</td><td>'.$sale->block.'</td><td>'.$block.'</td></tr>';
        $originalData .= '<tr><td>Lot</td><td>'.$sale->lot.'</td><td>'.$lot.'</td></tr>';
        $originalData .= '<tr><td>Total Contract Price</td><td>'.number_format($sale->total_contract_price).'</td><td>'.$total_contract_price.'</td></tr>';
        $originalData .= '<tr><td>Discount</td><td>'.number_format($sale->discount).'</td><td>'.$discount.'</td></tr>';
        $originalData .= '<tr><td>Processing Fee</td><td>'.number_format($sale->processing_fee).'</td><td>'.$processing_fee.'</td></tr>';
        $originalData .= '<tr><td>Reservation Fee</td><td>'.number_format($sale->reservation_fee).'</td><td>'.$reservation_fee.'</td></tr>';
        $originalData .= '<tr><td>Equity</td><td>'.number_format($sale->equity).'</td><td>'.$equity.'</td></tr>';
        $originalData .= '<tr><td>Loanable Amount</td><td>'.number_format($sale->loanable_amount).'</td><td>'.$loanable_amount.'</td></tr>';
        $originalData .= '<tr><td>Financing</td><td>'.$sale->financing.'</td><td>'.$financing.'</td></tr>';
        $originalData .= '<tr><td>Terms</td><td>'.$sale->terms.'</td><td>'.$terms.'</td></tr>';
        $originalData .= '<tr><td>Details</td><td>'.$sale->details.'</td><td>'.$details.'</td></tr>';
        $originalData .= '<tr><td>Status</td><td>'.$sale->status.'</td><td>'.$status.'</td></tr>';
        $originalData .= '</table>';

        return $originalData;
    }


    /**
     * @since April 25, 2020
     * @author john kevin paunel
     * update the sales
     *
     * */
    public function updateSales($request, $id)
    {
        $sales = Sales::find($id);
        $sales->reservation_date = $request->edit_reservation_date;
        $sales->lead_id = $request->edit_buyer;
        $sales->project_id = $request->edit_project;
        $sales->model_unit_id = $request->edit_model_unit;
        $sales->lot_area = $request->edit_lot_area;
        $sales->floor_area = $request->edit_floor_area;
        $sales->phase = $request->edit_phase;
        $sales->block = $request->edit_block_number;
        $sales->lot = $request->edit_lot_number;
        $sales->total_contract_price = $request->edit_total_contract_price;
        $sales->discount = $request->edit_discount;
        $sales->processing_fee = $request->edit_processing_fee;
        $sales->reservation_fee = $request->edit_reservation_fee;
        $sales->equity = $request->edit_equity;
        $sales->loanable_amount = $request->edit_loanable_amount;
        $sales->financing = $request->edit_financing;
        $sales->terms = $request->edit_dp_terms;
        $sales->details = $request->edit_details;
//        $sales->commission_rate = $this->setCommissionRate($request->edit_project,auth()->user()->id);
        $sales->commission_rate = $this->setCommissionRate($request->edit_project,$this->accountManagerService->checkIfUserIsAccountManager()->id);

        if($sales->isDirty())
        {
            $sales->save();
            //update the rank and points
//            $total_points = $this->getTotalSales(auth()->user()->id) / 100000;getTotalSales
            $total_points = $this->getTotalSales($this->accountManagerService->checkIfUserIsAccountManager()->id) / 100000;

            ///check if the user has extra points
//            $extra_points = auth()->user()->userRankPoint == null ? 0 : auth()->user()->userRankPoint->extra_points;
            $extra_points = $this->accountManagerService->checkIfUserIsAccountManager()->userRankPoint == null ? 0 : $this->accountManagerService->checkIfUserIsAccountManager()->userRankPoint->extra_points;

//            event(new UserRankPointsEvent(auth()->user(),$total_points,$extra_points));
            event(new UserRankPointsEvent($this->accountManagerService->checkIfUserIsAccountManager(),$total_points,$extra_points));
            return ['success' => true, 'message' => 'Sales Successfully Updated!', $sales];
        }

        return ['success' => false, 'message' => 'No Changes Occurred!', $sales];
    }

    /**
     * @since April 29, 2020
     * @author john kevin paunel
     * get the sales request count
     * @param int $salesId
     * @return object
     * */
    public function get_sales_request_count_in_threshold($salesId)
    {
        $threshold = Threshold::where([
            ['storage_name','=','sales'],
            ['storage_id','=',$salesId],
//            ['user_id','=',auth()->user()->id],
            ['user_id','=',$this->accountManagerService->checkIfUserIsAccountManager()->id],
            ['status','=','pending'],
            ['data->status','!=',null],
        ])->count();

        return $threshold;
    }

    /**
     * @since April 29, 2020
     * @author john kevin paunel
     * get the sales request count for update attribute
     * @param int $salesId
     * @return object
     * */
    public function get_sales_request_count_in_threshold_for_attribute($salesId)
    {
        $threshold = Threshold::where([
            ['storage_name','=','sales'],
            ['storage_id','=',$salesId],
//            ['user_id','=',auth()->user()->id],
            ['user_id','=',$this->accountManagerService->checkIfUserIsAccountManager()->id],
            ['status','=','pending'],
            ['extra_data->action','=','Update the sales attribute'],
        ])->count();

        return $threshold;
    }

    /**
     * March 24, 2020
     * @author john kevin paunel
     * get the upline IDs
     * @param string $user_id
     * @return string
     * */
    public function getUpLineIds($user_id)
    {
        $user = User::find($user_id);
        return $user->upline_id;
    }

    /**
     * @param int $project_id
     * @param $user_id
     * @return mixed
     * @author john kevin paunel
     * set the agents commission rate
     * algorithm for getting the commission rate
     * returns the user's current sales commission rate
     */
    public function setCommissionRate($project_id,$user_id)
    {
        ///$user = auth()->user()->id;/*set the id of the current user*/
        $user = $user_id;/*set the id of the current user*/
        $upLines = array(); /*instantiate the up line ids */
        $ctr = 1; /*array counter*/

        #this will loop until it gets all the user's up line IDs

        $upLines[$user] = 0;/*initialize the up line value to 0*/
        while($this->getUpLineIds($user) != null)
        {
            $user = $this->getUpLineIds($user);/*set the new user id*/
            $upLines[$user] = $ctr;/*set the user key value use for arranging the user by position or rank*/
            $ctr++;
        }


        $project_rate = Project::find($project_id); /*get the project rate*/
        $rate = $project_rate->commission_rate; /*instantiate the project rate*/

        arsort($upLines);/*this will arrange the Ids in descending order*/
        foreach ($upLines as $key => $value)
        {
            $user = User::find($key);

            if(!$user->hasRole(['super admin','account manager','online warrior']))
            {
                /*this will check if the project id is available on users commissions table project id column*/
                if($user->commissions()->where('project_id','=',$project_id)->count() > 0)
                {
                    /*if the commission was set to a specific project and it matches the sales project id*/
                    $user_rate = $user->commissions()->where('project_id','=',$project_id)->first()->commission_rate;
                }else{
                    $user_rate =  $user->commissions()->where('project_id','=',null)->first()->commission_rate;/*get the user commission rate*/
                }

                /*this conditional statement will be used if the commission rate offers by the project is lower
                or equal to the user's commission rate*/
                if($user_rate >= $rate)
                {
                    --$rate;
                }
                elseif($user_rate <= 0){

                    $rate = 0; /* commission rate is zero and will return an error message to the user*/
                }
                else{
                    $rate = $user_rate;
                }
            }
        }
        return $rate;

    }

    public function getTotalSales($user_id)
    {
        $sales = $this->retrieve([$user_id]);
        $tcp = $sales->sum('total_contract_price');
        $discount = $sales->sum('discount');
        return $tcp - $discount;
    }

    /**
     * March 31, 2020
     * @author john kevin paunel
     * get the user total sales
     * @param string $user_id
     * @return string
     * */
    public function getTotalPersonalSales($user_id)
    {
        $sales = $this->retrieve([$user_id])->whereYear('reservation_date',now()->format('Y'));
        $tcp = $sales->sum('total_contract_price');
        $discount = $sales->sum('discount');
        return $tcp - $discount;
    }

    public function getTotalPersonalSalesThisMonth($user_id)
    {
        $sales = $this->retrieve([$user_id])->whereYear('reservation_date',now()->format('Y'))->whereMonth('reservation_date',now()->format('m'));
        $tcp = $sales->sum('total_contract_price');
        $discount = $sales->sum('discount');
        return $tcp - $discount;
    }

    public function getTeamUnitSold($userId)
    {
        $downLineIds = collect($this->downLine->extractDownLines($userId)->pluck('id'))->concat([$this->accountManagerService->checkIfUserIsAccountManager()->id])->all();
        return $this->retrieve($downLineIds)->whereYear('reservation_date',now()->format('Y'));
    }


    public function getTotalUnitSold($userId)
    {
        return $this->retrieve($userId)->whereYear('reservation_date',now()->format('Y'));
    }

    public function retrieve($downLineIds)
    {
        return Sales::whereIn('user_id',$downLineIds)->where('status','!=','cancelled');
    }

    /**
     * get the total sales of the team
     * @param $userId
     * @return mixed
     */
    public function getTeamSales($userId)
    {
        $downLineIds = collect($this->downLine->extractDownLines($userId)->pluck('id'))->concat([$this->accountManagerService->checkIfUserIsAccountManager()->id])->all();
        $tcp = $this->retrieve($downLineIds)->whereYear('reservation_date',now()->format('Y'))->sum('total_contract_price');
        $discount = $this->retrieve($downLineIds)->whereYear('reservation_date',now()->format('Y'))->sum('discount');
        return $tcp - $discount;
    }

    public function getTeamSalesByDateRange($userId, $start_date, $end_date)
    {
        $downLineIds = collect($this->downLine->extractDownLines($userId)->pluck('id'))->concat([$this->accountManagerService->checkIfUserIsAccountManager()->id])->all();
        $tcp = $this->retrieve($downLineIds)->whereBetween('reservation_date',[$start_date, $end_date])->sum('total_contract_price');
        $discount = $this->retrieve($downLineIds)->whereBetween('reservation_date',[$start_date, $end_date])->sum('discount');
        return $tcp - $discount;
    }

    /**
     * get the total sales of all the down lines
     * @param $userId
     * @return mixed
     */
    public function getDownLineSales($userId)
    {
        $downLineIds = collect($this->downLine->extractDownLines($userId)->pluck('id'))->all();
        $tcp = $this->retrieve($downLineIds)->whereYear('reservation_date',now()->format('Y'))->sum('total_contract_price');
        $discount = $this->retrieve($downLineIds)->whereYear('reservation_date',now()->format('Y'))->sum('discount');
        return $tcp - $discount;
    }

    public function getTeamSalesThisMonth($userId)
    {
        $teamIds = collect($this->downLine->extractDownLines($userId)->pluck('id'))->concat([$this->accountManagerService->checkIfUserIsAccountManager()->id])->all();
        $sales = $this->retrieve($teamIds)->whereMonth('reservation_date',now()->format('m'))->whereYear('reservation_date',now()->format('Y'));
        $tcp = $sales->sum('total_contract_price');
        $discount = $sales->sum('discount');
        return $tcp - $discount;
    }

    /**
     * May 31, 2020
     * @author john kevin paunel
     * get the user total sales
     * @param $user_id
     * @return mixed
     */
    public function getTotalSalesThisMonth($user_id)
    {
        $sales = $this->retrieve([$user_id])->whereMonth('reservation_date',now()->format('m'))->whereYear('reservation_date',now()->format('Y'));
        $tcp = $sales->sum('total_contract_price');
        $discount = $sales->sum('discount');
        return $tcp - $discount;
    }

    /**
     * @since June 06, 2020
     * @author john kevin paunel
     * get the current user Total Points
     * @return int
     * */
    public function getCurrentUserTotalPoints()
    {
//        $total_points = UserRankPoint::where('user_id',auth()->user()->id);
        $total_points = UserRankPoint::where('user_id',$this->accountManagerService->checkIfUserIsAccountManager()->id);
        return $total_points->first()->sales_points + $total_points->first()->extra_points;
    }

    /**
     * get sales data
     * @param $id
     * @return mixed
     */
    public function viewSale($id)
    {
        return Sales::where('id',$id)->first();
    }

    public function getSalesByUser($userId)
    {
        return $this->retrieve(array($userId));
    }

    public function getSalesData($data)
    {
        $sale = $this->getSalesById($data['id']);
        $originalData = '<table class="table table-sm table-bordered table-hover">';
        $originalData .= '<tr><td>Reservation Date</td><td>'.$sale->reservation_date.'</td></tr>';
        $originalData .= '<tr><td>Client Name</td><td>'.$sale->lead->fullname.'</td></tr>';
        $originalData .= '<tr><td>Project</td><td>'.$sale->project->name.'</td></tr>';
        $originalData .= '<tr><td>Model Unit</td><td>'.$sale->modelUnit->name.'</td></tr>';
        $originalData .= '<tr><td>Lot Area</td><td>'.$sale->lot_area.'</td></tr>';
        $originalData .= '<tr><td>Floor Area</td><td>'.$sale->floor_area.'</td></tr>';
        $originalData .= '<tr><td>Phase</td><td>'.$sale->phase.'</td></tr>';
        $originalData .= '<tr><td>Block</td><td>'.$sale->block.'</td></tr>';
        $originalData .= '<tr><td>Lot</td><td>'.$sale->lot.'</td></tr>';
        $originalData .= '<tr><td>Total Contract Price</td><td>'.number_format($sale->total_contract_price).'</td></tr>';
        $originalData .= '<tr><td>Discount</td><td>'.number_format($sale->discount).'</td></tr>';
        $originalData .= '<tr><td>Processing Fee</td><td>'.number_format($sale->processing_fee).'</td></tr>';
        $originalData .= '<tr><td>Reservation Fee</td><td>'.number_format($sale->reservation_fee).'</td></tr>';
        $originalData .= '<tr><td>Equity</td><td>'.number_format($sale->equity).'</td></tr>';
        $originalData .= '<tr><td>Loanable Amount</td><td>'.number_format($sale->loanable_amount).'</td></tr>';
        $originalData .= '<tr><td>Financing</td><td>'.$sale->financing.'</td></tr>';
        $originalData .= '<tr><td>Terms</td><td>'.$sale->terms.'</td></tr>';
        $originalData .= '<tr><td>Details</td><td>'.$sale->details.'</td></tr>';
        $originalData .= '<tr><td>Status</td><td>'.$sale->status.'</td></tr>';
        $originalData .= '</table>';

        return $originalData;
    }
}
