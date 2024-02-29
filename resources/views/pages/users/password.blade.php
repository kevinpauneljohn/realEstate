@extends('adminlte::page')

@section('title', 'Change Password')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Change Password</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Change Password</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card card-default">
                    <div class="card-body">
                        <form id="change-password" class="form-submit">
                            @csrf
                            @method('PUT')
                            <div class="form-group current_password">
                                <label for="current_password">Current Password</label><span class="required">*</span>
                                <input type="password" name="current_password" class="form-control" id="current_password">
                            </div>
                            <div class="form-group password">
                                <label for="password">New Password</label><span class="required">*</span>
                                <input type="password" name="password" class="form-control" id="password">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label><span class="required">*</span>
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                            </div>
                            <button type="submit" class="btn btn-primary submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('right-sidebar')
    <x-custom.right-sidebar />
@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <style type="text/css">
        .delete_role{
            font-size: 20px;
        }
        small{
            margin: 2px;
        }
    </style>
@stop

@section('js')
    <script src="{{asset('js/changePassword.js')}}"></script>
@stop
