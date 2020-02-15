@extends('adminlte::page')

@section('title', 'Add Leads')

@section('content_header')
    <h1>Add Leads</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <div class="row">
                   <div class="col-lg-6">

                   </div>
                    <div class="col-lg-6">

                    </div>
                </div>
            </div>
        </div>
    </div>
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
                $('#users-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('users.list') !!}',
                    columns: [
                        { data: 'fullname', name: 'fullname'},
                        { data: 'username', name: 'username'},
                        { data: 'email', name: 'email'},
                        { data: 'mobileNo', name: 'mobileNo'},
                        { data: 'roles', name: 'roles'},
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
