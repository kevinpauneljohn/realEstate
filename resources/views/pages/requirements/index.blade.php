@extends('adminlte::page')

@section('title', 'View Requirements')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">View Requirements</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">View Requirements</li>
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
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow-x:auto;">
                <table id="sales-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th width="5%">Date Reserved</th>
                        <th>Full Name</th>
                        <th>Project</th>
                        <th>Model Unit</th>
                        <th>Total Contract Price</th>
                        <th>Discount</th>
                        <th>Financing</th>
                        <th>Rate</th>
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
                        <th>Rate</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('view sales')
        <div class="modal fade" id="view-sales-details">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Sales Detail</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="image-loader">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-5 sales-details">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                    <tr><td>Status</td><td id="sale-status"></td></tr>
                                    <tr><td>Date Of Reservation</td><td id="reservation-date"></td></tr>
                                    <tr><td>Buyer's Name</td><td id="buyer-name"></td></tr>
                                    <tr><td>Contact Number</td><td id="contact-number"></td></tr>
                                    <tr><td>Email</td><td id="email-address"></td></tr>
                                    <tr><td>Commission Rate</td><td id="commission-rate"></td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-7 sales-details">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                    <tr><td>Project</td><td id="project-name"></td></tr>
                                    <tr><td>Model Unit</td><td id="model-unit-name"></td></tr>
                                    <tr><td>Lot Area</td><td id="lot-area"></td></tr>
                                    <tr><td>Floor Area</td><td id="floor-area"></td></tr>
                                    <tr><td>Phase / Block / Lot</td><td id="location"></td></tr>
                                    <tr><td>Total Contract Price</td><td id="total-contract-price"></td></tr>
                                    <tr><td>Discount</td><td id="discount-amount"></td></tr>
                                    <tr><td>Processing Fee</td><td id="processing-fee"></td></tr>
                                    <tr><td>Reservation Fee</td><td id="reservation-fee"></td></tr>
                                    <tr><td>Equity</td><td id="equity-amount"></td></tr>
                                    <tr><td>Loanable Amount</td><td id="loanable-amount"></td></tr>
                                    <tr><td>Financing Terms</td><td id="financing-terms"></td></tr>
                                    <tr><td>Equity/Down Payment Terms</td><td id="dp-terms"></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    @endcan

    @can('add requirements')
        <!--add new sales modal-->
        <div class="modal fade" id="add-new-sales-modal">
            <form role="form" id="add-sales-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Sales</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
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
        <!--end add new user modal-->
    @endcan

    @can('edit project')
        <!--edit role modal-->
        <div class="modal fade" id="edit-project-modal">
            <form role="form" id="edit-project-form" class="form-submit">
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
                                <button type="submit" class="btn btn-primary submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Save</button>
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
            <form role="form" id="delete-project-form" class="form-submit">
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
                            <button type="submit" class="btn btn-outline-light submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Delete</button>
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
    @can('view sales')
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
                        { data: 'commission_rate', name: 'commission_rate'},
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
    @endcan
@stop
