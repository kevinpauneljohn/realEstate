<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BuilderMemberController extends Controller
{
    private $validation;
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
}
