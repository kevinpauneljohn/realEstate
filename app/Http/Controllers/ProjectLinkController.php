<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectLinksRequest;
use App\ProjectLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProjectLinkController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add project links'])->only(['store']);
        $this->middleware(['permission:view project links'])->only(['links']);
        $this->middleware(['permission:delete project links'])->only(['destroy']);
    }

    /**
     * @param StoreProjectLinksRequest $request
     * @return JsonResponse
     */
    public function store(StoreProjectLinksRequest $request): \Illuminate\Http\JsonResponse
    {
        return ProjectLink::create([
            'project_id' => $request->project_id,
            'url' => $request->url,
            'title' => $request->title,
            'user_id' => auth()->user()->id
        ]) ?
            response()->json(['success' => true, 'message' => 'Link successfully saved']):
            response()->json(['success' => false, 'message' => 'No link saved']);
    }

    public function links($project_id)
    {
        return DataTables::of(ProjectLink::where('project_id',$project_id)->get())
            ->editColumn('title',function($project_link){
                return '<a href="'.$project_link->url.'" target="_blank">'.ucwords(strtolower($project_link->title)).'</a>';
            })
            ->addColumn('action', function($project_link){
                $action = "";
                if(auth()->user()->can('add project links'))
                {
                    $action .= '<button class="btn btn-danger btn-xs delete-project-links float-right" title="Remove" id="'.$project_link->id.'"><i class="fa fa-trash"></i></button>';
                }
                return $action;
            })
            ->rawColumns(['action','title'])
            ->make(true);
    }

    public function destroy($project_link)
    {
        return ProjectLink::findOrFail($project_link)->delete() ?
            response()->json(['success' => true, 'message' => 'Link successfully removed']):
            response()->json(['success' => false, 'message' => 'An error occurred']);
    }
}
