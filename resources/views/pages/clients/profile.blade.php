@extends('adminlte::page')

@section('title', 'Client Profile')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Profile</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="card card-widget widget-user">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="widget-user-header bg-info">
            <h3 class="widget-user-username">{{$client->fullname}}</h3>
        </div>
        <div class="widget-user-image">
            <img class="img-circle elevation-2" src="{{asset('images/avatar.png')}}" alt="User Avatar">
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-sm-4 border-right">
                    <div class="description-block">
                        <h5 class="description-header">Brentwoods Village</h5>
                        <span class="description-text">Project</span>
                    </div>
                    <!-- /.description-block -->
                </div>
            </div>
            <!-- /.row -->
        </div>
    </div>

    <div class="card card-primary card-outline card-tabs">
        <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="project-description-tab" data-toggle="pill" href="#project-description" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Overall Progress</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="progress-tab" data-toggle="pill" href="#progress" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Check List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="files-tab" data-toggle="pill" href="#files" role="tab" aria-controls="files" aria-selected="false">Files</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-two-tabContent">
                <div class="tab-pane fade active show" id="project-description" role="tabpanel" aria-labelledby="project-description-tab">
                    Project Description Here
                </div>
                <div class="tab-pane fade" id="progress" role="tabpanel" aria-labelledby="progress-tab">

                    <h4>House Construction Check List</h4>
                    <div class="progress">
                        <div class="progress-bar" style="width:70%">70%</div>
                    </div>

                    <div class="container">
                                @can('add checklist')
                                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#add-checklist-modal"  style="margin-top:10px;">Add Check List</button>
                                @endcan
                                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4"  style="margin-top:5px;">
                                    <table id="client-list" class="table table-hover" role="grid">
                                        <thead>
                                        <tr role="row">
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Deadline</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>

                                        <tfoot>
                                        <tr>
                                            <th>Description</th>
                                            <th>Title</th>
                                            <th>Deadline</th>
                                            <th>Action</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                    Files here
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>

    @can('add checklist')
        <!--add checklist modal-->
        <div class="modal fade" id="add-checklist-modal">
            <form role="form" id="checklist-form" class="form-submit">
                @csrf
                <input type="hidden" name="client" value="{{$client->id}}">
                <input type="hidden" name="architect" value="{{auth()->user()->id}}">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Check List</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group title">
                                <label for="title">Title</label>
                                <input type="text" name="title" id="title" class="form-control">
                            </div>
                            <div class="form-group description">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description" id="description"></textarea>
                            </div>
                            <div class="form-group reminder_date">
                                <label for="date">Date</label><span class="required">*</span>
                                <input type="text" name="reminder_date" class="form-control datemask" id="reminder_date" value="{{today()->format('Y-m-d')}}" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy/mm/dd" data-mask="" im-insert="false">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-checklist-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end checklist modal-->
    @endcan

@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.css')}}">

    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <style>
        .dataTables_wrapper {
            overflow-x: hidden;
        }
    </style>
@stop

@section('js')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('js/validation.js')}}"></script>
        <script src="{{asset('js/checklist.js')}}"></script>
        <script src="{{asset('vendor/moment/moment.min.js')}}"></script>
        <script src="{{asset('vendor/daterangepicker/daterangepicker.js')}}"></script>
        <script src="{{asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script><!-- Summernote -->
        <script src="{{asset('vendor/summernote/summernote-bs4.min.js')}}"></script>

        <!-- bootstrap datepicker -->
        <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
        <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
        <script>

            $(function () {

                $('#client-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('checklist.client',['client' => $client->id]) !!}',
                    columns: [
                        { data: 'title', name: 'title'},
                        { data: 'description', name: 'description'},
                        { data: 'deadline', name: 'deadline'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                });
            });

            $('#reminder_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            }).datepicker("setDate", new Date());
        </script>
@stop
