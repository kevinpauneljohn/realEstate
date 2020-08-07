<?php

namespace App\Http\Controllers;

use App\Checklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CheckListController extends Controller
{
    /**
     * save the checklist for a specific client
     * @param Request $request
     * @return mixed
     * */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'title'         => 'required',
            'description'   => 'required'
        ]);

        if($validation->passes())
        {
            $checkList = new Checklist();
            $checkList->user_id = $request->client;
            $checkList->architect = $request->architect;
            $checkList->title = $request->title;
            $checkList->description = $request->description;
            $checkList->deadline = $request->reminder_date;
            $checkList->save();

            return response()->json(['success' => true, 'message' => 'check list successfully added!']);
        }
        return response()->json($validation->errors());
    }
}
