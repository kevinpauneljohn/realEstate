@extends('adminlte::page')

@section('title', 'Contacts')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Contacts</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Contacts</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container" style="max-width: 800px;">
        <div class="card">
            <div class="card-header">
                @can('add role')
                    <button type="button" class="btn bg-primary btn-sm" data-toggle="modal" data-target="#add-contacts-modal">Add</button>
                @endcan

            </div>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table id="contact-list" class="table table-hover" role="grid">
                        <thead>
                        <tr role="row">
                            <th>Title</th>
                            <th>Contact Person</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th>Title</th>
                            <th>Contact Person</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>


    @can('add contacts')
        <!--add contacts modal-->
        <div class="modal fade" id="add-contacts-modal">
            <form role="form" id="contact-form" class="form-submit">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Contact</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group title">
                                <label for="title">Title</label><span class="required">*</span>
                                <input type="text" name="title" class="form-control" id="title">
                            </div>
                            <div class="form-group contact_person">
                                <label for="contact_person">Contact Person</label><span class="required">*</span>
                                <input type="text" name="contact_person" class="form-control" id="contact_person">
                            </div>
                            <div class="form-group contact_details">
                                <label for="contact_details">Contact Details</label>
                                <textarea class="form-control" name="contact_details" id="contact_details" style="min-height: 150px;"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-contact-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end contacts modal-->
    @endcan

    @can('view contacts')
        <!--edit contacts modal-->
        <div class="modal fade" id="view-contacts-modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">View Contact Details</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
        </div>
        <!--end edit contacts modal-->
    @endcan

    @can('edit contacts')
        <!--edit contacts modal-->
        <div class="modal fade" id="edit-contacts-modal">
            <form role="form" id="edit-contacts-form" class="form-submit">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="updateContactId">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Contact Details</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group edit_title">
                                <label for="edit_title">Title</label><span class="required">*</span>
                                <input type="text" name="edit_title" class="form-control" id="edit_title">
                            </div>
                            <div class="form-group edit_contact_person">
                                <label for="edit_contact_person">Contact Person</label><span class="required">*</span>
                                <input type="text" name="edit_contact_person" class="form-control" id="edit_contact_person">
                            </div>
                            <div class="form-group edit_contact_details">
                                <label for="edit_contact_details">Contact Details</label>
                                <textarea class="form-control" name="edit_contact_details" id="edit_contact_details" style="min-height: 150px;"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-edit-contact-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end edit contacts modal-->
    @endcan

    @can('delete role')
        <!--delete terminal-->
        <div class="modal fade" id="delete-role-modal">
            <form role="form" id="delete-role-form" class="form-submit">
                @csrf
                @method('DELETE')
                <input type="hidden" name="deleteRoleId" id="deleteRoleId">
                <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                        <div class="modal-body">
                            <p class="delete_role">Delete Role: <span class="delete-role-name"></span></p>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-outline-light submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Delete</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end delete terminal modal-->
    @endcan
@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <style type="text/css">
        .delete_role{
            font-size: 20px;
        }
    </style>
@stop

@section('js')
    @can('view role')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('js/validation.js')}}"></script>
        <script src="{{asset('js/contact.js')}}"></script>
        <script>
            $(function() {
                $('#contact-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('contacts.list') !!}',
                    columns: [
                        { data: 'title', name: 'title'},
                        { data: 'contact_person', name: 'contact_person'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'desc']
                });
            });
        </script>
    @endcan
@stop
