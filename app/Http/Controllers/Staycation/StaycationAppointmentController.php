<?php

namespace App\Http\Controllers\Staycation;

use App\Http\Controllers\Controller;
use App\Repositories\RepositoryInterface\StaycationInterface;
use Illuminate\Http\Request;

class StaycationAppointmentController extends Controller
{
    public $staycation;
    public function __construct(StaycationInterface $staycation)
    {
        $this->middleware('auth');
        $this->middleware('permission:view staycation appointment')->only('availability');

        $this->staycation = $staycation;
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function availability(Request $request)
    {
        $schedule = explode(' - ',$request->input('schedule'));
        $availability = $this->staycation->checkAvailability($schedule[0],$schedule[1]);
        if($availability->count() > 0)
        {
            return response(['success' => true, 'bookings' => $availability->get()]);
        }
        return response(['success' => false]);
    }
}
