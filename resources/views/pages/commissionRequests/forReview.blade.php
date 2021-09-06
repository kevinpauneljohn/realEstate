@extends('adminlte::page')

@section('title', 'Request Details')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Request #: <span style="color:#007bff">{{str_pad($commissionRequest->id, 6, '0', STR_PAD_LEFT)}}</span></h1>
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
                            <div class="info-box @if($commissionRequest->status == "requested to developer")bg-success @else bg-light @endif">
                                <span class="info-box-icon"><i class="fa fa-building"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Request To Developer</span>
                                    <span class="info-box-number">
                                            @if($commissionRequest->status == "requested to developer")
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
                                        @elseif($commissionRequest->status == "pending" || $commissionRequest->status == "for review" || $commissionRequest->status == "requested to developer")
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
                            <th>Approved Rate</th>
                            <th>Approved Estimated Amount</th>
                        </tr>
                        <tr id="request-data">
                            <td>{{ucfirst($commissionRequest->status)}}</td>
                            <td>{{$commissionRequest->created_at->format('F-d-Y')}} </td>
                            <td>{{$askingRate}}%</td>
                            <td>{{number_format($estimatedAmount,2)}} </td>
                            <td>@if($commissionRequest->approved_rate !== null) {{$commissionRequest->approved_rate}}% @endif </td>
                            <td>@if($commissionRequest->approved_rate !== null) {{number_format($approvedEstimatedAmount,2)}} @endif</td>
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
                            <div class="post">
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

                            <div class="post">
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

                            <div class="post">
                                <h5>Admin Action</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Action</th>
                                        <th>Remarks</th>
                                    </tr>
                                    <tr>
                                        <td width="20%">Requested To Developer</td>
                                        <td>{{$commissionRequest->remarks['request_to_developer']}}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">For Release</td>
                                        <td>{{$commissionRequest->remarks['for_release']}}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Completed</td>
                                        <td>{{$commissionRequest->remarks['rejected']}}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Rejected</td>
                                        <td>{{$commissionRequest->remarks['completed']}}</td>
                                    </tr>
                                </table>
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
                            <b class="d-block">{{$rateGiven}}%</b>
                        </p>
{{--                        days passes: {{collect($byPass)->where('upLine_id',auth()->user()->id)->first()['daysPasses']}}<br/>--}}
{{--                        days  by pass: {{collect($byPass)->where('upLine_id',auth()->user()->id)->first()['daysByPass']}}<br/>--}}
{{--                        by pass user: {{collect($byPass)->where('upLine_id',auth()->user()->id)->first()['byPassConsent'] ? "yes" : "no"}}<br/>--}}
{{--                        allow approve and reject: {{collect($byPass)->where('upLine_id',auth()->user()->id)->first()['AllowByPassApproveAndReject'] ? "yes" : "no"}}<br/>--}}

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


                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    </div>


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
@stop
