<?php

namespace App\Http\Controllers;

use App\Checklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

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

    public function check_list($id)
    {
        $checkList = Checklist::where('user_id',$id)->get();
        return DataTables::of($checkList)
            ->addColumn('action', function ($client)
            {
                $action = "";
                if(auth()->user()->can('view client'))
                {
                    $action .= '<a href="'.route('client.show',['client' => $client->id]).'" class="btn btn-xs btn-success edit-user-btn"><i class="fa fa-eye"></i></a>';
                }
                if(auth()->user()->can('edit client'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-user-btn" id="'.$client->id.'" data-toggle="modal" data-target="#edit-user-modal"><i class="fa fa-edit"></i></a>';
                }
                if(auth()->user()->can('delete client'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-user-btn" id="'.$client->id.'" data-toggle="modal" data-target="#delete-user-modal"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
