<?php

namespace App\Http\Controllers;

use App\Builder;
use App\Repositories\RepositoryInterface\BuilderInterface;
use App\Repositories\RepositoryInterface\CheckCredentialInterface;
use App\Repositories\RepositoryInterface\DhgClientInterFace;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BuilderController extends Controller
{
    private $builder, $credential, $pass, $request, $user;

    public function __construct(
        BuilderInterface $builder,
        CheckCredentialInterface $checkCredential,
        DhgClientInterFace $dhgClientInterFace,
        Request $request
    )
    {
        $this->builder = $builder;
        $this->credential = $checkCredential;
        $this->request = $request;
        $this->user = $dhgClientInterFace;
    }

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
        return $this->builder->create($request->all());
    }

    public function show($id)
    {
        $builder = $this->builder->viewById($id);
        $members = $this->user->viewByRole('builder member');
        $selected = $builder['users'];

//        $data = array();
//        foreach ($members as $key => $value)
//        {
//            $data[$key] = $value['firstname'];
//        }
        return view('pages.builders.profile')->with([
            'builder'   => $builder,
            'members'   => $members,
            'selected'  => $selected
        ]);
//        return $data;
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
        return $this->builder->viewById($id);
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
        return $this->builder->updateById($request->all(),$id);
    }


    /**
     * Dec. 12, 2020
     * @author john kevin paunel
     * This will display all the builders create in a table
     *
     * */
    public function builderList()
    {
        $builders = $this->builder->viewAll();
        return DataTables::of($builders)
            ->addColumn('project_count',function($builder){
                return '';
            })
            ->addColumn('action', function ($builder)
            {
                $builder = collect($builder)->toArray();
                $action = "";
                if(auth()->user()->can('view builder'))
                {
                    $action .= '<a href="'.route('builder.show',['builder' => $builder['id']]).'" class="btn btn-xs btn-success view-details" id="'.$builder['id'].'" title="View Details"><i class="fa fa-eye"></i> </a>';
                }
                if(auth()->user()->can('edit builder'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-btn" id="'.$builder['id'].'" data-toggle="modal" data-target="#edit-builder-modal" title="Edit Builder"><i class="fa fa-edit"></i></a>';
                }
                if(auth()->user()->can('delete builder'))
                {
                    $action .= '<button type="button" value="delete-builder" class="btn btn-xs btn-danger delete-btn" id="'.$builder['id'].'" title="Delete Builder"><i class="fa fa-trash"></i></a>';
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
        //this will check first if the user requesting knows his password
        $credential = $this->credential->checkPassword(auth()->user()->username,$this->request->password);
        if($credential === true)
        {
            //if true will return the delete api call for builder
            return $this->builder->deleteById($id);
        }
        return response()->json(['success' => false, 'message' => 'Unauthorized access'],419);
    }


}
