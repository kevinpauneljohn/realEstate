<?php

namespace App\Http\Controllers;

use App\Finding;
use App\Http\Requests\StoreFindingRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FindingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view findings']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreFindingRequest $request)
    {
        return Finding::create([
            'commission_request_id' => $request->commission_request_id,
            'description' => $request->findings,
            'user_id' => auth()->user()->id
        ]) ? response()->json(['success' => true,'message' => 'Findings Successfully added!']) :
            response()->json(['success' => false, 'message' => 'An error occured']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Finding  $finding
     * @return \Illuminate\Http\Response
     */
    public function show(Finding $finding)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Finding  $finding
     * @return \Illuminate\Http\Response
     */
    public function edit(Finding $finding)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Finding  $finding
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Finding $finding)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Finding  $finding
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Finding $finding): \Illuminate\Http\JsonResponse
    {
        return $finding->delete() ?
            response()->json(['success' => true,'message' => 'Finding Successfully deleted!']) :
            response()->json(['success' => false,'message' => 'An error occurred!']) ;
    }

    public function findingsList($commission_request_id)
    {
        return DataTables::of(Finding::where('commission_request_id', $commission_request_id)->get())
            ->editColumn('updated_at', function ($finding) {
                return $finding->updated_at->format('m-d-Y');
            })
            ->editColumn('user_id', function ($finding) {
                return $finding->user->fullname;
            })
            ->editColumn('description', function ($finding) {
                return nl2br($finding->description);
            })
            ->addColumn('action', function ($finding) use ($commission_request_id) {
                $action = '';
                if(auth()->user()->can('delete findings'))
                {
                    $action .= '<button class="btn btn-danger btn-sm delete-findings" id="'.$finding->id.'"><i class="fa fa-trash"></i></button>';
                }

                return $action;
            })
            ->rawColumns(['action','description'])
            ->make(true);
    }

}
