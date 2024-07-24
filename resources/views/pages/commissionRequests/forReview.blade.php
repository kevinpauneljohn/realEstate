@extends('adminlte::page')

@section('title', 'Request Details')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Request #: <span style="color:#007bff">{{$commissionRequest->request_number}}</span> - {!! $status !!}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{url()->previous()}}">Commission Request</a></li>
                <li class="breadcrumb-item active">Request Details</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-10 order-2 order-md-1">
                    <div class="row" id="phase-tracker">
                        <div class="col-12 col-sm-3 phase">
                            <div class="info-box @if($commissionRequest->status == "pending")bg-success @else bg-light @endif">
                                <span class="info-box-icon"><i class="far fa-thumbs-up"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Up Line Approvals</span>
                                    <span class="info-box-number">
                                            @if($commissionRequest->status == "pending")
                                            On-going
                                        @else
                                            <span class="text-success">Completed</span>
                                        @endif
                                        </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>

                        </div>
                        <div class="col-12 col-sm-3 phase">
                            <div class="info-box @if($commissionRequest->status == "for review")bg-success @else bg-light @endif">
                                <span class="info-box-icon"><i class="fas fa-search"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Admin Review</span>
                                    <span class="info-box-number">
                                            @if($commissionRequest->status == "for review")
                                            On-going
                                        @elseif($commissionRequest->status == "pending")
                                            pending
                                        @else
                                            <span class="text-success">Completed</span>
                                        @endif
                                        </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>
                        <div class="col-12 col-sm-3 phase">
                            <div class="info-box @if($commissionRequest->status == "requested")bg-success @else bg-light @endif">
                                <span class="info-box-icon"><i class="fa fa-building"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Request To Developer</span>
                                    <span class="info-box-number">
                                            @if($commissionRequest->status == "requested")
                                            On-going
                                        @elseif($commissionRequest->status == "pending" || $commissionRequest->status == "for review")
                                            pending
                                        @else
                                            <span class="text-success">Completed</span>
                                        @endif
                                        </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>
                        <div class="col-12 col-sm-3 phase">
                            <div class="info-box @if($commissionRequest->status == "for release")bg-success @else bg-light @endif">
                                <span class="info-box-icon"><i class="fa fa-check-square"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">For Release</span>
                                    <span class="info-box-number">
                                            @if($commissionRequest->status == "for release")
                                            On-going
                                        @elseif($commissionRequest->status == "pending" || $commissionRequest->status == "for review" || $commissionRequest->status == "requested")
                                            pending
                                        @else
                                            <span class="text-success">Completed</span>
                                        @endif
                                        </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered" id="request-overview">
                        <tr>
                            <th>Request Status</th>
                            <th>Date Requested</th>
                            <th>Rate Requested</th>
                            <th>Estimated Amount</th>
                            <th>% Released</th>
                            <th>Released Amount</th>
                        </tr>
                        <tr id="request-data">
                            <td>{{ucfirst($commissionRequest->status)}}</td>
                            <td>{{$commissionRequest->created_at->format('F-d-Y')}} </td>
                            <td><span class="dhg-hidden">{{$askingRate}}%</span></td>
                            <td>{{number_format($estimatedAmount,2)}} </td>
                            <td>@if($commissionVoucher->count() > 0) <span class="dhg-hidden">{{$commissionVoucher->first()->percentage_released}}%</span> @endif </td>
                            <td>@if($commissionVoucher->count() > 0) {{number_format($commissionVoucher->first()->net_commission_less_deductions,2)}} @endif</td>
                        </tr>
                    </table>

                    <div class="row">
                        <div class="col-12">
                            <div class="post">
                                <h5>Sales Details</h5>

                                <table class="table table-bordered">
                                    <tr>
                                        <th>Date Reserved</th>
                                        <th>Last Due Date</th>
                                        <th>Agent</th>
                                        <th>Client</th>
                                        <th>Project</th>
                                        <th>Model Unit</th>
                                        <th>Phase/Blk/Lot</th>
                                        <th>TCP</th>
                                        <th>Discount</th>
                                        <th>Financing</th>
                                    </tr>
                                    <tr>
                                        <td>{{\Carbon\Carbon::create($commissionRequest->sales->reservation_date)->format('F-d-Y')}}</td>
                                        <td>{{\Carbon\Carbon::create($lastDueDate)->format('F-d-Y')}}</td>
                                        <td>{{$commissionRequest->sales->user->fullname}}</td>
                                        <td>{{$commissionRequest->sales->lead->fullname}}</td>
                                        <td>{{$commissionRequest->sales->project->name}}</td>
                                        <td>{{$commissionRequest->sales->modelUnit->name}}</td>
                                        <td>{{$commissionRequest->sales->location}}</td>
                                        <td>{{number_format($commissionRequest->sales->total_contract_price,2)}}</td>
                                        <td>{{number_format($commissionRequest->sales->discount,2)}}</td>
                                        <td>{{$commissionRequest->sales->financing}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="card card-primary card-outline card-tabs">
                                <div class="card-header p-0 pt-1 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="requirements-tab-link" data-toggle="pill" href="#requirements-tab" role="tab" aria-selected="true">Requirements</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="approval-tab-link" data-toggle="pill" href="#approval-tab" role="tab" aria-selected="false">Approvals</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="findings-tab-link" data-toggle="pill" href="#findings-tab" role="tab" aria-selected="false">Findings</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-three-tabContent">
                                        <div class="tab-pane fade active show" id="requirements-tab" role="tabpanel">
                                            <h5>Requirements <span class="text-info">({{\App\Services\ClientRequirementsService::clientRequirements($commissionRequest->sales->clientrequirements)}})</span></h5>
                                            <input type="text" class="form-control mb-2" value="{{collect($commissionRequest->sales->clientRequirements)->count() > 0 ? collect($commissionRequest->sales->clientRequirements)->first()->drive_link : ""}}" @if(collect($commissionRequest->sales->clientRequirements)->count() == 0) disabled @endif>
                                            <a href="{{collect($commissionRequest->sales->clientRequirements)->count() > 0 ? collect($commissionRequest->sales->clientRequirements)->first()->drive_link : "#"}}"  @if(collect($commissionRequest->sales->clientRequirements)->count() > 0) target="_blank" @endif class="btn btn-default btn-xs mb-2" title="click to access drive" >Access Drive</a>
                                            @if(collect($commissionRequest->sales->clientrequirements)->count() > 0)
                                                <table class="table table-bordered">
                                                    @foreach(collect($commissionRequest->sales->clientrequirements)->first()->requirements as $requirement)
                                                        <tr>
                                                            <td>{{$requirement['description']}}</td>
                                                            <td>
                                                                @if($requirement['exists'])
                                                                    <i class="fas fa-check text-success"></i>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            @endif
                                        </div>
                                        <div class="tab-pane fade" id="approval-tab" role="tabpanel">
                                            <h5>Approvals</h5>
                                            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                                <table id="approvals-list" class="table table-bordered table-hover" role="grid">
                                                    <thead>
                                                    <tr role="row">
                                                        <td width="20%">Up Line</td>
                                                        <td>Remarks</td>
                                                        <td width="8%">Status</td>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="findings-tab" role="tabpanel">
                                            <x-findings.findings-tab :commissionRequest="$commissionRequest"/>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-2 order-1 order-md-2">
                    <h6 class="text-primary"><i class="fas fa-user-tie"></i> Requester Details</h6>

                    <div class="text-muted">
                        <p class="text-sm">Agent Name
                            <b class="d-block">{{$commissionRequest->user->fullname}}</b>
                        </p>
                        <p class="text-sm">Role
                            <b class="d-block">
                                @foreach($commissionRequest->user->getRoleNames() as $role)
                                    <span class="right badge badge-info">{{$role}}</span>
                                @endforeach
                            </b>
                        </p>
                        <p class="text-sm">Email
                            <b class="d-block">{{$commissionRequest->user->email}}</b>
                        </p>
                        <p class="text-sm">Contact Number
                            <b class="d-block">{{$commissionRequest->user->mobileNo}}</b>
                        </p>
                        <p class="text-sm">Commission Rate
                            <b class="d-block dhg-hidden">{{$rateGiven}}%</b>
                        </p>
                        @if(collect($byPass)->where('upLine_id',auth()->user()->id)->count() > 0)
                            @if(collect($byPass)->where('upLine_id',auth()->user()->id)->first()['finalConsent'] && !auth()->user()->hasRole('Finance Admin'))

                                <div class="text-center mt-5 mb-3">
                                    <button class="btn btn-sm btn-primary approval-btn" id="approve-btn" data-toggle="modal" data-target="#approve">Approve</button>
                                    <button class="btn btn-sm btn-danger approval-btn" id="reject-btn" data-toggle="modal" data-target="#approve">Reject</button>
                                </div>

                            @else

                                @if(auth()->user()->hasRole('Finance Admin') && $commissionRequest->status != "rejected" && $commissionRequest->status != "completed")
                                    <div class="mt-5 mb-3" id="finance-admin-form">
                                        <form id="finance-admin-action">
                                            @csrf
                                            <div class="form-group action">
                                                <label>Set Action</label>
                                                <select name="action" class="select2" id="action" style="width: 100%">
                                                    <option value="" selected>-- Select Action --</option>
                                                    @if($commissionRequest->status !== "requested to developer" && $commissionRequest->status !== "for release")
                                                        <option value="request to developer">Request to developer</option>
                                                    @endif

                                                    @if($commissionRequest->status !== "for release")
                                                        <option value="for release">For Release</option>
                                                    @endif

                                                    <option value="completed">Completed</option>
                                                    <option value="reject">Reject request</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Remarks</label> <span>(optional)</span>
                                                <textarea name="remarks" class="form-control" style="min-height: 150px;"></textarea>
                                            </div>
                                            <input type="submit" class="btn btn-primary" value="Submit" style="width: 100%">
                                        </form>
                                    </div>
                                @endif
                            @endif
                        @endif



                </div>
            </div>
        </div>
        <!-- /.card-body -->
        </div>
    </div>
    @if($commissionRequest->sales->user_id === auth()->user()->id && $commissionVoucher->count() > 0 && $commissionRequest->status === 'completed')
        <div class="row">
            <div class="col-lg-10">
                <div class="card voucher-preview">

                    <div class="card-body preview table-responsive-xl">

                        <table class="table table-bordered table-hover">
                            <tbody><tr>
                                <th class="text-center" colspan="4">Dream Home Guide Realty Comm Voucher</th>
                            </tr>
                            <tr>
                                <th id="project-name" colspan="4" class="text-center"></th>
                            </tr>
                            <tr>
                                <td>Payee</td>
                                <td id="payee" class="text-bold">{{$commissionRequest->sales->user->fullname}}</td>
                                <td>Amount:</td>
                                <td id="amount" class="text-bold">@if($commissionVoucher->count() > 0) {{number_format($commissionVoucher->first()->net_commission_less_deductions,2)}} @endif</td>
                            </tr>
                            <tr>
                                <td class="w-25">Client</td>
                                <td id="client" class="text-bold w-25">{{$commissionRequest->sales->lead->fullname}}</td>
                                <td>In Words:</td>
                                <td id="amount-in-words" class="text-bold w-50">@if($commissionVoucher->count() > 0) {{ucwords($net_commission_in_words)}} @endif</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="table-active"></td>
                            </tr>
                            <tr>
                                <td colspan="3">TCP</td>
                                <td colspan="1" id="tcp">@if($commissionVoucher->count() > 0)&#8369; {{number_format($commissionRequest->sales->total_contract_price,2)}} @endif</td>
                            </tr>
                            <tr>
                                <td colspan="3">Requested %</td>
                                <td colspan="1"><span id="requested-rate" class="dhg-hidden">{{number_format($commissionRequest->commission,2)}}%</span></td>
                            </tr>
                            <tr>
                                <td colspan="3">Gross Commission</td>
                                <td colspan="1" id="gross-commission">@if($commissionVoucher->count() > 0)&#8369;{{number_format($commissionVoucher->first()->gross_commission,2)}}@endif</td>
                            </tr>
                            <tr id="tax-basis-row" style="display: none;">
                                <td colspan="3" id="tax_basis_reference_remarks"></td>
                                <td colspan="1" id="tax-basis"></td>
                            </tr>
                            <tr>
                                <td colspan="3"><span id="percent-released">@if($commissionVoucher->count() === 0) 0 @else {{$commissionVoucher->first()->percentage_released}} @endif%</span> Released</td>
                                <td colspan="1" id="released-gross-commission">@if($commissionVoucher->count() > 0)&#8369; {{number_format($commissionVoucher->first()->sub_total,2)}} @endif</td>
                            </tr>
                            <tr>
                                <td colspan="3">Withholding Tax <span id="wht-percent">@if($commissionVoucher->count() === 0) 0 @else {{$commissionVoucher->first()->wht_percent}} @endif%</span></td>
                                <td colspan="1" id="wht">@if($commissionVoucher->count() > 0)&#8369; {{number_format($commissionVoucher->first()->wht_amount,2)}} @endif</td>
                            </tr>
                            <tr>
                                <td colspan="3">VAT <span id="vat-percent">@if($commissionVoucher->count() === 0) 0 @else {{$commissionVoucher->first()->vat_percent}} @endif%</span></td>
                                <td colspan="1" id="vat-amount">@if($commissionVoucher->count() > 0)&#8369; {{number_format($commissionVoucher->first()->vat_amount,2)}} @endif</td>
                            </tr>
                            <tr class="net-commission">
                                <td colspan="3">Net Commission</td>
                                <td colspan="1" id="net-commission">@if($commissionVoucher->count() > 0)&#8369;  {{number_format($commissionVoucher->first()->net_commission_less_vat,2)}} @endif</td>
                            </tr>
                            @if($commissionVoucher->count() > 0)
                                @foreach($commissionVoucher->first()->deductions as $deduction)
                                    <tr>
                                        <td colspan="3">{{$deduction->title}}</td>
                                        <td class="text-danger">- &#8369; {{number_format($deduction->amount,2)}}</td>
                                    </tr>
                                @endforeach
                            @endif
                            @if($commissionVoucher->count() > 0)
                                @if($commissionVoucher->first()->deductions->count() > 0)
                                    <tr>
                                        <td colspan="3">Total Commission Balance</td>
                                        <td class="text-success"> &#8369; {{number_format($commissionVoucher->first()->net_commission_less_deductions,2)}}</td>
                                    </tr>
                                @endif
                            @endif
                            <tr id="row-separator">
                                <td colspan="4" class="table-active"></td>
                            </tr>
                            </tbody></table>
                        @if($commissionVoucher->count() > 0 && !is_null($commissionVoucher->first()->drive_link))
                            <a href="{{$commissionVoucher->first()->drive_link}}" target="_blank" class="btn btn-success mt-2">Access drive</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(auth()->user()->hasRole(['super admin','admin','Finance Admin']))
        <div class="row">
            <div class="col-lg-6">
                <div class="card card-olive" id="related-requests">
                    <div class="card-header">
                        <h3 class="card-title">Related Requests</h3>
                    </div>
                    <div class="card-body">
                        @if(collect($related_requests)->count() > 0)
                                @foreach($related_requests as $request)
                                    <a href="@if($request->id === $commissionRequest->id) #related-requests @else{{route('commission.request.review',['request' => $request->id])}}@endif" style="font-size: 15pt;"><span class="@if($request->id === $commissionRequest->id) text-success @else text-primary @endif">#{{$request->request_number}}</span></a>
                                    @if($request->id === $commissionRequest->id) <i>Current</i> @endif<br/>
                                @endforeach
                            @else
                            No Available Data
                        @endif
                    </div>
                </div>
                <form>
                     @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 request_status">
                                    <label for="request_status">Request Status</label>
                                    <select name="request_status" class="form-control" id="request_status">
                                        <option value="pending" @if($commissionRequest->status === 'pending') selected @endif>Pending</option>
                                        <option value="for review" @if($commissionRequest->status === 'for review') selected @endif>On-going Admin Review</option>
                                        <option value="requested" @if($commissionRequest->status === 'requested') selected @endif>Requested to developer</option>
                                        <option value="for release" @if($commissionRequest->status === 'for release') selected @endif>For Release</option>
                                        <option value="completed" @if($commissionRequest->status === 'completed') selected @endif>Completed</option>
                                        <option value="rejected" @if($commissionRequest->status === 'rejected') selected @endif>Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @if($commissionRequest->status !== 'completed' && $commissionRequest->status !== 'rejected')
                <div class="col-lg-6">
                    <form class="sales-form">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 total_contract_price">
                                        <label for="total_contract_price">Total Contract Price</label>
                                        <input type="number" name="total_contract_price" step="any" class="form-control" id="total_contract_price" value="{{$commissionRequest->sales->total_contract_price}}">
                                    </div>
                                    <div class="col-lg-6 commission_rate">
                                        <label for="commission_rate">Commission Rate</label>
                                        <input type="number" name="commission_rate" step="any" class="form-control" id="commission_rate" value="{{\Illuminate\Support\Facades\DB::table('settings')->where('title','sensitive_data')->first()->show ? $commissionRequest->commission : 0}}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <label for="total_commission">Total Commission Amount</label>
                                        <input type="text" name="total_commission" step="any" class="form-control" id="total_commission" value="{{number_format($commissionRequest->sales->total_contract_price * ($commissionRequest->commission / 100),2)}}" disabled>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="request_id" value="{{$commissionRequest->id}}">
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-lg-6">
                <form class="calculate-voucher">
                    @csrf
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="category">Category</label>
                                    <select name="category" class="form-select form-control" id="category" required="">
                                        <option value="">--Select Category--</option>
                                        <option value="Corporate Broker's Tax Deduction">Corporate Broker's Tax Deduction</option>
                                        <option value="Individual Broker's Tax Deduction">Individual Broker's Tax Deduction</option>
                                        <option value="Apec Homes Tax Deduction">Apec Homes Tax Deduction</option>
                                        <option value="No Tax Deduction">No Tax Deduction</option>
                                        <option value="Split Commission">Split Commission</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-9 mt-3 total_contract_price">
                                    <label for="total_contract_price">TCP</label>
                                    <input type="number" step="any" class="form-control" name="total_contract_price" id="total_contract_price" value="{{$commissionRequest->sales->total_contract_price}}" readonly>
                                </div>
                                <div class="col-lg-3 mt-3 requested_rate">
                                    <label for="requested_rate">Requested Rate</label>
                                    <input type="number" step="any" class="form-control" name="requested_rate" id="requested_rate" max="100" min="0" value="{{\Illuminate\Support\Facades\DB::table('settings')->where('title','sensitive_data')->first()->show ? $commissionRequest->commission : 0}}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mt-3 gross_commission">
                                    <label for="gross_commission">Gross Commission</label>
                                    <input type="number" step="any" class="form-control" name="gross_commission" id="gross_commission" max="5000000" min="0" value="{{$commissionRequest->sales->total_contract_price * ($commissionRequest->commission / 100)}}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mt-3 reference_amount_for_wht">
                                    <input type="checkbox" id="reference_amount_for_wht" name="reference_amount_checkbox">
                                    <label for="reference_amount_for_wht"></label> <span>Select other amount for wht</span>
                                </div>
                            </div>
                            <div class="row reference_amount_field_row" style="display: none;">
                                <div class="col-lg-6 mt-3 reference_amount">
                                    <label for="reference_amount">Ref. Amt WHT</label>
                                    <input type="number" step="any" class="form-control" name="reference_amount" id="reference_amount" max="5000000" min="0" value="0" disabled="disabled" required="">
                                </div>
                                <div class="col-lg-6 mt-3 remarks">
                                    <label for="remarks">Remarks</label>
                                    <input type="text" step="any" class="form-control" name="remarks" id="remarks" disabled="disabled" required="">
                                </div>
                            </div>
                            <div class="row reference_amount_field_row" style="display: none;">
                                <div class="col-lg-5 mt-3 percentage_released_reference_amount">
                                    <label for="percentage_released_reference_amount">% Released ref</label>
                                    <input type="number" step="any" class="form-control" name="percentage_released_reference_amount" id="percentage_released_reference_amount" placeholder="remaining" max="100" min="0" required="" disabled="disabled">
                                </div>
                                <div class="col-lg-7 mt-3 sub_total_reference_amount">
                                    <label for="sub_total_reference_amount">Sub Total ref</label>
                                    <input type="number" step="any" class="form-control" name="sub_total_reference_amount" id="sub_total_reference_amount" max="5000000" min="0" value="0" required="" disabled="disabled">
                                </div>
                            </div>
                            <div class="row tcp_basis">
                                <div class="col-lg-5 mt-3 percentage_released">
                                    <label for="percentage_released">% Released</label>
                                    <input type="number" step="any" class="form-control" name="percentage_released" id="percentage_released" placeholder="{{$remaining_request}}% remaining" max="{{$remaining_request}}" min="0" required="">
                                </div>
                                <div class="col-lg-7 mt-3 sub_total">
                                    <label for="sub_total">Sub Total</label>
                                    <input type="number" step=".01" class="form-control" name="sub_total" id="sub_total" max="{{$commissionRequest->sales->total_contract_price * ($commissionRequest->commission / 100)}}" min="0" value="0">
                                </div>
                            </div>
                            <div class="row tax">
                                <div class="col-lg-6 mt-3 wht">
                                    <label for="wht">WHT Tax</label>
                                    <input type="number" name="wht" step="any" class="form-control" id="wht" min="0" value="0" readonly>
                                </div>
                                <div class="col-lg-6 mt-3 vat">
                                    <label for="vat">VAT</label>
                                    <input type="number" name="vat" step="any" class="form-control" id="vat" min="0" max="12" value="0" readonly>
                                </div>
                            </div>
                            <div class="row deductions">
                                <div class="col-12 mt-3"><h5>Deductions</h5></div>

                            </div>
                        </div>
                        <input type="hidden" name="sale_id" value="{{$commissionRequest->sales_id}}">
                        <input type="hidden" name="commission_request_id" value="{{$commissionRequest->id}}">
                        <div class="card-footer">
                            <button type="submit" class="btn bg-gray">Preview</button>
                            <span class="float-right">
                                <button type="button" class="btn bg-warning" id="add-deduction-btn">Add Deduction</button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-6">
                <div class="card voucher-preview">

                    <div class="card-body preview table-responsive-xl">

                        <x-commission-voucher.voucher :commissionRequest="$commissionRequest" :commissionVoucher="$commissionVoucher" :netCommissionWords="$net_commission_in_words"/>
                        <div id="save-button-section" class="mt-3">
                            @if($commissionRequest->status !== 'completed' && $commissionRequest->status !== 'rejected')
                                @if($commissionVoucher->count() === 0)
                                    <button type="button" class="btn btn-primary btn-sm w-100" id="save-voucher-button">Save</button>
                                @elseif($commissionVoucher->count() > 0 && $commissionVoucher->first()->status === 'pending')
                                    <button type="button" class="btn btn-success btn-sm" id="approve-voucher-button">Approve</button>
                                    <button type="button" class="btn btn-danger btn-sm" id="remove-voucher-button">Remove</button>
                                @endif

                            @elseif($commissionVoucher->count() > 0 && $commissionVoucher->first()->status === 'approved')
                                <form id="save-drive-form">
                                    @csrf
                                    <div class="form-group drive_link">
                                        <label for="drive_link">Drive Link</label>
                                        <input type="text" name="drive_link" class="form-control" id="drive_link" value="{{$commissionVoucher->first()->drive_link}}">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Drive</button>
                                    @if(!is_null($commissionVoucher->first()->drive_link))
                                        <a href="{{$commissionVoucher->first()->drive_link}}" target="_blank" class="btn btn-success">Access Drive</a>
                                    @endif
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

   @endif

@if(collect($byPass)->where('upLine_id',auth()->user()->id)->count() > 0)
    @if(collect($byPass)->where('upLine_id',auth()->user()->id)->first()['finalConsent'] && !auth()->user()->hasRole('Finance Admin'))
        <!--add contacts modal-->
        <div class="modal fade" id="approve">
            <form role="form" id="approve-request-form">
                @csrf
                <input type="hidden" name="status">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Approve Request?</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group remarks">
                                <label for="remarks">Remarks</label> <span class="text-gray">(optional)</span>
                                <textarea class="form-control" name="remarks" id="remarks" style="min-height: 150px;"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-request" value="Proceed">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end contacts modal-->
    @endif
@endif

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
    <style type="text/css">
        .data-value
        {
            color: #1d68a7;
            font-weight: bold;
        }
    </style>
@stop

@section('js')
    <!-- bootstrap datepicker -->
    <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('/js/custom-alert.js')}}"></script>
    <script src="{{asset('/js/validation.js')}}"></script>
    <script>
        @if(collect($byPass)->where('upLine_id',auth()->user()->id)->count() > 0)
            @if(collect($byPass)->where('upLine_id',auth()->user()->id)->first()['finalConsent'] && !auth()->user()->hasRole('Finance Admin'))
            let approveForm = $('#approve-request-form');

            $(document).on('click','.approval-btn',function(){
                let id = this.id;
                let status = "";
                let modalTitle = "";
                console.log(id);
                if(id === "approve-btn"){
                    status = "approved";
                    modalTitle = "Approved Request?";
                }else {
                    status = "rejected";
                    modalTitle = "Reject Request?";
                }
                approveForm.find('input[name=status]').val(status);
                approveForm.find('.modal-title').text(modalTitle);
            });

            $(document).on('submit','#approve-request-form',function(form){
                form.preventDefault();

                let data = $(this).serializeArray();
                console.log(data);

                $.ajax({
                    'url' : '{{route('commission.request.status.set',['request' => $commissionRequest->id])}}',
                    'type' : 'post',
                    'data' : data,
                    beforeSend: function(){
                        approveForm.find('.submit-request').attr('disabled',true).val('Proceeding ...');
                    },success: function (response) {
                        console.log(response);

                        if(response.success === true)
                        {
                            let table = $('#approvals-list').DataTable();
                            table.ajax.reload(null, false);

                            $('.approval-btn').fadeOut();
                            customAlert('success',response.message);

                            approveForm.find('.submit-request').attr('disabled',false).val('Proceeded!').removeClass('btn-primary').addClass('btn-success');
                            setTimeout(function () {
                                approveForm.closest('#approve').modal('toggle');
                                setTimeout(function () {
                                    approveForm.closest('#approve').remove()
                                },850);
                            },800);
                        }

                    },error: function(xhr, status, error){
                        console.log(xhr)
                    }
                });
            });

            @else
            $(document).on('submit','#finance-admin-action',function(form){
                form.preventDefault();
                let data = $(this).serializeArray();
                console.log(data[1].value);

                Swal.fire({
                    title: 'Set Action <span class="text-info"> &nbsp;'+data[1].value+'</span>?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    console.log(result);
                    if (result.value) {

                        $.ajax({
                            'url' : '{{route('commission.request.admin.action',['request' => $commissionRequest->id])}}',
                            'type' : 'PATCH',
                            'data' : data,
                            beforeSend: function(){
                                $('#finance-admin-action').find('input[type=submit]').attr('disabled',true).val('Submitting ...');
                            },success: function(response){
                                console.log(response)

                                if(response.success === true)
                                {
                                    $('#phase-tracker').load('{{url()->current()}} #phase-tracker .phase');
                                    $('#request-overview #request-data').load('{{url()->current()}} #request-overview #request-data td');
                                    $('#finance-admin-form #finance-admin-action select').load('{{url()->current()}} #finance-admin-form #finance-admin-action select option',function () {
                                        $('#finance-admin-action').trigger('reset');
                                    });
                                }

                                if(response.request_status === "completed" || response.request_status === "rejected")
                                {
                                    $('#finance-admin-action').remove();
                                }

                                $.each(response, function (key, value) {
                                    let element = $('.'+key);

                                    element.find('.error-'+key).remove();
                                    element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
                                });
                                $('#finance-admin-action').find('input[type=submit]').attr('disabled',false).val('Submit');
                            },error: function(xhr, status, error){
                                console.log(xhr);
                                $('#finance-admin-action').find('input[type=submit]').attr('disabled',false).val('Submit');
                            }
                        });
                        clear_errors('action');

                    }
                });


            });
            @endif
        @endif



        $(function() {
            $('#approvals-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('commission.request.review.approval',['request' => $commissionRequest->id]) !!}',
                columns: [
                    { data: 'id', name: 'id'},
                    { data: 'remarks', name: 'remarks'},
                    { data: 'approval', name: 'approval'},
                ],
                responsive:true,
                order:[0,'asc'],
                ordering: false,
                searching: false,
                paging: false,
            });
        });
    </script>

    @if(auth()->user()->hasRole(['super admin','admin','Finance Admin']))
        <script>
            let request_status = $('#request_status').val();
            $(document).on('change','#request_status',function(){
                let value = $(this).val();
                console.log(value)
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, update status!'
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            'url' : '/commission-request-status-update/{{$commissionRequest->id}}',
                            'type' : 'post',
                            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            'data' : {'status':value},
                            beforeSend: function(){

                            },success: function(response){
                                console.log(response)
                                if(response.success === true)
                                {
                                    Swal.fire({
                                        title: "Good job!",
                                        text: response.message,
                                        type: "success"
                                    });

                                    window.location.reload();
                                }
                            },error: function(xhr, status, error){
                                console.log(xhr);
                            }
                        });

                    }else{
                        $(this).val(request_status)
                    }
                });
            });


            function category(category)
            {
                let wht = 0, vat = 0, readonly = true;
                if(category === "Corporate Broker's Tax Deduction")
                {
                    wht = 15; vat = 12;
                    readonly = false;
                }
                else if(category === "Individual Broker's Tax Deduction")
                {
                    wht = 10; vat = 12;
                    readonly = false;
                }
                else if(category === "Apec Homes Tax Deduction")
                {
                    wht = 15; vat = 0;
                    readonly = false;
                }

                $('#wht').val(wht).attr('readonly',readonly);
                $('#vat').val(vat).attr('readonly',readonly);
            }

            $(document).on('change','#category',function(){
                let tax = $(this).val();
                category(tax)
            })

            function commission_released(gross_commission, percentage_released)
            {
                return gross_commission * (percentage_released / 100);
            }

            function rate_released(gross_commission, sub_total)
            {
                return (sub_total / gross_commission) * 100;
            }

            $(document).on('input','#percentage_released',function(){
                let gross_commission = $('#gross_commission').val();
                let percentage_released = $(this).val();

                if(percentage_released > 100)
                {
                    $(this).val(100).change();
                }
                else if(percentage_released < 0)
                {
                    $(this).val(0).change();
                }

                $('#sub_total').val(commission_released(gross_commission, percentage_released).toFixed(2));
            })


            $(document).on('input','#sub_total',function(){
                let gross_commission = $('#gross_commission').val();
                let sub_total = $(this).val();
                if(sub_total > gross_commission)
                {
                    $(this).val(gross_commission).change();
                }

                $('#percentage_released').val(rate_released(gross_commission, sub_total)).change()
            })

            let calculateVoucher = $('.calculate-voucher');
            $(document).on('click','#add-deduction-btn',function(){
                calculateVoucher.find('.deductions').append('<div class="deduction-row col-lg-12"><div class="row"><div class="col-5 mt-2"><input type="text" class="form-control" name="deductions_remarks[]" placeholder="Deduction"></div>' +
                    '<div class="col-5 mt-2"><input type="number" step="0.1" class="form-control" name="deductions[]" placeholder="amount"></div><div class="col-2"><button type="button" class="btn btn-xs btn-danger mt-3 remove-deduction"><i class="fa fa-minus"></i></button></div></div></div>');
            });

            $(document).on('click','.remove-deduction',function(){
                $(this).closest('.deduction-row').remove();
            });

            //commission reference amount
            const use_reference_amount = () => {
                let reference_amount_field_row = $('.reference_amount_field_row')
                let tcp_basis = $('.tcp_basis')
                if($('#reference_amount_for_wht').is(":checked"))
                {
                    reference_amount_field_row.show();
                    reference_amount_field_row.
                    find('#reference_amount, #percentage_released_reference_amount, #sub_total_reference_amount, #remarks').attr('disabled',false);

                    tcp_basis.hide()
                    tcp_basis.find('#percentage_released, #sub_total').attr('disabled',true);
                }else{
                    reference_amount_field_row.hide();
                    reference_amount_field_row.find('#reference_amount, #percentage_released_reference_amount, #sub_total_reference_amount, #remarks').attr('disabled',true);

                    tcp_basis.show()
                    tcp_basis.find('#percentage_released, #sub_total').attr('disabled',false);
                }
            }

            $(document).on('change','#reference_amount_for_wht',function (){
                use_reference_amount()
            })

            const reference_amount_for_wht_value = () => {
                return parseFloat($('input[name=reference_amount]').val());
            }

            $(document).on('input','input[name=percentage_released_reference_amount]',function(){
                let percentage_released_reference_amount = 0
                if(this.value !== "")
                {
                    percentage_released_reference_amount = parseFloat(this.value) / 100
                }

                let sub_total_ref_value = reference_amount_for_wht_value() * percentage_released_reference_amount

                $('input[name=sub_total_reference_amount]').val(sub_total_ref_value.toFixed(2));
            })

            $(document).on('input','input[name=reference_amount]',function(){
                let reference_amount = 0
                let percentage_released_reference_amount = 0
                let percentage_released_element = $('input[name=percentage_released_reference_amount]');
                if(this.value !== "")
                {
                    reference_amount = parseFloat(this.value)
                }

                if(percentage_released_element.val() !== "")
                {
                    percentage_released_reference_amount = parseFloat(percentage_released_element.val()) / 100
                }

                let sub_total_ref_value = reference_amount * percentage_released_reference_amount

                $('input[name=sub_total_reference_amount]').val(sub_total_ref_value.toFixed(2));
            })

            $(document).on('input','input[name=sub_total_reference_amount]',function(){
                let sub_total_reference_amount = 0
                let reference_amount = 0
                let reference_amount_element = $('input[name=reference_amount]');
                let percentage_released_element = $('input[name=percentage_released_reference_amount]');
                if(this.value !== "")
                {
                    sub_total_reference_amount = parseFloat(this.value)

                }

                if(reference_amount_element.val() !== "")
                {
                    reference_amount = parseFloat(reference_amount_element.val())
                }

                let percentage_released_reference_amount = (sub_total_reference_amount / reference_amount) * 100

                percentage_released_element.val(percentage_released_reference_amount.toFixed(2));
            })
            //end commission reference amount

            let voucherData;
            let voucherPreview = $('.voucher-preview');
            $(document).on('submit','.calculate-voucher',function(form){
                form.preventDefault();
                voucherData = $(this).serializeArray();
                console.log(voucherData);
                $.ajax({
                    url: '{{route('preview.voucher')}}',
                    type: 'post',
                    data: voucherData,
                    beforeSend: function(){
                        voucherPreview.append('<div class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>');
                        voucherPreview.find('.deduction-preview').remove()
                    }
                }).done(function(response){
                    console.log(response)
                    voucherPreview.find('#tcp').text('₱ '+response.tcp);
                    voucherPreview.find('#requested-rate').text(response.requested_rate);
                    voucherPreview.find('#gross-commission').text('₱ '+response.gross_commission);
                    voucherPreview.find('#percent-released').text(response.percentage_released);
                    voucherPreview.find('#released-gross-commission').text('₱ '+response.sub_total);
                    voucherPreview.find('#wht-percent').text('('+response.wht_percent+')');
                    voucherPreview.find('#wht').text('₱ '+response.wht);
                    voucherPreview.find('#vat-percent').text('('+response.vat_percent+')');
                    voucherPreview.find('#vat-amount').text('₱ '+response.vat);
                    voucherPreview.find('#net-commission').text('₱ '+response.total_receivable_without_deduction);


                    if(response.tax_basis_reference === true)
                    {
                        $('#tax-basis-row').show();
                        voucherPreview.find('#tax_basis_reference_remarks').text(response.tax_basis_reference_remarks);
                        voucherPreview.find('#tax-basis').text(response.tax_basis_reference_amount);
                    }else{
                        $('#tax-basis-row').hide();
                    }
                    // voucherPreview.find('.deduction-preview').append('<tr><td colspan="2" class="text-bold">Deductions</td></tr>');
                    let deductionRow = '';
                    let deductionCount = 0;
                    $.each(response.deductions, function(key, value){
                        deductionRow += '<tr class="deduction-preview"><td colspan="3">'+key+'</td><td class="text-danger" colspan="1">- ₱ '+value+'</td></tr>';
                        deductionCount++;
                    });

                    if(deductionCount > 0)
                    {
                        deductionRow += '<tr class="deduction-preview"><td colspan="3">Total Commission Balance</td><td class="text-success" colspan="1">₱ '+response.net_commission_less_deductions+'</td></tr>';
                    }
                    // console.log(deductionCount)
                    voucherPreview.find('.net-commission').after(deductionRow);
                }).fail(function(xhr, status, error){
                    console.log(xhr)
                }).always(function(){
                    $('.overlay').remove();
                });
            })


            $(document).on('click','#save-voucher-button',function (){
                Swal.fire({
                    title: 'Do you want to save the voucher?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            'url' : '/save-commission-voucher',
                            'type' : 'post',
                            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            'data' : voucherData,
                            beforeSend: function(){

                            },success: function(response){
                                console.log(response)
                                if(response.success === true)
                                {
                                    Swal.fire({
                                        title: "Good job!",
                                        text: response.message,
                                        type: "success"
                                    });

                                    setTimeout(function(){
                                        window.location.reload();
                                    },1000)
                                }
                            },error: function(xhr, status, error){
                                console.log(xhr);
                            }
                        });

                    }else{
                        $(this).val(request_status)
                    }
                });
            })

            @if($commissionVoucher->count() > 0)
            $(document).on('click','#approve-voucher-button',function (){
                Swal.fire({
                    title: 'Do you want to approve the voucher?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve it!'
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            'url' : '/approve-voucher/{{$commissionVoucher->first()->id}}',
                            'type' : 'post',
                            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            beforeSend: function(){

                            },success: function(response){
                                console.log(response)
                                if(response.success === true)
                                {
                                    Swal.fire({
                                        title: "Good job!",
                                        text: response.message,
                                        type: "success"
                                    });

                                    setTimeout(function(){
                                        window.location.reload();
                                    },1500)
                                }
                            },error: function(xhr, status, error){
                                console.log(xhr);
                            }
                        });

                    }else{
                        $(this).val(request_status)
                    }
                });
            })

            $(document).on('click','#remove-voucher-button',function (){
                Swal.fire({
                    title: 'Do you want to remove the voucher?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            'url' : '/remove-voucher/{{$commissionVoucher->first()->id}}',
                            'type' : 'delete',
                            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            beforeSend: function(){

                            },success: function(response){
                                console.log(response)
                                if(response.success === true)
                                {
                                    Swal.fire({
                                        title: "Good job!",
                                        text: response.message,
                                        type: "success"
                                    });

                                    setTimeout(function(){
                                        window.location.reload();
                                    },1500)
                                }
                            },error: function(xhr, status, error){
                                console.log(xhr);
                            }
                        });

                    }else{
                        $(this).val(request_status)
                    }
                });
            })
            @endif
            @if($commissionRequest->status !== 'completed' && $commissionRequest->status !== 'rejected')
            let salesForm = $('.sales-form');
            $(document).on('submit','.sales-form', function(form){
                form.preventDefault();
                let data = $(this).serializeArray();
                // console.log(data)
                $.ajax({
                    url: '{{route('update.sales.tcp',['sales_id' => $commissionRequest->sales->id])}}',
                    type: 'post',
                    data: data,
                    beforeSend: function(){
                        salesForm.find('.text-danger').remove();
                        salesForm.find('button[type=submit]').attr('disabled',true).text('Saving...')
                    }
                }).done(function(response){
                    console.log(response)
                    if(response.success === true)
                    {
                        Swal.fire({
                            title: "Good job!",
                            text: response.message,
                            icon: "success"
                        });

                        setTimeout(function(){
                            window.location.reload();
                        },1500)
                    }
                    else if(response.success === false)
                    {
                        Swal.fire({
                            title: response.message,
                            icon: "warning"
                        });
                    }
                }).fail(function(xhr, status, error){
                    console.log(xhr)
                    $.each(xhr.responseJSON.errors, function(key, value){
                        salesForm.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                    })

                }).always(function(){
                    salesForm.find('button[type=submit]').attr('disabled',false).text('Save')
                })
            })

            @else
                let saveDriveForm = $('#save-drive-form');
                 $(document).on('submit','#save-drive-form',function(form){
                     form.preventDefault();
                     let data = $(this).serializeArray();

                     $.ajax({
                         url: '{{route('voucher.save.drive.link',['voucher_id' => $commissionVoucher->first()->id])}}',
                         type: 'patch',
                         data: data,
                         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                         beforeSend: function(){
                             saveDriveForm.find('.text-danger').remove();
                             saveDriveForm.find('button[type=submit]').attr('disabled',true).text('Saving...');
                         }
                     }).done(function(response){
                         console.log(response)
                         if(response.success === true)
                         {
                             Swal.fire({
                                 title: "Good job!",
                                 text: response.message,
                                 icon: "success"
                             });

                             setTimeout(function(){
                                 window.location.reload();
                             },1500)
                         }
                         else if(response.success === false)
                         {
                             Swal.fire({
                                 title: response.message,
                                 icon: "warning"
                             });
                         }
                     }).fail(function(xhr, status, error){
                        console.log(xhr)
                         $.each(xhr.responseJSON.errors, function(key, value){
                             saveDriveForm.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                         })
                     }).always(function(){
                         saveDriveForm.find('button[type=submit]').attr('disabled',false).text('Save Drive');
                     })
                 })
            @endif
        </script>
    @endif
@stop
