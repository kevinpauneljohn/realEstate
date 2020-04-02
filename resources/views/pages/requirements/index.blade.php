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
    <div class="card">
        <div class="card-header">
            @can('add requirements')
                <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-new-requirements-modal"><i class="fa fa-plus-circle"></i> Add Requirements</button>
            @endcan

        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow-x:auto;">
                <table id="requirements-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Title</th>
                        <th>Project</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th width="15%">Title</th>
                        <th width="25%">Project</th>
                        <th width="35%">Description</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
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
                            <div class="form-group project">
                                <label>Assign Project</label><span class="required">*</span>
                                <select class="select2" name="project[]" id="project" multiple="multiple" data-placeholder="Select a project" style="width: 100%;">
                                    @foreach($projects as $project)
                                        <option value="{{$project->id}}">{{$project->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group title">
                                <label for="title">Title</label><span>(Optional)</span>
                                <input type="text" name="title" class="form-control" id="title">
                            </div>
                            <div class="form-group financing_type">
                                <label for="financing_type">Financing Type</label><span class="required">*</span>
                                <select name="financing_type" class="form-control" id="financing_type">
                                    <option value=""> -- Select -- </option>
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
                            <button type="submit" class="btn btn-primary submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Save</button>
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
                            <h4 class="modal-title">Update Project</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group edit_project">
                                <label>Assign Project</label><span class="required">*</span>
                                <select class="select2" name="edit_project[]" id="edit_project" multiple="multiple" data-placeholder="Select a project" style="width: 100%;">
                                    @foreach($projects as $project)
                                        <option value="{{$project->id}}">{{$project->name}}</option>
                                    @endforeach
                                </select>
                            </div>
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
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Save</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add user modal-->
    @endcan

    @can('delete requirements')
        <!--delete user-->
        <div class="modal fade" id="delete-requirements-modal">
            <form role="form" id="delete-requirements-form" class="form-submit">
                @csrf
                @method('DELETE')
                <input type="hidden" name="deleteRequirementsId" id="deleteRequirementsId">
                <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                        <div class="modal-body">
                            <p class="delete_project">Delete Requirements: <span class="delete-requirements-name"></span></p>
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
        <!--end delete user modal-->
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
                        { data: 'title', name: 'title'},
                        { data: 'project_id', name: 'project_id'},
                        { data: 'description', name: 'description'},
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
