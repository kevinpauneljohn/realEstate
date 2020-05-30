<?php

namespace App\Http\Controllers;

use App\Lead;
use App\Notification;
use App\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Yajra\DataTables\DataTables;

class NotificationsController extends Controller
{
    public function notify()
    {
        Artisan::call('reminder:set');
        return Artisan::output();
    }

    public function index()
    {
        return view('pages.notifications.index');
    }

    public function notifications_list()
    {
        $notifications = Notification::where('user_id','=',auth()->user()->id)->where('deleted_at','=',null)->limit(100)->orderBy('id','desc')->get();

        return DataTables::of($notifications)
            ->setRowClass(function ($notifications){
                if($notifications->viewed === 1)
                {
                    return 'viewed';
                }
                elseif($notifications->viewed === 0)
                {
                    return 'not-viewed';
                }

            })
            ->addColumn('mark',function($notification){
                if($notification->viewed === 0)
                {
                    return '<input type="checkbox" class="mark-box notify-un-viewed" value="'.$notification->id.'">';
                }

            })
            ->addColumn('notification', function ($notification)
            {
                $action = '';
                $action .= '<div class="media">
                    <img src="'.asset('/images/avatar-sm.png').'" class="user-image img-circle elevation-2" height="40" style="margin:0px 10px 10px 10px;">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                    '.$notification->data->category.' to <span class="text-primary">'.Lead::find($notification->data->lead_id)->fullname.'</span>
                                    '.$notification->data->time_left.'
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> '.$notification->created_at->diffForHumans().'</p>
                                </h3>
                        </div>
                    </div>';

                return $action;
            })
            ->addColumn('action',function($notification){
                $action = '';
                $action .= '<div class="btn-group">';
                $action .= '<div class="btn-group">';
                $action .= '<button type="button" class="btn btn-default notification-btn" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></button>';
                $action .= '<div class="dropdown-menu">';
                if($notification->viewed === 0)
                {
                    $action .= '<a class="dropdown-item mark-read" href="#" id="'.$notification->id.'">Mark as read</a>';
                }
                $action .= '<a class="dropdown-item remove-notification" href="'.route('leads.show',['lead' => $notification->data->lead_id]).'">View</a>';
                $action .= '</div></div>';
                $action .= '</div>';
                return $action;
            })
            ->rawColumns(['mark','notification','action'])
            ->make(true);
    }

    public function update(Request $request,$id)
    {
        $notification = Notification::find($id);
        $notification->viewed = 1;
        if($notification->isDirty('viewed'))
        {
            $notification->save();
            return response()->json(['success' => true, 'message' => 'Reminder marked as read']);
        }
        return response()->json(['success' => false, 'message' => 'No changes occurred']);
    }

    public function markBulk(Request $request)
    {
        $ids = $request->id;

        foreach ($ids as $id)
        {
            $notification = Notification::find($id);
            $notification->viewed = true;
            if($notification->isDirty('viewed'))
            {
                $notification->save();
            }
        }
        return response()->json(['success' => true, 'message' => 'Reminder marked as read']);
    }
}
