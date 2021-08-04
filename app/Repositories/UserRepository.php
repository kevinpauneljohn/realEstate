<?php


namespace App\Repositories;


use App\User;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class UserRepository
{
    /**
     *
     * */
    public function getUserById($id)
    {
        return User::findOrFail($id);
    }

    public function getUsersOriginalData($data)
    {
        $table = '<table class="table table-hover table-bordered">';
        $table .= '<tr><td>Upline</td><td>'.User::find($data['upline_id'])->fullname.'</td></tr>';
        $table .= '<tr><td>First Name</td><td>'.$data['firstname'].'</td></tr>';
        $table .= '<tr><td>Middle Name</td><td>'.$data['middlename'].'</td></tr>';
        $table .= '<tr><td>Last Name</td><td>'.$data['lastname'].'</td></tr>';
        $table .= '<tr><td>Mobile No.</td><td>'.$data['mobileNo'].'</td></tr>';
        $table .= '<tr><td>Address</td><td>'.$data['address'].'</td></tr>';
        $table .= '<tr><td>Date Of Birth</td><td>'.$data['date_of_birth'].'</td></tr>';
        $table .= '<tr><td>Email</td><td>'.$data['email'].'</td></tr>';
        $table .= '<tr><td>Username</td><td>'.$data['username'].'</td></tr>';
        $table .= '<tr><td>Password</td><td>********</td></tr>';
        $table .= '<tr><td>Role</td><td>'.$data['role'].'</td></tr>';
        $table .= '</table>';

        return $table;
    }

    public function onlineWarriorActivities($id)
    {
        $user = User::findOrFail($id);
        if($user->hasRole(['online warrior']))
        {
            $activities = Activity::where('causer_id',$user->id)->limit(100)->get();
            return DataTables::of($activities)
                ->editColumn('created_at',function($activity){
                    return $activity->created_at;
                })
                ->editColumn('properties',function($activity){
                    return $activity->properties;
                })
                ->make(true);
        }
    }
}
