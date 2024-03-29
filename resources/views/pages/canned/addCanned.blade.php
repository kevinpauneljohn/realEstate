@extends('adminlte::page')

@section('title', 'Add Canned Message')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Canned Message Panel</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Canned Message Panel</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-9">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <button type="button" class="btn btn-default btn-sm add-canned-btn" data-toggle="modal" data-target="#add-canned-message-modal" title="Add Canned Message"><i class="fas fa-plus"></i></button>
            </div>
            <div class="card-body">
                <div class="dataTables_wrapper dt-bootstrap4" style="overflow-x:auto;margin-top:20px;">
                    <table id="canned-messages-list" class="table table-bordered table-hover" role="grid">
                        <thead>
                        <tr role="row">
                            <th>Title</th>
                            <th>Category</th>
                            <th>Created By</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Created By</th>
                            <th>Status</th>
                            <th width="20%">Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- /.card -->
        </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-secondary">
                <div class="card-header">
                    <strong style="font-size: 18px;">Category</strong>
                </div>
                <div class="card-body">
                    <form class="category-form" role="form">
                        @csrf
                        <div class="form-group category_name">
                            <label for="category_name">Name</label><span class="required">*</span>

                            <div class="input-group mb-3">
                                <input type="text" name="category_name" id="category_name" class="form-control">
                                <div class="input-group-append">
                                    <input type="submit" class="btn btn-primary category-btn" value="Save">
                                </div>
                            </div>
                        </div>
                    </form>

                        <table id="canned-category-list" class="table table-hover" role="grid">
                            <thead>
                            <tr role="row" class="category-list-head">
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                </div>
            </div>
        </div>
    </div>

    @can('add canned message')
        <div class="modal fade" id="add-canned-message-modal">
            <form role="form" class="add-canned-message" id="add-canned-message-form">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Canned Message</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group status">
                                        <label for="status">Status</label><span class="required">*</span>
                                        <select name="status" class="form-control" id="status">
                                            <option value="Publish">Publish</option>
                                            <option value="Draft">Draft</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group category">
                                        <label for="category">Category</label><span class="required">*</span>
                                        <select name="category" class="form-control canned-category" id="category">
                                            <option value=""> -- Select -- </option>
                                            @foreach($category as $cat)
                                                <option value="{{$cat->id}}">{{$cat->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group title">
                                <label for="title">Title</label><span class="required">*</span>
                                <input type="text" name="title" class="form-control" id="title">
                            </div>
                            <div class="form-group body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="body">Body</label><span class="required">*</span> (Maximum of 8000 characters only)
                                        <div class="dropdown float-right">
                                            <button type="button" class="btn btn-default btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                                                Short Codes
                                            </button>
                                            <div class="dropdown-menu" style="cursor: pointer;">
                                                @php
                                                    $shortCodes = array('{full_name}','{first_name}','{middle_name}','{last_name}','{username}','{mobile_no}','{email}','{address}');

                                                    foreach ($shortCodes as $code)
                                                    {
                                                        echo '<a class="dropdown-item" id="'.$code.'">'.$code.'</a>';
                                                    }
                                                @endphp
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <textarea name="body" class="textarea form-control" id="body" data-min-height="150" placeholder="Place some text here" style="min-height: 200px;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-form-btn" value="Save">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan

    @can('add canned message')
        <div class="modal fade" id="edit-category-modal">
            <form role="form" class="edit-category" id="edit-category-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="category_id">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Category</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group edit_category">
                                <label for="edit_category">Category</label>
                                <input type="text" name="edit_category" class="form-control" id="edit_category">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-category-btn" value="Save">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan
@stop
@section('right-sidebar')
    <x-custom.right-sidebar />
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
    <style>
        .dataTables_wrapper {
            overflow-x: hidden;
        }
        .delete-category:hover{
            color:red;
        }
        .category-list-head{
            display:none;
        }
        #canned-category-list td{
            border-top:none;
        }
        #canned-category-list td{
            padding:0px!important;
        }
    </style>
@stop

@section('js')
    @can('add canned message')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('js/validation.js')}}"></script>
        <script src="{{asset('js/canned.js')}}"></script>
        <script src="{{asset('js/cannedCategory.js')}}"></script>
        <script src="{{asset('vendor/moment/moment.min.js')}}"></script>
        <script src="{{asset('vendor/daterangepicker/daterangepicker.js')}}"></script>
        <script src="{{asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script><!-- Summernote -->
        <script src="{{asset('vendor/summernote/summernote-bs4.min.js')}}"></script>
        <script>

            $(function () {
                $('#canned-category-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('canned.category.list') !!}',
                    columns: [
                        { data: 'name', name: 'name', orderable: false, searchable: false},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'desc'],
                    ordering: false,
                    searching: false,
                    paging: false,
                    info:false
                });

                $('#canned-messages-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('canned.message.list') !!}',
                    columns: [
                        { data: 'title', name: 'title'},
                        { data: 'canned_categories_id', name: 'canned_categories_id'},
                        { data: 'user_id', name: 'user_id'},
                        { data: 'status', name: 'status',orderable: false,},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                });
            });
        </script>
    @endcan
@stop
