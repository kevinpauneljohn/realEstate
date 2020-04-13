<?php

namespace App\Http\Controllers;

use App\Lead;
use App\Priority;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PriorityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.priorities.index');
    }

    public function priorityList()
    {
        $priorities = Priority::all();
        return DataTables::of($priorities)
            ->editColumn('color',function ($priority){
                $color = '<button class="btn" style="background-color:'.$priority->color.'"></button>';
                return $color;
            })
            ->addColumn('action', function ($priority)
            {
                $action = "";
                if(auth()->user()->can('view priority'))
                {
                    $action .= '<a href="'.route("leads.show",["lead" => $priority->id]).'" class="btn btn-xs btn-success view-btn" id="'.$priority->id.'"><i class="fa fa-eye"></i> View</a>';
                }
                if(auth()->user()->can('edit priority'))
                {
                    $action .= '<a href="'.route("leads.edit",["lead" => $priority->id]).'" class="btn btn-xs btn-primary view-btn" id="'.$priority->id.'"><i class="fa fa-edit"></i> Edit</a>';
                }
                if(auth()->user()->can('delete priority'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-lead-btn" id="'.$priority->id.'" data-toggle="modal" data-target="#delete-lead-modal"><i class="fa fa-trash"></i> Delete</a>';
                }
                return $action;
            })
            ->rawColumns(['action','color'])
            ->make(true);
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
        $validator = Validator::make($request->all(), [
            'name'      => 'required|unique:priorities,name',
            'description'   => 'required',
            'day'          => 'required',
            'color'         => 'required|unique:priorities,color'
        ]);

        if($validator->passes())
        {
            $priority = new Priority();
            $priority->name = $request->name;
            $priority->description = $request->description;
            $priority->days = $request->day;
            $priority->color = $request->color;

            if($priority->save())
            {
                return response()->json(['success' => true]);
            }
        }
        return response()->json($validator->errors());
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
