@extends('adminlte::page')

@section('title', 'Leads')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Leads</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Leads</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            @can('add lead')
                <a href="{{route('leads.create')}}"><button type="button" class="btn bg-gradient-primary btn-sm"><i class="fa fa-plus-circle"></i> Add New</button></a>
            @endcan

        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-schedule-modal">Create Schedule</button>
                <table id="leads-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Date Inquired</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Mobile No.</th>
                        <th>Email</th>
                        <th>Point Of Contact</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th>Date Inquired</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Mobile No.</th>
                        <th>Email</th>
                        <th>Point Of Contact</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('delete lead')
        <!--delete permission-->
        <div class="modal fade" id="delete-lead-modal">
            <form role="form" id="delete-lead-form">
                @csrf
                @method('DELETE')
                <input type="hidden" name="deleteLeadId" id="deleteLeadId">
                <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                        <div class="modal-body">
                            <p class="delete_lead">Delete Lead: <span class="delete-lead-name"></span></p>
                            <p class="lead-details"></p>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-outline-light">Delete</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end delete permission modal-->
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
    @can('view lead')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('js/user.js')}}"></script>
        <script>
            $(function() {
                $('#leads-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('leads.list') !!}',
                    columns: [
                        { data: 'date_inquired', name: 'date_inquired'},
                        { data: 'firstname', name: 'firstname'},
                        { data: 'lastname', name: 'lastname'},
                        { data: 'mobileNo', name: 'mobileNo'},
                        { data: 'email', name: 'email'},
                        { data: 'point_of_contact', name: 'point_of_contact'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'desc']
                });
            });
            //Initialize Select2 Elements
            $('.select2').select2();
        </script>
    @endcan
@stop
