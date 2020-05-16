<?php

namespace App\Http\Controllers;

use App\CannedMessageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CannedMessageController extends Controller
{
    public function create()
    {
        return view('pages.canned.addCanned');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'body'  => 'required|max:8000'
        ]);

        if($validator->passes())
        {
            $canned = new CannedMessageModel();
            $canned->user_id = auth()->user()->id;
            $canned->title = $request->title;
            $canned->body = $request->body;
            $canned->status = 'Draft';
            $canned->save();

            return response()->json(['success' => true,'message' => 'Canned Message Successfully Created!']);
        }
        return response()->json($validator->errors());
    }
}
