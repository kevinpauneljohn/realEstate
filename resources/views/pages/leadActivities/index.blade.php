@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{$title}}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">{{$title}}</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
{{--@foreach($tasks as $task)--}}
{{--    @php--}}
{{--            $date = \Carbon\Carbon::createFromFormat('Y-m-d h:i a',$task->schedule->format('Y-m-d').' '.\Carbon\Carbon::create($task->start_date)->format('h:i a'));--}}
{{--            @endphp--}}
{{--    {{$task}}<br/>--}}
{{--    @endforeach--}}
    <div id='calendar' class="container" style="background-color: white"></div>

@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <style type="text/css">
        .fc-day-grid-event .fc-content{
            white-space: normal !important;
        }
    </style>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
@stop

@section('js')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('js/validation.js')}}"></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
        <script>
            $(document).ready(function() {
                // page is now ready, initialize the calendar...
                $('#calendar').fullCalendar({
                    // put your options and callbacks here
                    events : [
                            @foreach($tasks as $task)
                        {
                            @php
                                $date = \Carbon\Carbon::createFromFormat('Y-m-d h:i a',$task->schedule->format('Y-m-d').' '.\Carbon\Carbon::create($task->start_date)->format('h:i A'));
                            @endphp
                            title : '{{ $task->category }} - Client: {{$task->lead !== null ? $task->lead->fullname : ""}}',
                            start : '{{ $date }}',
                            url : '{{ route('leads.show', $task->lead_id) }}'
                        },
                        @endforeach
                    ]
                })
            });
        </script>
@stop
