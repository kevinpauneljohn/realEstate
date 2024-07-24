<?php

namespace App\Http\Controllers;

use App\CommissionVoucher;
use Illuminate\Http\Request;

class CommissionVoucherController extends Controller
{
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CommissionVoucher  $commissionVoucher
     * @return \Illuminate\Http\Response
     */
    public function show(CommissionVoucher $commissionVoucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CommissionVoucher  $commissionVoucher
     * @return \Illuminate\Http\Response
     */
    public function edit(CommissionVoucher $commissionVoucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CommissionVoucher  $commissionVoucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommissionVoucher $commissionVoucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CommissionVoucher  $commissionVoucher
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommissionVoucher $commissionVoucher)
    {
        //
    }

    public function saveDriveLink($voucher_id, Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'drive_link' => ['url','nullable']
        ]);

        $commissionVoucher = CommissionVoucher::findOrFail($voucher_id);
        $commissionVoucher->drive_link = $request->drive_link;
        return $commissionVoucher->save() ?
            response()->json(['success' => true, 'message' => 'Drive successfully updated']) :
            response()->json(['success' => false, 'message' => 'An error occured while updating drive']); ;
    }
}
