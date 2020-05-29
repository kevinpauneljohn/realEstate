<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contacts.index');
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'contact_person'   => 'required',
            'contact_details'   => 'required',
            'title'   => 'required',
        ]);

        if($validation->passes())
        {
            $contacts = new Contact();
            $contacts->title = $request->title;
            $contacts->user_id = auth()->user()->id;
            $contacts->contact_person = $request->contact_person;
            $contacts->contact_details = nl2br($request->contact_details);
            $contacts->save();
            return response()->json(['success' => true,'message' => 'Contact successfully saved']);
        }
        return response()->json($validation->errors());
    }

    public function contact_list()
    {
        $contacts = Contact::all();

        return DataTables::of($contacts)
            ->addColumn('action', function ($contact)
            {
                $action = "";
                if(auth()->user()->can('view contacts'))
                {
                    $action .= '<button class="btn btn-xs view-contacts-btn" id="'.$contact->id.'" title="View" data-target="#view-contacts-modal" data-toggle="modal"><i class="fa fa-eye"></i> </button>';
                }
                if(auth()->user()->can('edit contacts'))
                {
                    $action .= '<button class="btn btn-xs edit-contacts-btn" id="'.$contact->id.'" title="Edit" data-target="#edit-contacts-modal" data-toggle="modal"><i class="fa fa-edit"></i> </button>';
                }
                if(auth()->user()->can('delete contacts'))
                {
                    $action .= '<button class="btn btn-xs delete-contacts" id="'.$contact->id.'" title="Delete"><i class="fa fa-times-circle"></i> </button>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($id)
    {
        $contact = Contact::find($id);
        return $contact;
    }

    public function update(Request $request,$id)
    {
        $validation = Validator::make($request->all(),[
            'edit_contact_person'   => 'required',
            'edit_contact_details'   => 'required',
            'edit_title'   => 'required',
        ]);

        if($validation->passes())
        {

            $contact = Contact::find($id);
            $contact->title = $request->edit_title;
            $contact->contact_person = $request->edit_contact_person;
            $contact->contact_details = $request->edit_contact_details;
            if($contact->isDirty())
            {
                $contact->save();
                return response()->json(['success' => true, 'message' => 'Contact successfully updated!']);
            }
            return response()->json(['success' => false, 'message' => 'No changes occurred']);
        }
        return response()->json($validation->errors());
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);
        $contact->delete();

        return response()->json(['success' => true, 'message' => 'Contact successfully deleted!']);
    }
}
