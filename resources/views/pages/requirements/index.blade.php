@extends('adminlte::page')

@section('title', 'View Requirements')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">View Requirements</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">View Requirements</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                @can('add requirements')
                    <button type="button" class="btn bg-primary btn-sm" data-toggle="modal" data-target="#add-new-requirements-modal">Add</button>
                @endcan

            </div>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow-x:auto;">
                    <table id="requirements-list" class="table table-hover" role="grid">
                        <thead>
                        <tr role="row">
                            <th>Title</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @can('add requirements')
        <!--add new sales modal-->
        <div class="modal fade" id="add-new-requirements-modal">
            <form role="form" id="add-requirements-form" class="form-submit">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Requirements</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group title">
                                <label for="title">Title</label><span>(Optional)</span>
                                <input type="text" name="title" class="form-control" id="title">
                            </div>
                            <div class="form-group financing_type">
                                <label for="financing_type">Financing Type</label><span class="required">*</span>
                                <select name="financing_type" class="form-control" id="financing_type">
                                    <option value=""> -- Select -- </option>
                                    <option value="For Reservation">For Reservation</option>
                                    <option value="INHOUSE">INHOUSE</option>
                                    <option value="BANK">BANK</option>
                                    <option value="HDMF">HDMF</option>
                                </select>
                            </div>
                            <div class="form-group desc-inputs">
                                <label>Description</label>
                                <div class="row row-description">
                                    <div class="col-sm-9">
                                        <input type="text" name="description[]" class="form-control description"/>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" class="btn btn-success row-description-btn" value="plus"><i class="fa fa-plus"></i></button>
                                        <button type="button" class="btn btn-danger row-description-btn" value="minus"><i class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-requirements-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new user modal-->
    @endcan

    @can('edit project')
        <!--edit role modal-->
        <div class="modal fade" id="edit-requirement-modal">
            <form role="form" id="edit-requirement-form" class="form-submit">
                @csrf
                @method('PUT')
                <input type="hidden" name="updateRequirementId" id="updateRequirementId">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Requirements</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group edit_title">
                                <label for="edit_title">Title</label><span>(Optional)</span>
                                <input type="text" name="edit_title" class="form-control" id="edit_title">
                            </div>
                            <div class="form-group edit_financing_type">
                                <label for="edit_financing_type">Financing Type</label><span class="required">*</span>
                                <select name="edit_financing_type" class="form-control" id="edit_financing_type">
                                    <option value=""> -- Select -- </option>
                                    <option value="INHOUSE">INHOUSE</option>
                                    <option value="BANK">BANK</option>
                                    <option value="HDMF">HDMF</option>
                                </select>
                            </div>
                            <div class="form-group edit-desc-inputs">
                                <label>Description</label>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="reset" class="btn btn-default">Reset</button>
                            <input type="submit" class="btn btn-primary edit-form-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add user modal-->
    @endcan
@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.css')}}">
    <style type="text/css">
        .desc-inputs .row-description, .edit-desc-inputs .edit-row-description{
            margin-top:2px!important;
        }
        .project-badge{
            margin-right:2px;
        }
    </style>
@stop

@section('js')
    <!-- bootstrap datepicker -->
    <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    @can('view sales')
        <script src="{{asset('js/requirements.js')}}"></script>
        <script>
            $(function() {
                $('#requirements-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('requirements.list') !!}',
                    columns: [
                        { data: 'name', name: 'name'},
                        { data: 'type', name: 'type'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });
            //Initialize Select2 Elements
            $('.select2').select2();
        </script>
    @endcan
@stop
