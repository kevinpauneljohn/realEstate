<?php


namespace App\Repositories;


use App\Action;
use App\Threshold;
use Illuminate\Support\Facades\DB;

class ThresholdRepository
{

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
     * save the use request to threshold
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

        $threshold->save();
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
     * @return object
     * */
    public function updateThreshold($id, $status = null, $approved_by = null, $priority_id = null, $admin_report = null)
    {
        $threshold = Threshold::find($id);
        if($status !== null){$threshold->status = $status;}
        if($approved_by !== null){$threshold->approved_by = $approved_by;}
        if($priority_id !== null){$threshold->priority_id = $priority_id;}
        if($admin_report !== null){$threshold->admin_report = $admin_report;}
        $threshold->save();
        $this->thresholdAction($threshold);
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
        DB::table($threshold->storage_name)
        ->where('id',$threshold->storage_id)
        ->update((array)$threshold->data);
    }



    /**
     * @since April 22, 2020
     * @author john kevin paunel
     * get the data to show for requestList method
     * @return object
     * */
    public function getAllThreshold()
    {
        if(auth()->user()->hasRole('super admin'))
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

}
