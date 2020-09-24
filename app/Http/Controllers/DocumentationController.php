<?php

namespace App\Http\Controllers;

use App\Documentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentationController extends Controller
{
    public function store(Request $request)
    {
        //return $request->all();
        $validator = Validator::make($request->all(),[
            'label'     => 'required',
            'document'      => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if($validator->passes())
        {

            $image = $request->file('document');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $new_name);


            $documentation = new Documentation();
            $documentation->user_id = $request->client;
            $documentation->admin_id = auth()->user()->id;
            $documentation->title = $request->label;
            $documentation->description = $request->detail;
            $documentation->filename = $new_name;

            $documentation->save();

            return response()->json(['success' => true,'message' => 'File successfully added!']);
        }
        return response()->json($validator->errors());
    }
}
