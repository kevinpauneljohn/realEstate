@extends('adminlte::page')

@section('title', 'My Tasks')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">My Tasks</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('tasks.index')}}">Tasks</a> </li>
                <li class="breadcrumb-item active">My Tasks</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <select class="select2" name="statusChange" style="width: 150px;">
                <option value="">All</option>
                <option value="pending" @if(\Illuminate\Support\Facades\Session::get('statusMyTask') === 'pending') selected @endif>Pending</option>
                <option value="on-going" @if(\Illuminate\Support\Facades\Session::get('statusMyTask') === 'on-going') selected @endif>On-going</option>
                <option value="completed" @if(\Illuminate\Support\Facades\Session::get('statusMyTask') === 'completed') selected @endif>Completed</option>
            </select>

        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="task-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Task #</th>
                        <th>Due Date</th>
                        <th>Title</th>
                        <th>Priority</th>
                        <th>Assigned To</th>
                        <th>Creator</th>
                        <th>Date Created</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th>Task #</th>
                        <th>Due Date</th>
                        <th>Title</th>
                        <th>Priority</th>
                        <th>Assigned To</th>
                        <th>Creator</th>
                        <th>Date Created</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
@stop

@section('js')
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('/vendor/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('/vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
    <script src="{{asset('js/custom-alert.js')}}"></script>
    <script src="{{asset('js/validation.js')}}"></script>
    <script>
        $(function() {
            $('#task-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('my.tasks.list') !!}',
                columns: [
                    { data: 'id', name: 'id'},
                    { data: 'due_date', name: 'due_date'},
                    { data: 'title', name: 'title'},
                    { data: 'priority_id', name: 'priority_id'},
                    { data: 'assigned_to', name: 'assigned_to'},
                    { data: 'created_by', name: 'created_by'},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'status', name: 'status'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'desc']
            });
        });

        $('#date_active').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        }).datepicker("setDate", new Date());

        //Initialize Select2 Elements
        $('.select2').select2();

        $(document).on('change','select[name=statusChange]',function(){
            let value = this.value;
            $.ajax({
                'url' : '{{route('display.my.task.change')}}',
                'type' : 'POST',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'data' : {
                    'status' : value
                },beforeSend: function(){

                },success: function(response){
                    let table = $('#task-list').DataTable();
                    table.ajax.reload();
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        });
    </script>
@stop
