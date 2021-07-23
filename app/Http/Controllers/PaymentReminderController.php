<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class PaymentReminderController extends Controller
{
    public function __invoke()
    {
        Artisan::call('reminder:run');
        return Artisan::output();
    }
}
