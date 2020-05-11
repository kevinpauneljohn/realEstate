<?php

namespace App\Http\Controllers;

use App\WebsiteLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebsiteLinkController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'website_name' => 'required',
            'url'          => 'required|url'
        ]);

        if($validator->passes())
        {
            $webLinkCount = WebsiteLink::where('lead_id',$request->lead_id)->count();
            if($webLinkCount <= 10)
            {
                $webLink = new WebsiteLink();
                $webLink->lead_id = $request->lead_id;
                $webLink->website_name = $request->website_name;
                $webLink->website_url = $request->url;
                $webLink->save();
                return response()->json(['success' => true,'message' => 'Website link successfully added',
                    'websiteLink' => $webLink]);
            }
            return response()->json(['success' => false,'message' => 'You have reached the maximum website links count']);
        }
        return response()->json($validator->errors());
    }

    public function destroy($id)
    {
        $webLink = WebsiteLink::find($id);
        if($webLink->delete()){
            return response()->json(['success' => true, 'message' => 'Website link successfully deleted!']);
        }
        return response()->json(['success' => false, 'message' => 'Error occurred']);
    }
}
