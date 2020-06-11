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
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>&#8369; {{number_format($total_sales,2)}}</h3>

                    <p>Total sales for this year</p>
                </div>
                <div class="icon">
                    <i class="fa fa-money-bill"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>&#8369; {{number_format($total_sales_this_month,2)}}</h3>

                    <p>Total sales for this month</p>
                </div>
                <div class="icon">
                    <i class="fa fa-money-bill-alt"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{$total_units_sold}}</h3>

                    <p>Total Units Sold</p>
                </div>
                <div class="icon">
                    <i class="fa fa-home"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{$total_cancelled}}</h3>

                    <p>Total Cancelled this year</p>
                </div>
                <div class="icon">
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <div class="card">
        <div class="card-header">
            @can('add sales')
                <a href="{{route('sales.create')}}" class="btn bg-gradient-primary btn-sm"><i class="fa fa-plus-circle"></i> Add Sales</a>
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
                        <th>Sale Status</th>
                        <th>Request Count</th>
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
                        <th>Sale Status</th>
                        <th width="3%">Request Count</th>
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

    @can('add sales')
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
                            <div class="row">
                                <div class="col-lg-4">
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
                                    <div class="row">
                                        <div class="form-group col-lg-6 lot_area">
                                            <label for="lot_area">Lot Area</label>
                                            <input type="text" name="lot_area" id="lot_area" class="form-control" />
                                        </div>
                                        <div class="form-group col-lg-6 floor_area">
                                            <label for="floor_area">Floor Area</label>
                                            <input type="text" name="floor_area" id="floor_area" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-4 phase">
                                            <label for="phase">Phase</label>
                                            <input type="text" name="phase" id="phase" class="form-control" />
                                        </div>
                                        <div class="form-group col-lg-4 block_number">
                                            <label for="block_number">Block</label>
                                            <input type="text" name="block_number" id="block_number" class="form-control" />
                                        </div>
                                        <div class="form-group col-lg-4 lot_number">
                                            <label for="lot_number">Lot</label>
                                            <input type="text" name="lot_number" id="lot_number" class="form-control" />
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group total_contract_price">
                                        <label>Total Contract Price</label>
                                        <input type="number" name="total_contract_price" id="total_contract_price" class="form-control">
                                    </div>
                                    <div class="form-group discount">
                                        <label>Discount</label>
                                        <input type="number" name="discount" id="discount" class="form-control" min="0" value="0">
                                    </div>
                                    <div class="form-group processing_fee">
                                        <label for="processing_fee">Processing Fee</label>
                                        <input type="number" name="processing_fee" id="processing_fee" class="form-control" min="0" value="0">
                                    </div>
                                    <div class="form-group reservation_fee">
                                        <label>Reservation Fee</label>
                                        <input type="number" name="reservation_fee" id="reservation_fee" class="form-control" min="0" value="0">
                                    </div>
                                    <div class="form-group equity">
                                        <label>Equity/Down Payment</label>
                                        <input type="number" name="equity" id="equity" class="form-control" min="0" value="0">
                                    </div>
                                    <div class="form-group loanable_amount">
                                        <label>Loanable Amount</label>
                                        <input type="number" name="loanable_amount" id="loanable_amount" class="form-control" min="0" value="0">
                                    </div>

                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group financing">
                                        <label>Financing</label>
                                        <select name="financing" id="financing" class="form-control select2" style="width: 100%;">
                                            <option value=""> -- Select -- </option>
                                            <option value="Cash">Cash</option>
                                            <option value="INHOUSE">INHOUSE</option>
                                            <option value="HDMF">HDMF</option>
                                            <option value="Bank">Bank</option>
                                        </select>
                                    </div>
                                    <div class="form-group dp_terms">
                                        <label for="dp_terms">Equity / Down Payment Terms</label>
                                        <input type="text" name="dp_terms" id="dp_terms" class="form-control">
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

    @can('edit sales')
        <div class="modal fade" id="edit-sales-modal">
            <form role="form" id="edit-sales-form" class="form-submit">
                @csrf
                @method('PUT')
                <input type="hidden" name="updateSalesId" id="updateSalesId">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Sales</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <!-- Date range -->
                                    <div class="form-group edit_reservation_date">
                                        <label>Reservation Date</label><span class="required">*</span>
                                        <input type="text" name="edit_reservation_date" id="edit_reservation_date" class="form-control datemask" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy/mm/dd" data-mask="" im-insert="false">
                                    </div>
                                    <div class="form-group edit_buyer">
                                        <label for="edit_buyer">Buyer's Name</label><span class="required">*</span> <i class="fas fa-question-circle" data-toggle="tooltip" title="Requires Admin approval to reflect the changes"></i>
                                        <select name="edit_buyer" id="edit_buyer" class="form-control select2" style="width: 100%;">
                                            <option value=""> -- Select -- </option>
                                            @foreach($leads as $lead)
                                                <option value="{{$lead->id}}">{{ucfirst($lead->firstname)}} {{ucfirst($lead->lastname)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group edit_project">
                                        <label for="edit_project">Project</label><span class="required">*</span> <i class="fas fa-question-circle" data-toggle="tooltip" title="Requires Admin approval to reflect the changes"></i>
                                        <select name="edit_project" id="edit_project" class="form-control select2" style="width: 100%;">
                                            <option value=""> -- Select -- </option>
                                            @foreach($projects as $project)
                                                <option value="{{$project->id}}">{{$project->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group edit_model_unit">
                                        <label for="edit_model_unit">Model Unit</label><span class="required">*</span> <i class="fas fa-question-circle" data-toggle="tooltip" title="Requires Admin approval to reflect the changes"></i>
                                        <select name="edit_model_unit" id="edit_model_unit" class="form-control" style="width: 100%;">

                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6 edit_lot_area">
                                            <label for="edit_lot_area">Lot Area</label>
                                            <input type="text" name="edit_lot_area" id="edit_lot_area" class="form-control" />
                                        </div>
                                        <div class="form-group col-lg-6 edit_floor_area">
                                            <label for="edit_floor_area">Floor Area</label>
                                            <input type="text" name="edit_floor_area" id="edit_floor_area" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-4 edit_phase">
                                            <label for="edit_phase">Phase</label>
                                            <input type="text" name="edit_phase" id="edit_phase" class="form-control" />
                                        </div>
                                        <div class="form-group col-lg-4 edit_block_number">
                                            <label for="edit_block_number">Block</label>
                                            <input type="text" name="edit_block_number" id="edit_block_number" class="form-control" />
                                        </div>
                                        <div class="form-group col-lg-4 edit_lot_number">
                                            <label for="edit_lot_number">Lot</label>
                                            <input type="text" name="edit_lot_number" id="edit_lot_number" class="form-control" />
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group edit_total_contract_price">
                                        <label>Total Contract Price</label><span class="required">*</span> <i class="fas fa-question-circle" data-toggle="tooltip" title="Requires Admin approval to reflect the changes"></i>
                                        <input type="number" name="edit_total_contract_price" id="edit_total_contract_price" class="form-control">
                                    </div>
                                    <div class="form-group edit_discount">
                                        <label>Discount</label> <i class="fas fa-question-circle" data-toggle="tooltip" title="Requires Admin approval to reflect the changes"></i>
                                        <input type="number" name="edit_discount" id="edit_discount" class="form-control">
                                    </div>
                                    <div class="form-group edit_processing_fee">
                                        <label for="edit_processing_fee">Processing Fee</label>
                                        <input type="number" name="edit_processing_fee" id="edit_processing_fee" class="form-control">
                                    </div>
                                    <div class="form-group edit_reservation_fee">
                                        <label>Reservation Fee</label>
                                        <input type="number" name="edit_reservation_fee" id="edit_reservation_fee" class="form-control">
                                    </div>
                                    <div class="form-group edit_equity">
                                        <label>Equity/Down Payment</label>
                                        <input type="number" name="edit_equity" id="edit_equity" class="form-control">
                                    </div>
                                    <div class="form-group edit_loanable_amount">
                                        <label>Loanable Amount</label>
                                        <input type="number" name="edit_loanable_amount" id="edit_loanable_amount" class="form-control">
                                    </div>

                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group edit_financing">
                                        <label>Financing</label> <i class="fas fa-question-circle" data-toggle="tooltip" title="Requires Admin approval to reflect the changes"></i>
                                        <select name="edit_financing" id="edit_financing" class="form-control select2" style="width: 100%;">
                                            <option value=""> -- Select -- </option>
                                            <option value="Cash">Cash</option>
                                            <option value="INHOUSE">INHOUSE</option>
                                            <option value="HDMF">HDMF</option>
                                            <option value="Bank">Bank</option>
                                        </select>
                                    </div>
                                    <div class="form-group edit_dp_terms">
                                        <label for="edit_dp_terms">Equity / Down Payment Terms</label>
                                        <input type="text" name="edit_dp_terms" id="edit_dp_terms" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_details">Details</label>
                                        <textarea name="edit_details" id="edit_details" class="textarea" data-min-height="150" placeholder="Place some text here"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group update_reason">
                                        <label for="update_reason">Reason</label><span class="required">*</span> <span class="text-muted">(Please provide a valid reason to make sure the admin will approve your request)</span>
                                        <textarea class="form-control" name="update_reason" id="update_reason">@if(auth()->user()->hasRole('super admin')) Proceed @endif</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-form-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
    @endcan


    @can('edit sales')
        <!--update sale status-->
        <div class="modal fade" id="update-sale-status">
            <form role="form" id="edit-status-form" class="form-submit">
                @csrf
                @method('PUT')
                <input type="hidden" name="updateSaleId" id="updateSaleId">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Sale Status</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group status">
                                <label for="status">Sale Status</label><span class="required">*</span>
                                <select name="status" id="status" class="select2 form-control" style="width: 100%;">
                                    <option value=""> -- Select -- </option>
                                    <option value="reserved">Reserved</option>
                                    <option value="cancelled">Cancelled</option>
                                    <option value="paid">Paid</option>
                                </select>
                            </div>
                            <div class="form-group reason">
                                <label for="reason">Reason</label>
                                <textarea class="form-control" name="reason" id="reason">@role('super admin') proceed @endrole</textarea>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary submit-form-btn" id="status-submit-btn"><i class="spinner fa fa-spinner fa-spin"></i> Save</button>
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

    @can('view request')
        <!--view request-->
        <div class="modal fade" id="view-request">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Requests</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered request-ticket">
                                <tr><th>#</th><th>Request #</th><th>Status</th></tr>
                            </table>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
        </div>
        <!--end add user modal-->
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
                    { data: 'request_status', name: 'request_status'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'desc']
            });
        });
        //Initialize Select2 Elements
        $('.select2').select2();
        $('#edit_reservation_date').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });

        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    @endcan
@stop
