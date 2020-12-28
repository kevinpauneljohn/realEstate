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
            $labeler_repository,
            $member_id,
            $builder_id,
            $remove,
            $data;

    public function __construct(LabelerRepository $labelerRepository)
    {
        $this->labeler_repository = $labelerRepository;
    }


    public function addMember(Request $request)
    {
        //instantiate data variable with requests
        $this->data = $request;
        return $this->validation()->saveMember();
    }

    private function query($member_id, $builder_id)
    {
        $query = DB::table('builder_user')->where([
            ['user_id','=',$member_id],
            ['builder_id','=',$builder_id],
        ]);
        return $query;
    }

    //save the member to the builder
    private function saveMember()
    {
        if($this->validation->passes())
        {
            $builder = $this->data->builder; // consist 1 value only
            $members = $this->data->members; ///this variable is an array and may contain more than 1 value

            foreach ($members as $member)
            {
                //instantiate global member_id & builder_id
                $this->member_id = $member;
                $this->builder_id = $builder;

                //will save member if not exists in database
                if($this->checkIfMemberExists() === false)
                {
                    $this->store();
                }else{
                    return ['success' => false, 'message' => 'Member already exists!'];
                }
            }
            return ['success' => true, 'message' => 'Member successfully added!'];
        }
        return $this->validationErrors($this->validation);
    }

    //check if there are errors on the submitted field
    private function validation()
    {
        $this->validation = Validator::make($this->data->all(),[
            'members'   => 'required'
        ]);

        return $this;
    }

    //this will check if the user is already a member of a builder
    private function checkIfMemberExists()
    {
        $exists = $this->query($this->member_id, $this->builder_id)->count();
        if($exists > 0)
        {
            return true;
        }else{
            return false;
        }
    }


    //display the form field errors
    private function validationErrors($validation)
    {
        return $validation->errors();
    }

    ///save the members in the specific builder if there are no validation errors
    private function store()
    {
        DB::table('builder_user')->insert([
            'user_id'       => $this->member_id,
            'builder_id'    => $this->builder_id
        ]);
        return $this;
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
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-btn" id="'.$member->id.'_'.$member->pivot->builder_id.'" title="Delete Builder"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['role','action'])
            ->make(true);
    }

    /**
     * Dec. 27, 2020
     * @author john kevin paunel
     * Remove the member from the builder profile
     * @route builder.member.destroy
     * @param string $id
     * @return mixed
     * */
    public function destroy($id)
    {
        return $this->separate($id)->removeMember()->removeResponse();
    }

    //separate the string by "_" to get the member id and builder id
    private function separate($id)
    {
        $array = explode("_",$id);
        $this->member_id = $array[0];
        $this->builder_id = $array[1];

        return $this;
    }

    //remove the selected member id from the builder
    private function removeMember()
    {
        $this->remove = $this->query($this->member_id, $this->builder_id)->delete();

        return $this;
    }

    //will return the appropriate response depending on the action occurred
    private function removeResponse()
    {
        if($this->remove)
        {
            return response()->json(['success' => true, 'message' => 'Member successfully removed']);
        }
        return response()->json(['success' => false, 'message' => 'An error occurred']);
    }
}
