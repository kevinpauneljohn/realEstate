<?php

namespace App\Http\Controllers;

use App\Repositories\RepositoryInterface\ClientRequirementInterface;
use App\Template;
use Illuminate\Http\Request;

class ClientRequirementsController extends Controller
{
    private $clientRequirements;

    public function __construct(
        ClientRequirementInterface $clientRequirement
    )
    {
        $this->middleware('auth');
        $this->middleware('permission:view client requirements')->only('index');
        $this->middleware('permission:view client requirements')->only('salesRequirements');
        $this->middleware('permission:add client requirements')->only('store');
        $this->middleware('permission:edit client requirements')->only('edit');
        $this->middleware('permission:edit client requirements')->only('update');
        $this->middleware('permission:delete client requirements')->only('destroy');

        $this->clientRequirements = $clientRequirement;
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
     * check if there is saved requirements
     * @param $sales_id
     * @return bool
     */
    public function checkSalesRequirements($sales_id): bool
    {
        return collect($this->clientRequirements->viewBySales($sales_id))->count() > 0;
    }

    /**
     * @param $sales_id
     * @return mixed
     */
    public function salesRequirements($sales_id)
    {
        if($this->checkSalesRequirements($sales_id))
        {
            return $this->clientRequirements->viewBySales($sales_id);
        }
        return response(['requirements' => false, 'templates' => Template::all()]);
    }
}
