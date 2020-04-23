<?php


namespace App\Repositories;


use App\Action;
use App\Threshold;

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
     * @param string $status
     * @param object $priority
     * */
    public function saveThreshold($type, $reason, $data, $table, $status,$priority)
    {
        $threshold = new Threshold();
        $threshold->user_id = auth()->user()->id;
        $threshold->type = $type;
        $threshold->description = $reason;
        $threshold->data = $data;
        $threshold->storage_name = $table;
        $threshold->status = $status;
        $threshold->priority_id = $priority;

        $threshold->save();
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
