<?php

namespace App\Http\Controllers;

use App\CannedCategory;
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
}
