<?php

namespace App\Http\Controllers;

use App\ClientRequirement;
use App\Repositories\RepositoryInterface\ClientRequirementInterface;
use App\Requirement;
use App\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $this->middleware('permission:edit client requirements')->only('checkDocument');
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
        $validation = Validator::make($request->all(),[
            'template' => 'required'
        ]);

        if($validation->passes())
        {
            $template = $request->input('template');
            $data = [
                'sales_id'  => $request->input('sales_id'),
                'template_id'   => $template,
                'requirements'  => $this->requirementTemplate($template)
//                'requirements'  => json_encode($this->requirementTemplate($template))
            ];
            $client = $this->clientRequirements->save(new ClientRequirement(),$data);
            return response([
                'success' => true,
                'message' => 'Requirements successfully added!',
                'requirements' => $client->requirements,
//                'requirements' => json_decode($client->requirements),
                'title' => Template::findOrFail($client->template_id)->name
            ]);
        }
        return response($validation->errors(),403);
    }

    /**
     * @param $template_id
     * @return array
     */
    public function requirementTemplate($template_id): array
    {
        $templates = Requirement::where('template_id',$template_id)->get();
        $requirements = array();

        foreach ($templates as $key => $value)
        {
            $collection = collect($value);

            $merged = $collection->merge(['exists' => false]);

            $requirements[$key] = $merged->all();
        }
        return $requirements;
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
        $client = $this->clientRequirements->viewSpecifiedSale($sales_id);
        if($this->checkSalesRequirements($sales_id))
        {
            return response([
                'success' => true,
                'requirements' => $client->requirements,
//                'requirements' => json_decode($client->requirements),
                'title' => Template::find($client->template_id)->name
            ]);
        }
        return response(['requirements' => false, 'templates' => Template::all()]);
    }

    /**
     * update the document availability
     * @param Request $request
     * @return
     */
    public function checkDocument(Request $request)
    {
        $collection = collect(\App\ClientRequirement::where('sales_id',$request->input('sales_id'))->first()->requirements);

        $data = array();
        foreach ($collection as $key => $value){
            if($value['id'] === (int)$request->input('id'))
            {
                $value['exists'] = (boolean)$request->input('exists');
            }
            $data[$key] = $value;
        }

        return ClientRequirement::where('sales_id',$request->input('sales_id'))->update(['requirements' => collect($data)->toJson()]);
    }
}
