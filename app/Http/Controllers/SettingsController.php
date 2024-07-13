<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function __construct()
    {

    }
    public function hideSensitiveContent(Request $request)
    {
        $hide = !($request->sensitive_data === "hide");
        return DB::table('settings')->where('title', 'sensitive_data')->update(['show' => $hide]) ?
            response()->json(['success' => true, 'message' => 'Settings successfully updated'], 200) :
            response()->json(['success' => false, 'message' => 'An error occurred'], 200);
    }

    public function settings()
    {
        return view('pages.settings.settings');
    }
}
