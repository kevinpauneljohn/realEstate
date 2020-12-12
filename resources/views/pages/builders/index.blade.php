@extends('adminlte::page')

@section('title', 'Builders')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Builders</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Builders</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                @can('add builder')
                    <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-new-builder-modal"><i class="fa fa-plus-circle"></i> Add Builder</button>
                @endcan

            </div>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table id="builder-list" class="table table-bordered table-striped" role="grid">
                        <thead>
                        <tr role="row">
                            <th>Name</th>
                            <th>Address</th>
                            <th>Projects</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Projects</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @can('add builder')
        <!--add new builder modal-->
        <div class="modal fade" id="add-new-builder-modal">
            <form role="form" id="add-builder-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Builder</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                {{--First Column--}}
                                <div class="col-lg-6">
                                    <div class="form-group name">
                                        <label for="name">Builder Name</label>
                                        <input type="text" name="name" class="form-control" id="name">
                                    </div>
                                    <div class="form-group address">
                                        <label for="address">Address</label>
                                        <textarea class="form-control" name="address" id="address"></textarea>
                                    </div>
                                    <div class="form-group description">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control" placeholder="Place some text here"></textarea>
                                    </div>
                                </div>
                                {{--End of First Column--}}
                                {{--Second Column--}}
                                <div class="col-lg-6">
                                    <div class="form-group remarks">
                                        <label for="remarks">Remarks</label>
                                        <textarea name="remarks" id="remarks" class="form-control" data-min-height="150" placeholder="Place some text here"></textarea>
                                    </div>
                                </div>
                                {{--End of Second Column--}}
                            </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary builder-form-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new builder modal-->
    @endcan

    @can('edit builder')
        <!--edit builder modal-->
        <div class="modal fade" id="edit-builder-modal">
            <form role="form" id="edit-builder-form" class="form-submit">
                @csrf
                @method('PUT')
{{--                <input type="hidden" name="updateProjectId" id="updateProjectId">--}}
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Builder</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                {{--First Column--}}
                                <div class="col-lg-6">
                                    <div class="form-group edit_name">
                                        <label for="edit_name">Builder Name</label>
                                        <input type="text" name="edit_name" class="form-control" id="edit_name">
                                    </div>
                                    <div class="form-group edit_address">
                                        <label for="edit_address">Address</label>
                                        <textarea class="form-control" name="edit_address" id="edit_address"></textarea>
                                    </div>
                                    <div class="form-group edit_description">
                                        <label for="edit_description">Description</label>
                                        <textarea name="description" id="edit_description" class="form-control" placeholder="Place some text here"></textarea>
                                    </div>
                                </div>
                                {{--End of First Column--}}
                                {{--Second Column--}}
                                <div class="col-lg-6">
                                    <div class="form-group edit_remarks">
                                        <label for="edit_remarks">Remarks</label>
                                        <textarea name="edit_remarks" id="edit_remarks" class="form-control" data-min-height="150" placeholder="Place some text here"></textarea>
                                    </div>
                                </div>
                                {{--End of Second Column--}}
                            </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary builder-form-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add builder modal-->
    @endcan

@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.css')}}">
    <style type="text/css">

    </style>
@stop

@section('js')
    @can('view user')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('js/builder.js')}}"></script>
        <!-- Summernote -->
        <script src="{{asset('vendor/summernote/summernote-bs4.min.js')}}"></script>
        <script>
            // $(function () {
            //     // Summernote
            //     $('.textarea, .textarea2').summernote({
            //         toolbar: [
            //             ['style', ['style']],
            //             ['font', ['bold', 'underline', 'clear']],
            //             ['fontname', ['fontname']],
            //             ['color', ['color']],
            //             ['para', ['ul', 'ol', 'paragraph']],
            //             ['insert', ['link']],
            //             ['height', ['height']],
            //             ['view', ['fullscreen']],
            //         ],
            //         // lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
            //         lineHeights: ['1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
            //     });
            // })
            $(function() {
                $('#builder-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('builder.list') !!}',
                    columns: [
                        { data: 'name', name: 'name'},
                        { data: 'address', name: 'address'},
                        { data: 'project_count', name: 'project_count'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });
            //Initialize Select2 Elements
            $('.select2').select2();
        </script>
    @endcan
@stop
