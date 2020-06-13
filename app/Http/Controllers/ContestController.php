<?php

namespace App\Http\Controllers;

use App\Contest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ContestController extends Controller
{
    public function index()
    {
        return view('pages.contest.index');
    }

    public function contest_list()
    {
        $contests = Contest::all();

        return DataTables::of($contests)
            ->editColumn('name',function($contest){
                return ucfirst($contest->name);
            })
            ->editColumn('description',function($contest){
                return ucfirst($contest->description);
            })
            ->editColumn('active',function($contest){
                return $contest === 1 ? '<span class="text-success">Yes</span>' : '<span class="text-muted">No</span>';
            })
            ->editColumn('date_working',function($contest){
                return $contest->date_working->format('M d, Y');
            })
            ->addColumn('action',function($contest){
                $action = "";

                if(auth()->user()->can('view contest'))
                {
                    $action .= '<button type="button" class="btn btn-xs btn-success edit-rank-btn" title="View" id="'.$contest->id.'" data-toggle="modal" data-target="#edit-rank-modal"><i class="fas fa-eye"></i></button>';
                }
                if(auth()->user()->can('edit contest'))
                {
                    $action .= '<button type="button" class="btn btn-xs btn-primary edit-rank-btn" title="Edit" id="'.$contest->id.'" data-toggle="modal" data-target="#edit-rank-modal"><i class="fas fa-edit"></i></button>';
                }
                if(auth()->user()->can('delete contest'))
                {
                    $action .= '<button type="button" class="btn btn-xs btn-danger delete-rank-btn" title="Delete" id="'.$contest->id.'"><i class="fas fa-trash"></i></button>';
                }
                return $action;
            })
            ->rawColumns(['action','active'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'title'         => 'required',
            'description'   => 'required',
            'date_active'   => 'required',
            'amount'        => 'required',
            'points'        => 'required'
        ]);

        if($validation->passes())
        {
            $contest = new Contest();
            $contest->name = $request->title;
            $contest->description = $request->description;
            $contest->active = $request->is_active ? true : false;
            $contest->date_working = $request->date_active;
            $contest->extra_field = array(
                'amount'    => $request->amount,
                'points'    => $request->points,
                'item'      => $request->item ? $request->item : ""
            );

            $contest->save();
            return response()->json(['success' => true, 'message' => 'Contest successfully added!']);
        }
        return response()->json($validation->errors());

    }
}
