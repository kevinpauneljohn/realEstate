<?php

namespace App\Http\Controllers;

use App\Lead;
use App\ModelUnit;
use App\Project;
use App\Requirement;
use App\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class RequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.requirements.index');
    }

    public function requirements_list()
    {
        $templates = Template::all();
        return DataTables::of($templates)
            ->editColumn('description',function($template){
                $requirement = Requirement::where('template_id',$template->id)->get();
                $description = "<ul>";

                    foreach($requirement as $desc)
                    {
                        $description .= '<li>'.$desc->description.'</li>';
                    }
                    $description .= '</ul>';

                    return $description;
            })
            ->addColumn('action', function ($template)
            {
                $action = "";
                if(auth()->user()->can('view requirements'))
                {
                    $action .= '<button class="btn btn-xs btn-success view-sales-btn" id="'.$template->id.'" data-toggle="modal" data-target="#view-sales-details"><i class="fa fa-eye"></i> View</button>';
                }
                if(auth()->user()->can('edit requirements'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-btn" id="'.$template->id.'" data-toggle="modal" data-target="#edit-requirement-modal"><i class="fa fa-edit"></i> Edit</a>';
                }
                if(auth()->user()->can('delete requirements'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-requirements-btn" id="'.$template->id.'" data-toggle="modal" data-target="#delete-requirements-modal"><i class="fa fa-trash"></i> Delete</a>';
                }
                return $action;
            })
            ->rawColumns(['action','project_id','description'])
            ->make(true);
    }

    /**
     * March 03, 2020
     * @author john kevin paunel
     * @param Request $request
     * @return mixed
     * */
    public function getRequirements(Request $request)
    {
        $requirements = Requirement::where('template_id',$request->id)->get();
        $template = Template::find($request->id);
        return response()->json(['requirements' => $requirements, 'template' => $template]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title'   => ['required'],
            'financing_type'    => ['required']
        ]);

        if($validator->passes())
        {
            $template = new Template();
            $template->name = $request->title;
            $template->type = $request->financing_type;

            $template->save();


            foreach ($request->description as $desc)
            {
                $requirement = new Requirement();
                $requirement->template_id = $template->id;
                $requirement->description = $desc;
                $requirement->save();
            }
            return response()->json(['success' => true]);

        }
        return response()->json($validator->errors());
    }

    public function requiremets()
    {

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'edit_title'   => ['required'],
            'edit_financing_type'    => ['required']
        ],[
           'edit_title.required' => 'Title field is required',
           'edit_financing_type.required' => 'Financing field is required',
        ]);

        if($validator->passes())
        {

            $template = Template::findOrFail($id);
            $template->name = $request->edit_title;
            $template->type = $request->edit_financing_type;

            if($template->save())
            {
                $this->update_or_create($request, $template)->delete_requirement($request);
                return response()->json(['success' => true]);
            }

        }
        return response()->json($validator->errors());
    }

    /**
     * March 06, 2020
     * @author john kevin paunel
     * update or create a new requirement description
     * @param Request $request
     * @param object $template
     * @return mixed
     * */
    public function update_or_create($request, $template)
    {
        foreach ($request->edit_description as $key => $value)
        {
            Requirement::updateOrCreate(
                ['id' => $key],
                ['template_id' => $template->id, 'description' => $value]
            );
        }
        return $this;
    }

    /**
     * March 06, 2020
     * @author john kevin paunel
     * delete the requirement description
     * @param Request $request
     * @return mixed
     * */
    public function delete_requirement($request)
    {
        if($request->delete_requirements != null)
        {
            foreach ($request->delete_requirements as $key => $value)
            {
                $requirement = Requirement::find($value);
                $requirement->delete();
            }
        }

        return $this;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $template = Template::findOrFail($id);

        if($template->delete())
        {
            return response()->json(['success' => true]);
        }
    }
}
