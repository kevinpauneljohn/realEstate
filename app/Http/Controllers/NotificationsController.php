<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class NotificationsController extends Controller
{
    public function notify()
    {
        Artisan::call('reminder:set');
        return Artisan::output();
    }
}
