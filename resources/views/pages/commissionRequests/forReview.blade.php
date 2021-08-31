@extends('adminlte::page')

@section('title', 'Request Details')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Request Details</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('commission.request.approval')}}">Commission Request</a></li>
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
                    <div class="row">
                        <div class="col-12 col-sm-3">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Request Status</span>
                                    <span class="info-box-number text-center text-muted mb-0">{{ucfirst($commissionRequest->status)}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-3">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Date Requested</span>
                                    <span class="info-box-number text-center text-muted mb-0">{{$commissionRequest->created_at->format('F-d-Y')}} </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-3">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Rate Requested</span>
                                    <span class="info-box-number text-center text-muted mb-0">{{$askingRate}}%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-3">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Estimated Amount</span>
                                    <span class="info-box-number text-center text-muted mb-0">{{number_format($estimatedAmount,2)}} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="post">
                                <h5>Sales Details</h5>

                                <table class="table table-bordered">
                                    <tr>
                                        <th>Date Reserved</th>
                                        <th>Last Due Date</th>
                                        <th>Client</th>
                                        <th>Project</th>
                                        <th>Model Unit</th>
                                        <th>TCP</th>
                                        <th>Discount</th>
                                        <th>Financing</th>
                                        <th>Rate Given</th>
                                    </tr>
                                    <tr>
                                        <td>{{\Carbon\Carbon::create($commissionRequest->sales->reservation_date)->format('F-d-Y')}}</td>
                                        <td>{{\Carbon\Carbon::create($lastDueDate)->format('F-d-Y')}}</td>
                                        <td>{{$commissionRequest->sales->lead->fullname}}</td>
                                        <td>{{$commissionRequest->sales->project->name}}</td>
                                        <td>{{$commissionRequest->sales->modelUnit->name}}</td>
                                        <td>{{number_format($commissionRequest->sales->total_contract_price,2)}}</td>
                                        <td>{{number_format($commissionRequest->sales->discount,2)}}</td>
                                        <td>{{$commissionRequest->sales->financing}}</td>
                                        <td>{{$rateGiven}}%</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="post">
                                <h5>Requirements <span class="text-info">({{\App\Services\ClientRequirementsService::clientRequirements($commissionRequest->sales->clientrequirements)}})</span></h5>

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
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-2 order-1 order-md-2">
                    <h6 class="text-primary"><i class="fas fa-user-tie"></i> Requester Details</h6>

                    <div class="text-muted">
                        <p class="text-sm">Agent Name
                            <b class="d-block">{{$commissionRequest->user->fullname}}</b>
                        </p>
                        <p class="text-sm">Email
                            <b class="d-block">{{$commissionRequest->user->email}}</b>
                        </p>
                        <p class="text-sm">Contact Number
                            <b class="d-block">{{$commissionRequest->user->mobileNo}}</b>
                        </p>
{{--                        days passes: {{collect($byPass)->where('upLine_id',auth()->user()->id)->first()['daysPasses']}}<br/>--}}
{{--                        days  by pass: {{collect($byPass)->where('upLine_id',auth()->user()->id)->first()['daysByPass']}}<br/>--}}
{{--                        by pass user: {{collect($byPass)->where('upLine_id',auth()->user()->id)->first()['byPassConsent'] ? "yes" : "no"}}<br/>--}}
{{--                        allow approve and reject: {{collect($byPass)->where('upLine_id',auth()->user()->id)->first()['AllowByPassApproveAndReject'] ? "yes" : "no"}}<br/>--}}

                        @if(collect($byPass)->where('upLine_id',auth()->user()->id)->first()['finalConsent']
                        || (auth()->user()->hasRole('Finance Admin') && $commissionRequest->status == "for review"))
                            <div class="text-center mt-5 mb-3">
                                @if($commissionRequest->status != "for review" && $commissionRequest->status != "requested to developer")
                                    <button class="btn btn-sm btn-primary approval-btn" id="approve-btn" data-toggle="modal" data-target="#approve">Approve</button>
                                    @else
                                    <button class="btn btn-sm bg-purple approval-btn" id="approve-btn" data-toggle="modal" data-target="#approve">Request to Developer</button>
                                @endif
                                <button class="btn btn-sm btn-danger approval-btn" id="reject-btn" data-toggle="modal" data-target="#approve">Reject</button>
                            </div>
                        @endif


                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    </div>


    @if(collect($byPass)->where('upLine_id',auth()->user()->id)->first()['finalConsent']
    || (auth()->user()->hasRole('Finance Admin') && $commissionRequest->status == "for review"))
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
    <script>
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
