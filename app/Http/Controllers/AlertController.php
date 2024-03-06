<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function notifications()
    {

    }

    /**
     * Get the new notification data for the navbar notification.
     *
     * @param Request $request
     * @return array
     */
    public function getNotificationsData(Request $request)
    {

        ///admin request
        if(auth()->user()->hasAnyRole(['super admin','admin']))
        {
            $request = \App\Threshold::where([
                ['status','=','pending']
            ]);
        }else{
            $request = \App\Threshold::where([
                ['user_id','=',auth()->user()->id],
                ['status','=','pending'],
            ]);
        }

        //notification
        $notification = \App\Notification::where([
            ['user_id','=',auth()->user()->id],
            ['viewed','=',0],
            ['type','=','lead activity'],
        ]);

        //tasks
        $task = \App\Task::where('assigned_to',auth()->user()->id)
            ->where('status','!=','completed')
            ->where('status','!=','on-going')->count();

        // For the sake of simplicity, assume we have a variable called
        // $notifications with the unread notifications. Each notification
        // have the next properties:
        // icon: An icon for the notification.
        // text: A text for the notification.
        // time: The time since notification was created on the server.
        // At next, we define a hardcoded variable with the explained format,
        // but you can assume this data comes from a database query.

        $notifications = [
            [
                'icon' => 'fas fa-fw fa-list',
                'text' => ' Admin Request <span class="badge badge-danger">'.$request->count().'</span>',
                'time' => rand(0, 10) . ' minutes',
                'url' => '/thresholds',
                'count' => $request->count()
            ],
            [
                'icon' => 'fas fa-fw fa-users',
                'text' => ($notification->count() > 0) ? 'Lead Activities <span class="badge badge-danger">'.$notification->count().'</span>' : 'Lead Activities',
                'url' => '/notifications',
                'count' => $notification->count()
            ],
            [
                'icon' => 'fas fa-fw fa-tasks',
                'text' => ($task > 0) ? 'Tasks <span class="badge badge-danger">'.$task.'</span>' : 'Tasks',
                'url' => '/my-tasks',
                'count' => $task
            ],
        ];

        // Now, we create the notification dropdown main content.

        $dropdownHtml = '';

        foreach ($notifications as $key => $not) {

            if($not['count'] > 0)
            {
                $icon = "<i class='mr-2 {$not['icon']}'></i>";
                $dropdownHtml .= "<a href='".$not['url']."' class='dropdown-item'>
                            {$icon}{$not['text']}
                          </a>";
                if ($key < count($notifications) - 1) {
                    $dropdownHtml .= "<div class='dropdown-divider'></div>";
                }
            }

        }

        // Return the new notification data.

        return [
            'label'       => $request->count() + $notification->count() + $task,
            'label_color' => 'danger',
            'icon_color'  => 'dark',
            'dropdown'    => $dropdownHtml,
        ];
    }
}
