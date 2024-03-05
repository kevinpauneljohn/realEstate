<?php

namespace App\Http\Controllers;

use App\Contest;
use App\Http\Requests\ContestRequest;
use App\Rank;
use App\Services\ContestService;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ContestController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','permission:view contest'])->only(['index','contest_list','joinUserToContest']);
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
            ->editColumn('active',function($contest){
                return $contest->active === 1 ? '<span class="text-success">Yes</span>' : '<span class="text-muted">No</span>';
            })
            ->editColumn('date_working',function($contest){
                return $contest->date_working->format('M d, Y');
            })
            ->addColumn('rank',function($contest){
                $ranks = DB::table('contest_rank')->where('contest_id',$contest->id)->get();
                $rankName = '';
                foreach ($ranks as $rank){
                    $rankName .= '<span class="badge badge-success mr-1">'.Rank::findOrFail($rank->rank_id)->name.'</span>';
                }
                return $rankName;
            })
            ->addColumn('action',function($contest){
                $action = "";

                if(auth()->user()->can('view contest'))
                {
                    $action .= '<a href="'.route('contest.show',['contest' => $contest->id]).'" class="btn btn-xs btn-success view-rank-btn" title="View" id="'.$contest->id.'"><i class="fas fa-eye"></i></a>';
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
            ->rawColumns(['action','active','rank','description'])
            ->make(true);
    }

    public function show($id, ContestService $contestService)
    {
        $userId = auth()->user()->id;
        $contest = Contest::findOrFail($id);
        $userRank = $contestService->getUserRank($userId);
        $allowedToJoin = $contestService->checkUserIfAllowedToJoin($userId, $id);
        $is_user_joined_the_contest = $contestService->checkIfUserAlreadyJoinedContest($userId, $contest->id);
        return view('pages.contest.profile',
            compact('contest',
                'userRank',
                'allowedToJoin',
                'is_user_joined_the_contest')
        );
    }

    public function store(ContestRequest $request, ContestService $contestService)
    {
            $contest = new Contest();
            $contest->name = $request->title;
            $contest->description = nl2br($request->description);
            $contest->ranks = array($request->rank);
            $contest->active = $request->is_active ? true : false;
            $contest->date_working = $request->date_active;
            $contest->extra_field = array(
                'amount'    => $request->amount,
                'points'    => $request->points,
                'item'      => $request->item ? $request->item : "",
            );

            $contest->save();
            $contestService->saveRankToContest($contest->id, $request->rank);
            return response()->json(['success' => true, 'message' => 'Contest successfully added!']);
    }

    public function edit($id, ContestService $contestService)
    {
        return collect(Contest::findOrFail($id))->merge([
            'ranks' => collect($contestService->getRanksByContestId($id))->pluck(['rank_id'])
        ]);
    }

    public function update(ContestRequest $request, $id, ContestService $contestService): \Illuminate\Http\JsonResponse
    {
        $contest = Contest::findOrFail($id);
        $contest->name = $request->title;
        $contest->description = nl2br($request->description);
        $contest->ranks = array($request->rank);
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
            $contestService->removeRanksByContestId($contest->id);
            $contestService->saveRankToContest($contest->id, $request->rank);
            return response()->json(['success' => true, 'message' => 'Contest successfully updated!']);
        }
        return response()->json(['success' => false, 'message' => 'No changes made!']);

    }

    public function joinUserToContest($contest_id, ContestService $contestService): \Illuminate\Http\JsonResponse
    {
        return $contestService->joinContest($contest_id, auth()->user()->id) ?
            response()->json(['success' => true, 'message' =>'User successfully joined']) :
            response()->json(['success' => false, 'message' =>'User not allowed to join']);
    }
}
