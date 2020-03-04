<?php

namespace App\Http\Controllers;

use App\Lead;
use App\Project;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.leads.index');
    }

    /**
     * Feb. 18, 2020
     * @author john kevin paunel
     * display all leads
     * */
    public function lead_list()
    {
        $leads = Lead::where('user_id',auth()->user()->id)->get();
        return DataTables::of($leads)
            ->addColumn('action', function ($lead)
            {
                $action = "";
                if(auth()->user()->can('edit lead'))
                {
                    $action .= '<a href="'.route("leads.show",["lead" => $lead->id]).'" class="btn btn-xs btn-success view-btn" id="'.$lead->id.'"><i class="fa fa-eye"></i> View</a>';
                }
                if(auth()->user()->can('delete lead'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-lead-btn" id="'.$lead->id.'" data-toggle="modal" data-target="#delete-lead-modal"><i class="fa fa-trash"></i> Delete</a>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.leads.addLeads')->with([
            'projects'   => Project::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $interest = "";
        if($request->project != null)
        {
            foreach ($request->project as $project){
                $interest .= $project.",";
            }
        }


        $this->validate_field($request);
        $lead = new Lead();
        $lead->user_id = auth()->user()->id;
        $lead->date_inquired = $request->date_inquired;
        $lead->firstname = $request->firstname;
        $lead->middlename = $request->middlename;
        $lead->lastname = $request->lastname;
        $lead->address = $request->address;
        $lead->landline = $request->landline;
        $lead->mobileNo = $request->mobileNo;
        $lead->email = $request->email;
        $lead->status = $request->status;
        $lead->income_range = $request->income_range;
        $lead->point_of_contact = $request->point_of_contact;
        $lead->project = $interest;
        $lead->remarks = $request->remarks;

        if($lead->save())
        {
            return redirect(route('leads.edit',['lead'  => $lead->id]));
        }
        return back()->withErrors()->withInput();
    }

    /**
     * Feb. 17, 2020
     * @author john kevin paunel
     * Validate submitted field
     * @param object $request
     * @return mixed
     * */
    private function validate_field($request)
    {
        $request->validate([
            'date_inquired' => ['date','required'],
            'firstname'     => ['required'],
            'lastname'     => ['required'],
        ]);

        return $this;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('pages.leads.view')->with([
            'lead'  => Lead::findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('pages.leads.edit')->with([
            'lead'  => Lead::findOrFail($id),
            'projects'   => Project::all(),
        ]);
    }

    /**
     * Mar. 01, 2020
     * @author john kevin paunel
     * @param array $projectSelected
     * @param string $projects
     * add selected value to project interested
     * @return mixed
     * */
    public static function selectedProject($projectSelected, $projects)
    {
        #$projectSelected - this is the selected project from the leads
        #$projects - are available projects saved in the system

        $projectSelected = explode(',',$projectSelected);
        foreach ($projectSelected as $selected)
        {
            if($projects == $selected)
            {
                return 'selected="selected"';
            }
        }
    }

    public static function labeler($project)
    {
        $interest = explode(',',$project);
        $label = "";
        foreach ($interest as $projectSelected)
        {
            $label .= '<small class="badge badge-success">'.$projectSelected.'</small>';
        }
        return $label;
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
        $this->validate_field($request);

        $interest = "";
        foreach ($request->project as $project){
            $interest .= $project.",";
        }

        $lead = Lead::findOrFail($id);
        $lead->user_id = auth()->user()->id;
        $lead->date_inquired = $request->date_inquired;
        $lead->firstname = $request->firstname;
        $lead->middlename = $request->middlename;
        $lead->lastname = $request->lastname;
        $lead->address = $request->address;
        $lead->landline = $request->landline;
        $lead->mobileNo = $request->mobileNo;
        $lead->email = $request->email;
        $lead->status = $request->status;
        $lead->income_range = $request->income_range;
        $lead->point_of_contact = $request->point_of_contact;
        $lead->project = $interest;
        $lead->remarks = $request->remarks;

        if($lead->save())
        {
            return back()->withInput()->with(['success' => true]);
        }
        return back()->withErrors()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        if($lead->delete())
        {
            return response()->json(['success' => true]);
        }
    }
}
