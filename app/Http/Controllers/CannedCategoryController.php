<?php

namespace App\Http\Controllers;

use App\CannedCategory;
use App\CannedMessageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CannedCategoryController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'category_name' => 'required|unique:canned_categories,name'
        ]);

        if($validator->passes())
        {
            $category = new CannedCategory();
            $category->user_id = auth()->user()->id;
            $category->name = $request->category_name;
            $category->save();
            return response()->json(['success' => true,'message' => 'Category Successfully Saved!','catValue' => $category]);
        }
        return response()->json($validator->errors());
    }

    public function cannedCategoryList()
    {
        $categories = CannedCategory::all();

        return DataTables::of($categories)
            ->editColumn('name',function($category){
                $count = CannedMessageModel::where('canned_categories_id',$category->id)->count();
                return ucfirst($category->name).' ('.$count.')';

            })
            ->addColumn('action', function ($category)
            {
                $action = "";
                if(auth()->user()->can('add canned message'))
                {
                    $action .= '<button class="btn btn-xs delete-category" id="'.$category->id.' "title="Delete"><i class="fa fa-times-circle"></i> </button>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function destroy($id)
    {
        $count = CannedMessageModel::where('canned_categories_id',$id)->count();
        if($count === 0)
        {
            if($category = CannedCategory::find($id))
            {
                $category->delete();
                return response()->json(['success' => true, 'message' => 'Category successfully deleted!']);
            }
            return response()->json(['success' => false, 'message' => 'Token Mismatch','reload' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Category cannot be deleted']);
    }
}
