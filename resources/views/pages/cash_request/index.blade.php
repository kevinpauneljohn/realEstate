@extends('adminlte::page')

@section('title', 'Cash Request')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Cash Request</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Cash Request</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container" style="max-width: 1000px;">
        <div class="card">
            <div class="card-header">
                @can('add role')
                    <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-new-role-modal"><i class="fa fa-plus-circle"></i> Add New</button>
                @endcan

            </div>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table id="cash-request-list" class="table table-bordered table-striped" role="grid">
                        <thead>
                        <tr role="row">
                            <th>Request #</th>
                            <th>Date Requested</th>
                            <th>Requester</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th>Request #</th>
                            <th>Date Requested</th>
                            <th>Requester</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@can('add role')
<!--add new roles modal-->
<div class="modal fade" id="add-new-role-modal">
    <form role="form" id="role-form" class="form-submit">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Role</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group role">
                        <label for="role">Role Name</label><span class="required">*</span>
                        <input type="text" name="role" class="form-control" id="role">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </form>
</div>
<!--end add new roles modal-->
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
@role('super admin')
<script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('js/role.js')}}"></script>
<script>
    $(function() {
        $('#cash-request-list').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('cash.list') !!}',
            columns: [
                { data: 'id', name: 'id'},
                { data: 'created_at', name: 'created_at'},
                { data: 'user_id', name: 'user_id'},
                { data: 'status', name: 'status'},
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            responsive:true,
            order:[0,'desc']
        });
    });
</script>
@endrole
@stop
