@extends('adminlte::page')

@section('title', 'Add Canned Message')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Add Canned Message</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Add Canned Message</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card card-secondary">
                <div class="card-body">
                    <form role="form" class="add-canned-message">
                        @csrf
                        <div class="form-group title">
                            <label for="title">Title</label><span class="required">*</span>
                            <input type="text" name="title" class="form-control" id="title">
                        </div>
                        <div class="form-group body">
                            <label for="body">Body</label><span class="required">*</span> (Maximum of 8000 characters only)
                            <textarea name="body" class="textarea" id="body" data-min-height="150" placeholder="Place some text here"></textarea>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary submit-form-btn" style="width: 100%">
                                <i class="spinner fa fa-spinner fa-spin"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
@stop

@section('js')
    @can('add canned message')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('js/validation.js')}}"></script>
        <script src="{{asset('js/canned.js')}}"></script>
        <script src="{{asset('vendor/moment/moment.min.js')}}"></script>
        <script src="{{asset('vendor/daterangepicker/daterangepicker.js')}}"></script>
        <script src="{{asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script><!-- Summernote -->
        <script src="{{asset('vendor/summernote/summernote-bs4.min.js')}}"></script>
        <script>

            $(function () {
                // Summernote
                $('.textarea').summernote({
                    toolbar: [
                        ['font', ['bold', 'underline', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                        ['height', ['height']],
                        ['view', ['fullscreen']],
                    ],
                    lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
                });
            })
        </script>
    @endcan
@stop
