@extends('adminlte::page')

@section('title', 'Requirements')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Requirements</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('sales.index')}}">Sales</a></li>
                <li class="breadcrumb-item active">Requirements</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <td>Client Name:</td>
                            <td>
                                <strong>
                                    {{ucfirst($lead->firstname)}}
                                    {{ucfirst($lead->middlename)}}
                                    {{ucfirst($lead->lastname)}}
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td>Project:</td>
                            <td>
                                <strong>
                                    {{$project->name}}
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td>Model Unit</td>
                            <td>
                                <strong>
                                    {{$modelUnit->name}}
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td>Total Contract Price</td>
                            <td>
                                <strong>
                                    {{number_format($sales->total_contract_price)}}
                                </strong>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">

                </div>
                <div class="card-body">
                     @if($sales->template_id == null)
                         <form role="form">
                             @csrf
                             <div class="form-group">
                                 <label for="template">Select Requirement Template</label>
                             </div>
                         </form>
                     @endif
                </div>
            </div>
        </div>
    </div>

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
    </style>
@stop

@section('js')
    <!-- bootstrap datepicker -->
    <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    @can('view sales')
        <script src="{{asset('js/sales.js')}}"></script>
    @endcan
@stop
