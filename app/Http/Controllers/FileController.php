<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Requests\StoreFileRequest;
use App\Services\FilesService;
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
        $this->middleware(['permission:delete files'])->only('destroy');
    }

    public function upload(StoreFileRequest $request): \Illuminate\Http\JsonResponse
    {
        $file = $request->file('file');
        if(file_exists(public_path('storage\\'.$file->getClientOriginalName())))
        {
            return response()->json(['success' => false, 'message' => 'File already exists.'],406);
        }
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

    public function files($project_id, FilesService $filesService)
    {
        return DataTables::of(File::where('project_id',$project_id)->get())
            ->editColumn('user_id',function($file){
                return $file->user->fullname;
            })
            ->editColumn('updated_at',function($file){
                return $file->updated_at->format('m-d-Y g:i a');
            })
            ->editColumn('extension',function ($file) use ($filesService){
                return '<img src="'.asset('images/icons/'.$filesService->icons($file->extension)).'" class="img-fluid img-thumbnail" style="width:50px;">';
            })
            ->addColumn('action', function($file){
                $action = "";
                if(auth()->user()->can('download files'))
                {
                    $action .= '<a href="'.route('download.files',['id' => $file->id]).'" class="btn btn-info btn-sm mr-1 mb-1" title="Download"><i class="fa fa-download"></i></a>';
                }
                if(auth()->user()->can('delete files'))
                {
                    $action .= '<button class="btn btn-danger btn-sm delete-files mb-1" id="'.$file->id.'" title="Delete file"><i class="fa fa-trash"></i></button>';
                }
                return $action;
            })
            ->rawColumns(['action','extension'])
            ->make(true);
    }

    public function destroy(File $file)
    {
        unlink(public_path("storage\\".$file->name));
        return $file->delete() ?
        response()->json(['success' => true, 'message' => 'Files deleted.']):
        response()->json(['success' => false, 'message' => 'No files deleted.']);
    }
}
