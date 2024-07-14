<?php

namespace App\Http\Controllers;

use App\Finding;
use App\Http\Requests\StoreFindingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * @return \Illuminate\Http\Response
     */
    public function destroy(Finding $finding)
    {
        //
    }

}
