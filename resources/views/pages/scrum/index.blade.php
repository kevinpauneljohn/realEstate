@extends('adminlte::page')

@section('title', 'SCRUM')

@section('content_header')
    <h1>SCRUM</h1>
@stop

@section('content')
    <div class="row">
        <section class="col-lg-2 connectedSortable ui-sortable">
            <div class="card card-purple">
                <div class="card-header">
                    <h3 class="card-title">Back Log</h3>
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
            <div class="card card-yellow">
                <div class="card-header">
                    <h3 class="card-title">New</h3>
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
