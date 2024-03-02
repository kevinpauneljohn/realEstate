<?php

namespace App\Http\Controllers;

use App\Contest;
use App\Http\Requests\ContestRequest;
use App\Rank;
use Yajra\DataTables\DataTables;

class ContestController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','permission:view contest'])->only(['index','contest_list']);
        $this->middleware(['auth','permission:add contest'])->only(['store']);
        $this->middleware(['auth','permission:edit contest'])->only(['edit','update']);
    }
    public function index()
    {
        return view('pages.contest.index')->with([
            'ranks' => Rank::all()
        ]);
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
                return $contest->active === 1 ? '<span class="text-success">Yes</span>' : '<span class="text-muted">No</span>';
            })
            ->editColumn('date_working',function($contest){
                return $contest->date_working->format('M d, Y');
            })
            ->addColumn('rank',function($contest){
                return ucfirst(Rank::findOrFail($contest->extra_field->rank)->name);
            })
            ->addColumn('action',function($contest){
                $action = "";

                if(auth()->user()->can('view contest'))
                {
                    $action .= '<button type="button" class="btn btn-xs btn-success view-rank-btn" title="View" id="'.$contest->id.'" data-toggle="modal" data-target="#edit-rank-modal"><i class="fas fa-eye"></i></button>';
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

    public function store(ContestRequest $request)
    {
            $contest = new Contest();
            $contest->name = $request->title;
            $contest->description = $request->description;
            $contest->active = $request->is_active ? true : false;
            $contest->date_working = $request->date_active;
            $contest->extra_field = array(
                'amount'    => $request->amount,
                'points'    => $request->points,
                'item'      => $request->item ? $request->item : "",
                'rank'      => $request->rank,
            );

            $contest->save();
            return response()->json(['success' => true, 'message' => 'Contest successfully added!']);

    }

    public function edit($id)
    {
        return Contest::findOrFail($id);
    }

    public function update(ContestRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        $contest = Contest::findOrFail($id);
        $contest->name = $request->title;
        $contest->description = $request->description;
        $contest->active = $request->is_active ? 1 : 0;
        $contest->date_working = $request->date_active;
        $contest->extra_field = array(
            'amount'    => $request->amount,
            'points'    => $request->points,
            'item'      => $request->item ? $request->item : "",
            'rank'      => $request->rank,
        );

        if($contest->isDirty())
        {
            $contest->save();
            return response()->json(['success' => true, 'message' => 'Contest successfully updated!']);
        }
        return response()->json(['success' => false, 'message' => 'No changes made!']);

    }
}
