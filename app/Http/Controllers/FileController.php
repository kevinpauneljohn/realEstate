<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Requests\StoreFileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add files'])->only('upload');
        $this->middleware(['permission:view files'])->only('files');
        $this->middleware(['permission:download files'])->only('download');
    }

    public function upload(StoreFileRequest $request): \Illuminate\Http\JsonResponse
    {
        $file = $request->file('file');
        $file->move(public_path('storage'),$file->getClientOriginalName());

        if(File::create([
            'project_id' => $request->project_id,
            'name' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
            'user_id' => auth()->user()->id
        ]))
        {
            return response()->json(['success' => true, 'message' => 'File uploaded successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'An error occurred while uploading the file.']);
    }

    public function download($id)
    {
        $file = File::findOrFail($id);
        return Storage::download('/public/'.$file->name);
    }

    public function files($project_id)
    {
        return DataTables::of(File::where('project_id',$project_id)->get())
            ->editColumn('user_id',function($file){
                return $file->user->fullname;
            })
            ->addColumn('action', function($file){
                $action = "";
                if(auth()->user()->can('download files'))
                {
                    $action .= '<a href="'.route('download.files',['id' => $file->id]).'" class="btn btn-info btn-sm" title="Download"><i class="fa fa-download"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
