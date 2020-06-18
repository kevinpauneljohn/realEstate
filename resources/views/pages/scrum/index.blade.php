@extends('adminlte::page')

@section('title', 'Tasks Profile')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Tasks Profile</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Tasks</li>
            </ol>
        </div><!-- /.col -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary">
                <div class="card-header">
                    @can('add task')
                        <button type="button" class="btn btn-outline-light btn-sm" data-toggle="modal" data-target="#add-task-modal">Create Child Task</button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <section class="col-lg-2 connectedSortable ui-sortable">
            <div class="card card-purple">
                <div class="card-header">
                    <h3 class="card-title">Back Log</h3>
                </div>
            </div>

        </section>
        <section class="col-lg-2 connectedSortable ui-sortable">
            <div class="card card-yellow">
                <div class="card-header">
                    <h3 class="card-title">New</h3>
                </div>
            </div>

            <div class="card">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h3 class="card-title">
                        <i class="ion ion-clipboard mr-1"></i>
                        To Do List
                    </h3>

                </div>
                <!-- /.card-header -->
                <div class="card-body">

                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">

                </div>
            </div>
        </section>
        <section class="col-lg-2 connectedSortable ui-sortable">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">In-progress</h3>
                </div>
            </div>
        </section>
        <section class="col-lg-2 connectedSortable ui-sortable">
            <div class="card card-pink">
                <div class="card-header">
                    <h3 class="card-title">Resolved</h3>
                </div>
            </div>
        </section>
        <section class="col-lg-2 connectedSortable ui-sortable">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Closed</h3>
                </div>
            </div>
        </section>
        <section class="col-lg-2 connectedSortable ui-sortable">

        </section>
    </div>

    @can('add task')
        <!--add new roles modal-->
        <div class="modal fade" id="add-task-modal">
            <form role="form" id="task-form">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Task</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group title">
                                <label for="title">Title</label><span class="required">*</span>
                                <input type="text" name="title" id="title" class="form-control">
                            </div>
                            <div class="form-group description">
                                <label for="description">Description</label><span class="required">*</span>
                                <textarea class="form-control" id="description" name="description"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group priority">
                                        <label for="priority">Priority</label><span class="required">*</span>
                                        <select class="form-control" name="priority" id="priority">
                                            <option value=""> -- Select -- </option>
                                            @foreach($priorities as $priority)
                                                <option value="{{$priority->id}}">{{$priority->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group user">
                                        <label for="user">Collaborator</label><span class="required">*</span>
                                        <select class="form-control select2" name="user" id="user" style="width: 100%">
                                            <option value=""> -- Select -- </option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->fullname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-task-btn" value="Save">
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
    <script src="{{asset('js/contest.js')}}"></script>
    <script src="{{asset('/vendor/jquery-ui/jquery-ui.min.js')}}"></script>
    {{--        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>--}}
    {{--        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>--}}
    <script>
        $(function() {

            'use strict'

            // Make the dashboard widgets sortable Using jquery UI
            $('.connectedSortable').sortable({
                placeholder         : 'sort-highlight',
                connectWith         : '.connectedSortable',
                handle              : '.card-header, .nav-tabs',
                forcePlaceholderSize: true,
                zIndex              : 999999
            })
            $('.connectedSortable .card-header, .connectedSortable .nav-tabs-custom').css('cursor', 'move')

            // jQuery UI sortable for the todo list
            $('.todo-list').sortable({
                placeholder         : 'sort-highlight',
                handle              : '.handle',
                forcePlaceholderSize: true,
                zIndex              : 999999
            })
        });
    </script>
@stop