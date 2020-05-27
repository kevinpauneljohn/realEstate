<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function calculator(Request $request)
    {
        return view('pages.calculator.calculator')->with([
            'template' => $request->template
        ]);
    }
}
