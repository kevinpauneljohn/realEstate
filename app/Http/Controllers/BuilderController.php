<?php

namespace App\Http\Controllers;

use App\Builder;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BuilderController extends Controller
{

    /**
     * Dec. 12, 2020
     * @author john kevin paunel
     * index page for viewing builder
     * */
    public function index()
    {
        return view('pages.builders.index')->with([

        ]);
    }

    /**
     * Dec. 12, 2020
     * @author john kevin paunel
     * Save the builder created from the add builder form
     * @param Request $request
     * @return mixed
     * */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'      => 'required|max:150',
        ]);

        if($validator->passes())
        {
            $builder = new Builder();

            $builder->name = $request->name;
            $builder->address = $request->address;
            $builder->description = $request->description;
            $builder->remarks = $request->remarks;

            if($builder->save())
            {
                return response()->json([
                    'success' => true, 'message' => 'Builder Successfully Created'
                ]);
            }
        }
        return response()->json($validator->errors());
    }


    /**
     * Dec. 12, 2020
     * @author john kevin paunel
     * This will display all the builders create in a table
     *
     * */
    public function builderList()
    {
        $builders = Builder::all();
        return DataTables::of($builders)
            ->addColumn('project_count',function($builder){
                return '';
            })
            ->addColumn('action', function ($builder)
            {
                $action = "";
                if(auth()->user()->can('view lead'))
                {
                    $action .= '<button class="btn btn-xs btn-info view-details" id="'.$builder->id.'" data-toggle="modal" data-target="#lead-details" title="View Details"><i class="fa fa-info-circle"></i> </button>';
                }
                if(auth()->user()->can('view lead'))
                {
                    $action .= '<a href="'.route("leads.show",["lead" => $builder->id]).'" class="btn btn-xs btn-success view-btn" id="'.$builder->id.'" title="Manage Leads"><i class="fas fa-folder-open"></i></a>';
                }
                if(auth()->user()->can('edit lead'))
                {
                    $action .= '<a href="'.route("leads.edit",["lead" => $builder->id]).'" class="btn btn-xs btn-primary view-btn" id="'.$builder->id.'" title="Edit Leads"><i class="fa fa-edit"></i></a>';
                }
                if(auth()->user()->can('delete lead'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-lead-btn" id="'.$builder->id.'" title="Delete Leads"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
