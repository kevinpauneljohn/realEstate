<?php

namespace App\Http\Controllers;

use App\CannedCategory;
use App\CannedMessageModel;
use App\Repositories\CannedMessageRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CannedMessageController extends Controller
{
    public $cannedMessageRepository;

    public function __construct(CannedMessageRepository $cannedMessageRepository)
    {
        $this->cannedMessageRepository = $cannedMessageRepository;
    }

    public function create()
    {
        return view('pages.canned.addCanned')->with([
            'canned' => CannedMessageModel::all(),
            'category' => CannedCategory::all(),
            'filter' => $this->cannedMessageRepository,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'status' => 'required',
            'category' => 'required',
            'body'  => 'required|max:8000'
        ]);

        if($validator->passes())
        {
            $canned = new CannedMessageModel();
            $canned->user_id = auth()->user()->id;
            $canned->canned_categories_id = $request->category;
            $canned->title = $request->title;
            $canned->body = nl2br($request->body);
            $canned->status = $request->status;
            $canned->save();

            return response()->json(['success' => true,'message' => 'Canned Message Successfully Created!']);
        }
        return response()->json($validator->errors());
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'status' => 'required',
            'category' => 'required',
            'body'  => 'required|max:8000'
        ]);

        if($validator->passes())
        {
            $canned = CannedMessageModel::find($id);
            $canned->user_id = auth()->user()->id;
            $canned->canned_categories_id = $request->category;
            $canned->title = $request->title;
            $canned->body = nl2br($request->body);
            $canned->status = $request->status;

            if($canned->isDirty())
            {
                $canned->save();
                return response()->json(['success' => true,'message' => 'Canned Message Successfully Updated!']);
            }else{
                return response()->json(['success' => false,'message' => 'No changes occurred!']);
            }

        }
        return response()->json($validator->errors());
    }

    public function show($id)
    {
        $canned = CannedMessageModel::find($id);
        return $canned;
    }

    public function cannedMessageList()
    {
        $cannedMessages = CannedMessageModel::all();
        return DataTables::of($cannedMessages)
            ->editColumn('canned_categories_id',function($cannedMessage){
                $category = CannedCategory::find($cannedMessage->canned_categories_id);
                return $category->name;
            })
            ->editColumn('user_id',function($cannedMessage){
                $user = User::find($cannedMessage->user_id);
                return $user->fullname;
            })
            ->addColumn('action', function ($cannedMessage)
            {
                $action = "";
                if(auth()->user()->can('add canned message'))
                {
                    $action .= '<button class="btn btn-xs btn-info edit-canned" id="'.$cannedMessage->id.'" data-toggle="modal" data-target="#add-canned-message-modal" title="Edit"><i class="fa fa-edit"></i> </button>';
                }
                if(auth()->user()->can('delete canned message'))
                {
                    $action .= '<button class="btn btn-xs btn-danger delete-canned" id="'.$cannedMessage->id.'" title="Delete"><i class="fa fa-trash"></i> </button>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function destroy($id)
    {
        $canned = CannedMessageModel::find($id);
        $canned->delete();
        return response()->json(['success' => true, 'message' => 'Canned Message Successfully Deleted!']);
    }
}
