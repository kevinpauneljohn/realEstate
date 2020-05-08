<?php

namespace App\Http\Controllers;

use App\LeadNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadNotesController extends Controller
{
    /**
     * @since May 07, 2020
     * @author john kevin paunel
     * @param Request $request
     * @return mixed
     * */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'notes'     => 'required|max:5000',
        ]);

        if($validation->passes())
        {
            $leadNote = new LeadNote();
            $leadNote->lead_id = $request->leadId;
            $leadNote->notes = nl2br($request->notes);
            $leadNote->save();
            return response()->json(['success' => true, 'message' => 'Notes successfully posted','note' => $leadNote,'count' => LeadNote::where('lead_id',$request->leadId)->count()]);
        }
        return response()->json($validation->errors());
    }

    /**
     * @since May 08, 2020
     * @author john kevin paunel
     * @param Request $request
     * @return mixed
     * */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(),[
            'note'     => 'required|max:5000',
        ]);

        if($validation->passes())
        {
            $LeadNote = LeadNote::find($id);
            $LeadNote->notes = $request->note;
            if($LeadNote->isDirty())
            {
                $LeadNote->save();
                return response()->json(['success' => true,'message' => 'Lead Note Successful updated!']);
            }
            return response()->json(['success' => false,'message' => 'No changes occurred']);
        }
        return response()->json($validation->errors());
    }
}
