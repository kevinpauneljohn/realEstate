<?php

namespace App\Http\Controllers;

use App\Rank;
use App\Repositories\RankRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class RankController extends Controller
{
    public $rankRepository;

    public function __construct(RankRepository $rankRepository)
    {
        $this->rankRepository = $rankRepository;
    }

    public function index()
    {
        return view('pages.rank.index');
    }

    public function rank_list()
    {
        $ranks = Rank::all();

        return DataTables::of($ranks)
            ->addColumn('points',function($rank){
                $points = number_format($rank->start_points,2).' pts - '.number_format($rank->end_points,2).' pts';
                return '<span class="text-primary">'.$points.'</span>';
            })
            ->addColumn('action',function($rank){
                $action = "";

                if(auth()->user()->can('edit rank'))
                {
                    $action .= '<button type="button" class="btn btn-xs btn-primary edit-rank-btn" title="Edit" id="'.$rank->id.'" data-toggle="modal" data-target="#edit-rank-modal"><i class="fas fa-edit"></i></button>';
                }
                return $action;
            })
            ->rawColumns(['action','points'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'rank'  => 'required',
            'start_points'  => 'required',
            'end_points'  => 'required',
            'description'  => 'required',
            'time_line'  => 'required',
        ]);

        if($validator->passes())
        {
            $rank = new Rank();
            $rank->name = $request->rank;
            $rank->description = $request->description;
            $rank->start_points = $request->start_points;
            $rank->end_points = $request->end_points;
            $rank->timeline = $request->time_line;
            $rank->save();

            return response()->json(['success' => true, 'message' => 'Rank successfully added!']);
        }

        return response()->json($validator->errors());
    }

    public function getRank(Request $request)
    {
        return $this->rankRepository->getRank($request->id);
    }
}
