<?php

namespace App\Http\Controllers;

use App\Documentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DocumentationController extends Controller
{

    /**
     * September 24, 2020
     * @author john kevin paunel
     * Upload the documentation needed prior to construction process
     * @param Request $request
     * @return mixed
     * */
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

    /**
     * September 24, 2020
     * @author john kevin paunel
     * display all the saved documentation file
     * */
    public function document_list($id)
    {
        $documentations = Documentation::where('user_id',$id)->get();
        return DataTables::of($documentations)
            ->addColumn('image',function($document){
                $image = '<img src="'.asset("images/".$document->filename).'" class="img-thumbnail" alt="'.$document->title.'">';
                return $image;
            })
            ->addColumn('action', function ($client)
            {
                $action = "";
                if(auth()->user()->can('view client'))
                {
                    $action .= '<a href="'.route('client.show',['client' => $client->id]).'" class="btn btn-xs btn-success edit-user-btn"><i class="fa fa-eye"></i></a>';
                }
                if(auth()->user()->can('edit client'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-client-btn" id="'.$client->id.'" data-toggle="modal" data-target="#edit-client-modal"><i class="fa fa-edit"></i></a>';
                }
                if(auth()->user()->can('delete client'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-user-btn" id="'.$client->id.'" data-toggle="modal" data-target="#delete-user-modal"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['image','action'])
            ->make(true);
    }
}
