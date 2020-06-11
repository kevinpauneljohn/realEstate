<?php


namespace App\Repositories;


use App\Action;
use App\Events\ThresholdEvent;
use App\Events\UserRankPointsEvent;
use App\Priority;
use App\Threshold;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ThresholdRepository
{
    public $walletRepository, $salesRepository;
    public function __construct(WalletRepository $walletRepository, SalesRepository $salesRepository)
    {
        $this->walletRepository = $walletRepository;
        $this->salesRepository = $salesRepository;
    }

    /**
     * @since April 15, 2020
     * @author john kevin paunel
     * get the priority ID
     * @param string $name
     * @return object
     * */
    public function getThresholdPriority($name)
    {
        return Action::where('name',$name)->first()->priority_id;
    }


    /**
     * @since April 22, 2020
     * @author john kevin paunel
     * save the user request to threshold
     * @param string $type
     * @param string $reason
     * @param array $data
     * @param string $table
     * @param int $table_id
     * @param string $status
     * @param object $priority
     * */
    public function saveThreshold($type, $reason, $data, $extra_data, $table, $table_id, $status,$priority)
    {
        $days = Priority::find($priority)->days;

        $threshold = new Threshold();
        $threshold->user_id = auth()->user()->id;
        $threshold->type = $type;
        $threshold->description = $reason;
        $threshold->data = $data;
        $threshold->extra_data = $extra_data;
        $threshold->storage_name = $table;
        $threshold->storage_id = $table_id;
        $threshold->status = $status;
        $threshold->priority_id = $priority;
        $threshold->due_date = today()->addDays($days);
        $threshold->lid = false;

        $threshold->save();

        ///notify the admin if there are user requests
        event(new ThresholdEvent($threshold));
    }

    /**
     * @since April 24, 2020
     * @author john kevin paunel
     * update threshold data
     * @param int $id
     * @param string $status
     * @param int $approved_by
     * @param int $priority_id
     * @param string $admin_report
     * @return void
     * */
    public function updateThreshold($id, $status = null, $approved_by = null, $priority_id = null, $admin_report = null)
    {
        $threshold = Threshold::find($id);
        if($status !== null){$threshold->status = $status;}
        if($approved_by !== null){$threshold->approved_by = $approved_by;}
        if($priority_id !== null){$threshold->priority_id = $priority_id;}
        if($admin_report !== null){$threshold->admin_report = $admin_report;}
        $threshold->save();

        if($status == 'approved')
        {
            $this->thresholdAction($threshold);
        }
        event(new ThresholdEvent($threshold));
    }

    /**
     * @since April 24, 2020
     * @author john kevin paunel
     * apply the update or delete to the requested table
     * @param object $threshold
     * @return void
     * */
    public function thresholdAction($threshold)
    {
        if($threshold->type == 'update')
        {
            DB::table($threshold->storage_name)
                ->where('id',$threshold->storage_id)
                ->update((array)$threshold->data);
        }elseif($threshold->type == 'insert'){
            if($threshold->storage_name == 'users')
            {
                ///create new user
                $user = new User();
                $user->upline_id = $threshold->data->upline_id;
                $user->firstname = $threshold->data->firstname;
                $user->middlename = $threshold->data->middlename;
                $user->lastname = $threshold->data->lastname;
                $user->mobileNo = $threshold->data->mobileNo;
                $user->address = $threshold->data->address;
                $user->date_of_birth = $threshold->data->date_of_birth;
                $user->email = $threshold->data->email;
                $user->username = $threshold->data->username;
                $user->password = bcrypt($threshold->data->password);
                $user->save();

                $find = array('["','"]');
                $replace = array('');
                $input = str_replace($find,$replace,$threshold->data->role);
                $roles = explode('","',$input);
                foreach ($roles as $role){
                    $user->assignRole($role);
                }

                //set the user rank based on his points
                event(new UserRankPointsEvent($user,0));

                ///send initial 500 dream coins on the user's dream wallet
                $this->walletRepository->setMoney(
                    $user->id,
                    User::where('username','kevinpauneljohn')->first()->id,
                    500,'Initial incentives can be cashed out if there is a reservation',
                    true,false,'incentive','for-approval'
                );
            }

        }else{
            DB::table($threshold->storage_name)
                ->where('id',$threshold->storage_id)
                ->update(['deleted_at' => now()]);
        }

        //if the request has impact on sales table update the user rank and points
        if($threshold->storage_name === 'sales')
        {
            $user = User::find($threshold->user_id);
            $total_points = $this->salesRepository->getTotalSales($user->id) / 100000;
            event(new UserRankPointsEvent($user,$total_points));
        }
    }



    /**
     * @since April 22, 2020
     * @author john kevin paunel
     * get the data to show for requestList method
     * @return object
     * */
    public function getAllThreshold()
    {
        if(auth()->user()->hasRole(['super admin','admin']))
        {
            $threshold = Threshold::all();
        }else{

            //get only the threshold requested by the current user
            $threshold = Threshold::where([
                ['user_id','=',auth()->user()->id]
            ])->get();
        }
        return $threshold;
    }

    /**
     * @since April 28, 2020
     * @author john kevin paunel
     * get all request number
     * @param string $table
     * @param int $id
     * @return object
     * */
    public function getAllRequestByStorageId($table, $id)
    {
        if(auth()->user()->can('key holder'))
        {
            $request = Threshold::where([
                ['storage_name','=',$table],
                ['storage_id','=',$id],
            ])->orderBy('id','desc')->get();
        }else{
            $request = Threshold::where([
                ['user_id','=',auth()->user()->id],
                ['storage_name','=',$table],
                ['storage_id','=',$id],
            ])->orderBy('id','desc')->get();
        }
        return $request;
    }

    /**
     * @since April 24, 2020
     * @author john kevin paunel
     * get all the threshold details
     * @param int $id
     * @return object
     * */
    public function getThresholdDetails($id)
    {
        $threshold = Threshold::findOrFail($id);
        $request = collect(
            $threshold,
            [
                'role'  => $threshold->user->getRoleNames(),
                'priority' => $threshold->priority->name,
            ]);

        return $request->all();
    }

    /**
     * @since April 28, 2020
     * @author john kevin paunel
     * set the requests status to display
     * @param string $status
     * @return object
     * */
    public function getThresholdStatus($status)
    {
        $user = auth()->user();
        if($user->hasRole(['super admin','admin']))
        {
            $threshold = Threshold::where('status',$status)->get();
        }else{
            $threshold = Threshold::where([
                ['user_id','=',$user->id],
                ['status','=',$status]
            ])->get();
        }
        return $threshold;
    }

}
