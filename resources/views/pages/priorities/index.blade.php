@extends('adminlte::page')

@section('title', 'Request')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Priorities</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Priorities</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            @can('add priority')
                <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-priority-modal"><i class="fa fa-plus-circle"></i> Add New</button>
            @endcan
        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="threshold-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Color</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Days</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th width="6%">Color</th>
                        <th width="25%">Name</th>
                        <th width="45%">Description</th>
                        <th>Days</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('add priority')
        <!--add new priority modal-->
        <div class="modal fade" id="add-priority-modal">
            <form role="form" id="add-priority-form" class="form-submit">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Priority</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group name">
                                <label for="name">Priority Name</label><span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name">
                            </div>
                            <div class="form-group description">
                                <label for="description">Description</label><span class="required">*</span>
                                <textarea class="form-control" name="description" id="description"></textarea>
                            </div>
                            <div class="form-group day">
                                <label for="name">Day</label><span class="required">*</span>
                                <select class="form-control select2" name="day" id="day" style="width:100%">
                                    <option value=""></option>
                                    @for($day = 1; $day <= 30; $day++)
                                        <option value="{{$day}}">{{$day}} day/s</option>
                                    @endfor
                                </select>
                            </div>
                            <!-- Color Picker -->
                            <div class="form-group color">
                                <label for="color">Color picker:</label><span class="required">*</span>
                                <input type="color" class="form-control" name="color" id="color">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Save</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new priority modal-->
    @endcan
@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
@stop

@section('js')
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
    <script src="{{asset('js/validation.js')}}"></script>
    <script src="{{asset('js/priority.js')}}"></script>
    @can('view sales')
        <script>
            $(function() {
                $('#threshold-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('priorities.list') !!}',
                    columns: [
                        { data: 'color', name: 'color'},
                        { data: 'name', name: 'name'},
                        { data: 'description', name: 'description'},
                        { data: 'days', name: 'days'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });
            //Initialize Select2 Elements
            $('.select2').select2();
            //Colorpicker
            $('.my-colorpicker1').colorpicker()
        </script>
    @endcan
@stop
