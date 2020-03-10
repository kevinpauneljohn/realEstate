@extends('adminlte::page')

@section('title', 'Sales')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Sales</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Sales</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            @can('add sales')
                <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-new-sales-modal"><i class="fa fa-plus-circle"></i> Add Sales</button>
            @endcan

        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="sales-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Date Reserved</th>
                        <th>Full Name</th>
                        <th>Project</th>
                        <th>Model Unit</th>
                        <th>Total Contract Price</th>
                        <th>Discount</th>
                        <th>Financing</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th>Date Reserved</th>
                        <th>Full Name</th>
                        <th>Project</th>
                        <th>Model Unit</th>
                        <th>Total Contract Price</th>
                        <th>Discount</th>
                        <th>Financing</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('add sales')
        <!--add new sales modal-->
        <div class="modal fade" id="add-new-sales-modal">
            <form role="form" id="add-sales-form">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Sales</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <!-- Date range -->
                                    <div class="form-group reservation_date">
                                        <label>Reservation Date</label><span class="required">*</span>
                                        <input type="text" name="reservation_date" id="reservation_date" class="form-control datemask" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy/mm/dd" data-mask="" im-insert="false">
                                    </div>
                                    <div class="form-group buyer">
                                        <label for="buyer">Buyer's Name</label><span class="required">*</span>
                                        <select name="buyer" id="buyer" class="form-control select2" style="width: 100%;">
                                            <option value=""> -- Select -- </option>
                                            @foreach($leads as $lead)
                                                <option value="{{$lead->id}}">{{ucfirst($lead->firstname)}} {{ucfirst($lead->lastname)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group project">
                                        <label for="project">Project</label><span class="required">*</span>
                                        <select name="project" id="project" class="form-control select2" style="width: 100%;">
                                            <option value=""> -- Select -- </option>
                                            @foreach($projects as $project)
                                                <option value="{{$project->id}}">{{$project->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group model_unit">
                                        <label for="model_unit">Model Unit</label><span class="required">*</span>
                                        <select name="model_unit" id="model_unit" class="form-control select2" style="width: 100%;">
                                            <option value=""> -- Select -- </option>

                                        </select>
                                    </div>
                                    <div class="form-group total_contract_price">
                                        <label>Total Contract Price</label>
                                        <input type="text" name="total_contract_price" id="total_contract_price" class="form-control">
                                    </div>
                                    <div class="form-group discount">
                                        <label>Discount</label>
                                        <input type="text" name="discount" id="discount" class="form-control">
                                    </div>
                                    <div class="form-group reservation_fee">
                                        <label>Reservation Fee</label>
                                        <input type="text" name="reservation_fee" id="reservation_fee" class="form-control">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group equity">
                                        <label>Equity</label>
                                        <input type="text" name="equity" id="equity" class="form-control">
                                    </div>
                                    <div class="form-group loanable_amount">
                                        <label>Loanable Amount</label>
                                        <input type="text" name="loanable_amount" id="loanable_amount" class="form-control">
                                    </div>
                                    <div class="form-group financing">
                                        <label>Financing</label>
                                        <select name="financing" id="financing" class="form-control select2" style="width: 100%;">
                                            <option value=""> -- Select -- </option>
                                            <option value="INHOUSE">INHOUSE</option>
                                            <option value="HDMF">HDMF</option>
                                            <option value="Bank">Bank</option>
                                        </select>
                                    </div>
                                    <div class="form-group terms">
                                        <label>Terms</label>
                                        <input type="text" name="terms" id="terms" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="details">Details</label>
                                        <textarea name="details" id="details" class="textarea" data-min-height="150" placeholder="Place some text here"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new user modal-->
    @endcan

    @can('edit project')
        <!--edit role modal-->
        <div class="modal fade" id="edit-project-modal">
            <form role="form" id="edit-project-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="updateProjectId" id="updateProjectId">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Project</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group edit_name">
                                <label for="edit_name">Project Name</label>
                                <input type="text" name="edit_name" class="form-control" id="edit_name">
                            </div>
                            <div class="form-group edit_address">
                                <label for="edit_address">Address</label>
                                <textarea class="form-control" name="edit_address" id="edit_address"></textarea>
                            </div>
                            <div class="form-group edit_remarks">
                                <label for="edit_remarks">Remarks</label>
                                <textarea name="edit_remarks" id="edit_remarks" class="textarea" data-min-height="150" placeholder="Place some text here"></textarea>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add user modal-->
    @endcan

    @can('delete project')
        <!--delete user-->
        <div class="modal fade" id="delete-project-modal">
            <form role="form" id="delete-project-form">
                @csrf
                @method('DELETE')
                <input type="hidden" name="deleteProjectId" id="deleteProjectId">
                <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                        <div class="modal-body">
                            <p class="delete_project">Delete Project: <span class="delete-project-name"></span></p>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-outline-light">Delete</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end delete user modal-->
    @endcan
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
        .delete_role{
            font-size: 20px;
        }
    </style>
@stop

@section('js')
    <!-- bootstrap datepicker -->
    <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('js/sales.js')}}"></script>
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
                    ['insert', ['link']],
                    ['height', ['height']],
                    ['view', ['fullscreen']],
                ],
                lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
            });
        })
    </script>
    <script>
        $(function() {
            $('#sales-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('sales.list') !!}',
                columns: [
                    { data: 'reservation_date', name: 'reservation_date'},
                    { data: 'full_name', name: 'full_name'},
                    { data: 'project', name: 'project'},
                    { data: 'model_unit', name: 'model_unit'},
                    { data: 'total_contract_price', name: 'total_contract_price'},
                    { data: 'discount', name: 'discount'},
                    { data: 'financing', name: 'financing'},
                    { data: 'status', name: 'status'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'asc']
            });
        });
        //Initialize Select2 Elements
        $('.select2').select2();
        $('#reservation_date').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
    </script>
@stop
