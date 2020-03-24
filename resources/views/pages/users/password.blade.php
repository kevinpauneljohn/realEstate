<?php
@extends('adminlte::page')

@section('title', 'Profile | Change Password')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">User Profile | Agents</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a> </li>
                <li class="breadcrumb-item active">User Profile | Agents</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container-fluid">
    </div>
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
        <script src="{{asset('js/sales.js')}}"></script>
@stop
