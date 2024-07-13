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
        <div class="col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Team Sales for this year {{now()->format('Y')}}</span>
                    <span class="info-box-number"><h4>&#8369; {{number_format($total_team_sales,2)}}</h4></span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-users-cog"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Team Sales for the month of {{now()->format('F')}}</span>
                    <span class="info-box-number"><h4>&#8369; {{number_format($team_sales_this_month,2)}}</h4></span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fas fa-home"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Team Units Sold</span>
                    <span class="info-box-number"><h4>{{$team_units_sold}}</h4></span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-house-damage"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Units Sold this {{now()->format('F')}}</span>
                    <span class="info-box-number"><h4>{{$team_units_sold_this_month}}</h4></span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <!-- ./col -->
    </div>
    <div class="row">
        <div class="col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-purple"><i class="fas fa-user-check"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Personal Sales this year {{now()->format('Y')}}</span>
                    <span class="info-box-number"><h4>&#8369; {{number_format($personal_sales_this_year,2)}}</h4></span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-orange"><i class="fas fa-user-check"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Personal Sales for the month of {{now()->format('F')}}</span>
                    <span class="info-box-number"><h4>&#8369; {{number_format($personal_sales_this_month,2)}}</h4></span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-fuchsia"><i class="fas fa-home"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Personal Units Sold</span>
                    <span class="info-box-number"><h4>{{$personal_units_sold}}</h4></span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-gradient-teal"><i class="fas fa-house-damage"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Personal Units Sold this {{now()->format('F')}}</span>
                    <span class="info-box-number"><h4>{{$personal_units_sold_this_month}}</h4></span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <!-- ./col -->
    </div>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-6">
                    @can('add sales')
                        <a href="{{route('sales.create')}}" class="btn bg-gradient-primary btn-sm"><i class="fa fa-plus-circle"></i> Add Sales</a>
                    @endcan
                </div>
                <div class="col-lg-6">
                    <div class="float-right">
                        <label>Rate Visibility Status :</label><br />
                        <select class="select2" name="hide_rate" id="statusChange" style="width: 200px;">
                            <option value="show" @if(\Illuminate\Support\Facades\Session::get('rate') === 'show') selected @endif>Show</option>
                            <option value="hide" @if(\Illuminate\Support\Facades\Session::get('rate') === 'hide') selected @endif>Hide</option>
                        </select>
                    </div>
                    <!-- <div class="custom-control custom-switch toggle_private float-right">
                        <input type="checkbox" name="hide_rate" class="custom-control-input" id="rate_status">
                        <label  style="cursor: pointer;" class="custom-control-label" for="rate_status">Toggle to Hide Rate Column</label>
                    </div> -->
                    <!-- <button type="button" class="btn bg-gradient-success btn-sm import-sales mr-1 float-right" data-toggle="modal" data-target="#import-sales-modal"><i class="fa fa-upload"></i> Import Sales</button>
                    <div class="modal fade" id="import-sales-modal">
                        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Import Sales</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group file">
                                            <label for="file">File</label><span class="required">*</span>
                                            <input type="file" name="file" class="form-control">
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-primary submit-task-btn" value="Save">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="card-body">
            <x-sales.date-range />
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow-x:auto;">
                <table id="sales-list" class="table table-bordered table-hover" role="grid">
                    <thead>
                    <tr role="row">
                        <th width="5%">Date Reserved</th>
                        <th>Full Name</th>
                        <th>Project</th>
                        <th>Model Unit</th>
                        <th>Total Contract Price</th>
                        <th>Financing</th>
                        <th>Rate</th>
                        <th>Sale Status</th>
                        <th>Agent</th>
                        <th>Request Count</th>
                        <th>Comm. Released</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody></tbody>
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
                                        <tr><td>Commission Rate</td><td id="commission-rate dhg-hidden"></td></tr>
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
                                            <label for="edit_phase">Phase</label> <i class="text-muted">(optional)</i>
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

                                        <select name="edit_dp_terms" id="edit_dp_terms" class="form-control">
                                            <option value=""> -- Select Terms -- </option>
                                            @for($months = 1; $months <= 60; $months++)
                                                <option value="{{$months}}"> {{$months}} month/s</option>
                                            @endfor
                                        </select>
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
                                <select name="status" id="status" class="select2 form-control select-update-status" style="width: 100%;">
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

    @can('delete sales')
        <!--view request-->

        <div class="modal fade" id="delete-sale-request">
            <form role="form" id="delete-sales-form" class="form-submit">
                @csrf
                @method('PUT')
                <input type="hidden" name="deleteSaleId" id="deleteSaleId">
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
                                <div class="col-lg-5 del-sales-details">
                                    <table class="table table-bordered table-hover">
                                        <tbody>
                                        <tr><td>Status</td><td id="sale-status-del"></td></tr>
                                        <tr><td>Date Of Reservation</td><td id="reservation-date-del"></td></tr>
                                        <tr><td>Buyer's Name</td><td id="buyer-name-del"></td></tr>
                                        <tr><td>Contact Number</td><td id="contact-number-del"></td></tr>
                                        <tr><td>Email</td><td id="email-address-del"></td></tr>
                                        <tr><td>Commission Rate</td><td id="commission-rate-del"></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-7 del-sales-details table-wrapper-scroll-y custom-scrollbar">
                                    <table class="table table-bordered table-hover">
                                        <tbody>
                                        <tr><td>Project</td><td id="project-name-del"></td></tr>
                                        <tr><td>Model Unit</td><td id="model-unit-name-del"></td></tr>
                                        <tr><td>Lot Area</td><td id="lot-area-del"></td></tr>
                                        <tr><td>Floor Area</td><td id="floor-area-del"></td></tr>
                                        <tr><td>Phase / Block / Lot</td><td id="location-del"></td></tr>
                                        <tr><td>Total Contract Price</td><td id="total-contract-price-del"></td></tr>
                                        <tr><td>Discount</td><td id="discount-amount-del"></td></tr>
                                        <tr><td>Processing Fee</td><td id="processing-fee-del"></td></tr>
                                        <tr><td>Reservation Fee</td><td id="reservation-fee-del"></td></tr>
                                        <tr><td>Equity</td><td id="equity-amount-del"></td></tr>
                                        <tr><td>Loanable Amount</td><td id="loanable-amount-del"></td></tr>
                                        <tr><td>Financing Terms</td><td id="financing-terms-del"></td></tr>
                                        <tr><td>Equity/Down Payment Terms</td><td id="dp-terms-del"></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group reason">
                                        <label for="reason"><span style="color:red;">*Reason to Delete:</span></label>
                                        <textarea class="form-control delete_reason_request_content" name="reason" id="reason" rows="6"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary submit-delete-btn" id="delete-submit-btn"><i class="spinner fa fa-spinner fa-spin"></i> Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!--end add user modal-->
    @endcan
@stop
@section('right-sidebar')
    <x-custom.right-sidebar />
@stop
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.css')}}">
    <style>
        .for-commission{
            background-color:#e0fdba;
        }
        .commission-request-pending{
            background-color: #d7fbfd;
        }
        .custom-scrollbar {
            position: relative;
            height: 220px;
            overflow: auto;
        }
        .table-wrapper-scroll-y {
            display: block;
        }
    </style>
@stop

@section('js')
    <!-- bootstrap datepicker -->
    <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('js/custom-alert.js')}}"></script>
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
                    ['height', ['height']],
                    ['view', ['fullscreen']],
                ],
                lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
            });
        })
    </script>
    <script>
        $(function() {
            let table = $('#sales-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('sales.list') !!}',
                columns: [
                    { data: 'reservation_date', name: 'reservation_date'},
                    { data: 'full_name', name: 'full_name'},
                    { data: 'project', name: 'project'},
                    { data: 'model_unit', name: 'model_unit'},
                    { data: 'total_contract_price', name: 'total_contract_price'},
                    { data: 'financing', name: 'financing'},
                    { data: 'commission_rate', name: 'commission_rate'},
                    { data: 'status', name: 'status'},
                    { data: 'agent', name: 'agent'},
                    { data: 'request_status', name: 'request_status'},
                    { data: 'comm_released', name: 'comm_released'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "createdRow": function( row, data, dataIndex ) {
                    if (
                        data['rate_status'] == 'hide'
                    ) {
                        table.columns([6]).visible(false);
                    } else {
                        table.columns([6]).visible(true);
                    }
                },
                responsive:true,
                order:[0,'desc'],
                pageLength: 10,
                drawCallback: function(row){
                    let sale = row.json
                    let rankings = '';
                    $('#sales-list').find('tbody')
                        .append('<tr class="sales-info-bg"><td colspan="12" style="font-size: 20pt"><span class="text-muted">Total Sales: </span>'+sale.total_sales+'</td></tr>')
                    // $.each(sale.leaderboard, function(key, value){
                    //     $('#sales-list').find('tbody')
                    //         .append('<tr class="leader-bg"><td colspan="11" style="font-size: 20pt"><span class="text-yellow"><i class="fa fa-trophy" aria-hidden="true"></i></span><span class="text-primary">#'+(parseInt(key)+1)+' </span> - <span class="text-success">'+value.firstname+' '+value.lastname+'</span> = <span>'+value.sales+'</span></td></tr>')
                    // });
                    $.each(sale.leaderboard, function(key, value){
                        rankings +='<td colspan="4" class="text-center"><div class="text-yellow"><i class="fa fa-trophy" aria-hidden="true"></i><span class="text-primary">#'+(parseInt(key)+1)+' </span></div><div class="text-success">'+value.firstname+' '+value.lastname+'</div><div>'+value.sales+'</div></td>'
                    });

                    $('#sales-list').find('tbody')
                        .append('<tr class="leader-bg" style="font-size: 15pt;"><td colspan="12"><h3 class="text-center">Leaderboard</h3><table><tr>'+rankings+'</tr></table></td></tr>');
                }
            });

        });

        @can('view commission request')
        $(document).on('click','.commission-request-btn',function(){
            let id = this.value;
            let element = $(this);

            $.ajax({
                'url' : '/commission-requests',
                'type' : 'POST',
                'data' : {'sales_id' : id},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){
                    element.attr('disabled',true).text('Requesting...');
                },
                success: function(response){
                    console.log(response);
                    if(response.success === true)
                    {
                        element.text('Requested');
                        setTimeout(function () {
                            element.fadeOut();
                        },1000);

                        element.closest('tr').removeClass('for-commission').addClass('commission-request-pending')
                    }

                },error: function(xhr, status, error){
                    let validation = JSON.parse(xhr.responseText)
                    console.log(xhr);
                    element.attr('disabled',false).text('Request Commission');
                    customAlert('warning',validation.message)
                }
            });
        });
        @endcan


        //Initialize Select2 Elements
        $('.select2').select2();
        $('#edit_reservation_date').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });

        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });

        $(document).on('change','select[name=hide_rate]',function(){
            let value = this.value;

            $.ajax({
                'url' : '{{route('hide.sale.rate')}}',
                'type' : 'POST',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'data' : {
                    'rate' : value
                },beforeSend: function(){

                },success: function(response){
                    let table = $('#sales-list').DataTable();
                    table.ajax.reload(null, false);
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        });
    </script>
    @endcan
@stop
