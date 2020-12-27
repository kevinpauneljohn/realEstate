<?php

namespace App\Http\Controllers;

use App\Builder;
use App\User;
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

    public function show($id)
    {
        $builder = Builder::findOrFail($id);
        $members = User::role('builder member')->get();
        return view('pages.builders.profile')->with([
            'builder'   => $builder,
            'members'   => $members
        ]);
    }

    /**
     * December 13, 2020
     * @author john kevin paunel
     * get the builder model if the edit button was clicked
     * @param int $id
     * @return mixed
    */
    public function edit($id)
    {
        $builder = Builder::findOrFail($id);
        return $builder;
    }


    /**
     * Dec. 12, 2020
     * @author john kevin paunel
     * Update the builder's model
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'edit_name'      => 'required|max:150',
        ],[
            'edit_name.required' => ['Name field is required']
        ]);

        if($validator->passes())
        {
            $builder = Builder::findOrFail($id);
            $builder->name = $request->edit_name;
            $builder->address = $request->edit_address;
            $builder->description = $request->edit_description;
            $builder->remarks = $request->edit_remarks;

            if($builder->isDirty())
            {
                $builder->save();
                return response()->json(['success' => true,'message' => 'Builder successfully updated!']);
            }else{
                return response()->json(['success' => false, 'message' => 'No changes occurred']);
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
                if(auth()->user()->can('view builder'))
                {
                    $action .= '<a href="'.route('builder.show',['builder' => $builder->id]).'" class="btn btn-xs btn-success view-details" id="'.$builder->id.'" title="View Details"><i class="fa fa-eye"></i> </a>';
                }
                if(auth()->user()->can('edit builder'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-btn" id="'.$builder->id.'" data-toggle="modal" data-target="#edit-builder-modal" title="Edit Builder"><i class="fa fa-edit"></i></a>';
                }
                if(auth()->user()->can('delete lead'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-btn" id="'.$builder->id.'" title="Delete Builder"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Dec. 13, 2020
     * @author john kevin paunel
     * soft delete the builder model
     * @param int $id
     * @return mixed
    */
    public function destroy($id)
    {
        Builder::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
