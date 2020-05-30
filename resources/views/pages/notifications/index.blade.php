@extends('adminlte::page')

@section('title', 'Reminders')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Reminders</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Reminders</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container card" style="max-width: 800px">
        <div class="card-body">
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width:100px;">
                        <input type="checkbox" class="mark-all"> Check all
                    </td>
                    <td>
                        <select class="form-control select-action" style="width: 200px;">
                            <option value=""> -- Select Action -- </option>
                            <option value="Mark as read">Mark as read</option>
                        </select>
                    </td>
                </tr>
            </table>
            <table id="notifications-list" class="table" role="grid"></table>
        </div>
    </div>

@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <style type="text/css">
        .dataTables_paginate {
            float: left;
            margin: 0;
        }
        #notifications-list_length{
            display:none;
        }
        #notifications-list_paginate{
            margin-top:10px;
            margin-bottom:10px;
        }
        #notifications-list thead th{
            display:none;
        }
        .dataTables_wrapper {
            overflow-x: inherit;
        }
        .notification-btn{
            border:none;
            background:none;
        }
        .not-viewed{
            background:#e9f3fe;
        }
    </style>
@stop

@section('js')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('js/notification.js')}}"></script>
        <script>
            $(function() {
                $('#notifications-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('notifications.list') !!}',
                    columns: [
                        { data: 'mark', name: 'mark', orderable: false, searchable: false},
                        { data: 'notification', name: 'notification', orderable: false, searchable: false},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'desc'],
                    ordering: false,
                    searching: false,
                    paging: true,
                    info:false,
                    pageLength: 25
                });
            });
        </script>
@stop
