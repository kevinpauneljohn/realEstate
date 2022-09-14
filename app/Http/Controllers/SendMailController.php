<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SendMailController extends Controller
{
    public function index()
    {
        \Artisan::call('queue:work --tries=3 --stop-when-empty');
    }
}
