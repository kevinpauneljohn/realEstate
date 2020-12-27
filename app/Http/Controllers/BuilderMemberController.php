<?php

namespace App\Http\Controllers;

use App\Builder;
use App\Repositories\LabelerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BuilderMemberController extends Controller
{
    private $validation,
            $labeler_repository;

    public function __construct(LabelerRepository $labelerRepository)
    {
        $this->labeler_repository = $labelerRepository;
    }


    public function addMember(Request $request)
    {
        return $this->addMemberProcess($request);
    }

    //
    private function addMemberProcess($request)
    {
        if($this->validation($request) === true)
        {
            $builder = $request->builder;
            $members = $request->members;

            foreach ($members as $member)
            {
                $this->store($builder, $member);
            }
            return ['success' => true, 'message' => 'Member successfully added!'];
        }
        return $this->validationErrors($this->validation);
    }

    //check if there are errors on the submitted field
    private function validation($request)
    {
        $validation = Validator::make($request->all(),[
            'members'   => 'required'
        ]);

        //instantiate validation method
        $this->validation = $validation;

        if($validation->passes()){
            return true;
        }
        return false;
    }

    //display the form field errors
    private function validationErrors($validation)
    {
        return $validation->errors();
    }

    ///save the members in the specific builder if there are no validation errors
    private function store($builder, $member)
    {
        DB::table('builder_user')->insert([
            'user_id'       => $member,
            'builder_id'    => $builder
        ]);
    }

    public function member($id)
    {
        $members = Builder::findOrFail($id)->users;
        return DataTables::of($members)
            ->editColumn('name', function ($member){
                return $member->fullName;
            })
            ->editColumn('role', function ($member){
                return $this->labeler_repository->role($member->getRoleNames());
            })
            ->addColumn('action', function ($member)
            {
                $action = "";
                if(auth()->user()->can('view builder member'))
                {
                    $action .= '<a href="'.route('builder.show',['builder' => $member->id]).'" class="btn btn-xs btn-success view-details" id="'.$member->id.'" title="View Details"><i class="fa fa-eye"></i> </a>';
                }
                if(auth()->user()->can('delete builder member'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-btn" id="'.$member->id.'" title="Delete Builder"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['role','action'])
            ->make(true);
    }
}
