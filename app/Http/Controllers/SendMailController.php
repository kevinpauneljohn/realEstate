<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SendMailController extends Controller
{
    public function index()
    {
        Artisan::call('queue:work --tries=3 --stop-when-empty');
    }
}
