@extends('adminlte::page')

@section('title', 'Computations')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Computations</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Computations</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            @can('add computation')
                <button type="button" class="btn bg-primary btn-sm add-computation-btn" data-toggle="modal" data-target="#add-new-computation-modal">Add</button>
            @endcan

        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="computation-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Project</th>
                        <th>Model Unit</th>
                        <th>Financing</th>
                        <th>Unit Type</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th>Project</th>
                        <th>Model Unit</th>
                        <th>Financing</th>
                        <th>Unit Type</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('add computation')
        <!--add new roles modal-->
        <div class="modal fade" id="add-new-computation-modal">
            <form role="form" id="computation-form" class="form-submit">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Computation</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group project">
                                        <label for="project">Project</label><span class="required">*</span>
                                        <select class="form-control" id="project" name="project" style="width: 100%;">
                                            <option value=""> -- Select -- </option>
                                            @foreach($projects as $project)
                                                <option value="{{$project->id}}">{{$project->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group model_unit">
                                        <label for="model_unit">Model Unit</label><span class="required">*</span>
                                        <select class="form-control" id="model_unit" name="model_unit" style="width: 100%;">
                                            <option value=""> -- Select -- </option>

                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group unit_type">
                                        <label for="unit_type">Unit Type</label>
                                        <select class="form-control" id="unit_type" name="unit_type" style="width: 100%;">
                                            <option value=""> -- Select -- </option>
                                            <option value="Inner Unit">Inner Unit</option>
                                            <option value="End Unit">End Unit</option>
                                            <option value="Corner Unit">Corner Unit</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group financing">
                                        <label for="financing">Financing</label><span class="required">*</span>
                                        <select class="form-control" id="financing" name="financing" style="width: 100%;">
                                            <option value=""> -- Select -- </option>
                                            <option value="HDMF">HDMF</option>
                                            <option value="Bank">Bank</option>
                                            <option value="Inhouse">Inhouse</option>
                                            <option value="Deferred">Deferred</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group computation">
                                <label for="computation">Computation Details</label><span class="required">*</span>
                                <textarea class="form-control" name="computation" id="computation" style="min-height: 400px;"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary save-btn" value="Save">
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
@section('right-sidebar')
    <x-custom.right-sidebar />
@stop
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{asset('/vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.css')}}">
    <style type="text/css">

    </style>
@stop

@section('js')
    @can('view lead')
        <!-- bootstrap datepicker -->
        <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
        <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
        <script src="{{asset('js/validation.js')}}"></script>
        <script src="{{asset('js/computation.js')}}"></script>
        <!-- Summernote -->
        <script src="{{asset('vendor/summernote/summernote-bs4.min.js')}}"></script>
        <script>

            $(function () {
                // Summernote
                $('.textarea').summernote({
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['view', ['fullscreen']],
                    ],
                    // lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
                    lineHeights: ['1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
                });
            })
        </script>
        <script>
            $(function() {
                $('#computation-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('computations.list') !!}',
                    columns: [
                        { data: 'project_id', name: 'project_id'},
                        { data: 'model_unit_id', name: 'model_unit_id'},
                        { data: 'financing', name: 'financing'},
                        { data: 'location_type', name: 'location_type'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'desc']
                });
            });
            //Initialize Select2 Elements
            $('.select2').select2();
            //Date picker
            $('#datepicker').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            }).datepicker("setDate", new Date());
            //Money Euro
            $('[data-mask]').inputmask()
            $('.textarea').html('{!! old('remarks') !!}');
        </script>
    @endcan
@stop
