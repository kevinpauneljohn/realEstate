<?php

namespace App\Http\Controllers;

use App\Lead;
use App\Notification;
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
        $notifications = Notification::where('user_id',auth()->user()->id)->orderBy('id','desc')->get();

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
            ->addColumn('notification', function ($notification)
            {
                $action = "";
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
                $action .= '<div class="btn-group">
                   
                    <button type="button" class="btn btn-default btn-sm notification-btn" data-toggle="dropdown" aria-expanded="false">
                      <i class="fas fa-ellipsis-h"></i>
                      <div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-1px, 37px, 0px);">
                        <a class="dropdown-item" href="#">Mark as read</a>
                        <a class="dropdown-item" href="#">Remove this notification</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">View</a>
                      </div>
                    </button>
                  </div>';
                return $action;
            })
            ->rawColumns(['notification','action'])
            ->make(true);
    }
}
