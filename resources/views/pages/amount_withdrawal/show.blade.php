@extends('adminlte::page')

@section('title', 'Withdrawal Request')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Withdrawal Request</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Withdrawal Request</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container" style="max-width: 1000px;">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-ticket-alt"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Request Number</span>
                        <span class="info-box-number text-primary">#{{str_pad($cashRequestId, 5, '0', STR_PAD_LEFT)}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-purple"><i class="fa fa-info-circle"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Request</span>
                        <span class="info-box-number text-danger">{{$cashRequests->count()}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-money-bill-alt"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Amount Requested</span>
                        <span class="info-box-number text-danger">&#8369; {{$cashRequests->sum('requested_amount')}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
        </div>
        @foreach($cashRequests->get() as $cashRequest)
            <form class="cash-request-form">
                <input type="hidden" name="cash_request_id" value="{{$cashRequestId}}">
                <input type="hidden" name="amount_withdrawal_id" value="{{$cashRequest->id}}">
                @csrf
            <div class="card">
                <div class="card-header">
                    <span class="float-right"><i class="fa fa-calendar-alt"></i> {{$cashRequest->created_at->format('M d, Y h:i a')}}</span>
                    <span>Requester</span>: <strong class="text-primary" style="margin-right:50px;">{{ucfirst($cashRequest->wallet->user->fullname)}}</strong>
                    <span>Category</span>: <strong class="text-info">{{ucfirst($cashRequest->wallet->category)}}</strong>
                </div>
                <div class="card-body">
                    <h6 class="text-muted">Source Description</h6>
                    <p>{{ucfirst($cashRequest->wallet->details->description)}}</p>
                    <hr/>
                    <p>
                        <span>Original Amount</span>: <strong class="text-primary">&#8369; {{number_format($cashRequest->original_amount,2)}}</strong><br/>
                        <span>Requested Amount</span>: <strong class="text-success">&#8369; {{number_format($cashRequest->requested_amount,2)}}</strong><br/>
                        <span>Balance (if granted)</span>: <strong class="text-danger">&#8369; {{number_format($cashRequest->original_amount - $cashRequest->requested_amount,2)}}</strong>
                    </p>
                    <input type="hidden" name="amount_withdrawal_id" value="{{$cashRequest->id}}">
                        <div class="form-group action">
                            <label for="action">Action</label>
                            <select class="form-control" name="action" style="width:200px;" id="action-{{$cashRequest->id}}">
                                <option value=""> -- Select action -- </option>
                                <option value="reject">Reject</option>
                                <option value="approve">Approve</option>
                            </select>
                        </div>
                    <div class="form-group extra_field">
                        <label class="text-muted">Extra Field (Optional)</label>
                        <table class="table table-bordered" id="table-{{$cashRequest->id}}">
                            <thead>
                            <tr>
                                <th>Amount</th>
                                <th>Description</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="extra-row-{{$cashRequest->id}} table-row">
                                <td width="20%"><input type="text" class="form-control" name="extra_amount[]"></td>
                                <td width="68%"><input type="text" class="form-control" name="extra_description[]"></td>
                                <td>
                                    <input type="hidden" class="extra_field_id" value="{{$cashRequest->id}}">
                                    <button type="button" class="btn btn-danger btn-sm float-right minus" style="margin:2px;"><i class="fa fa-minus"></i></button>
                                    <button type="button" class="btn btn-success btn-sm float-right plus" style="margin:2px;"><i class="fa fa-plus"></i></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                        <div class="form-group remarks">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control" name="remarks" style="min-height: 150px;"></textarea>
                        </div>
                    <input type="submit" class="btn btn-primary" value="Submit" style="width: 100%;">
                </div>
            </div>
        </form>
        @endforeach

    </div>

    @can('add role')
        <!--add new roles modal-->
        <div class="modal fade" id="add-new-role-modal">
            <form role="form" id="role-form" class="form-submit">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Role</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group role">
                                <label for="role">Role Name</label><span class="required">*</span>
                                <input type="text" name="role" class="form-control" id="role">
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
        <!--end add new roles modal-->
    @endcan


@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <style type="text/css">
        .delete_role{
            font-size: 20px;
        }
    </style>
@stop

@section('js')
    @role('super admin')
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/cash-request.js')}}"></script>
    <script>
        $(function() {
            $('#cash-request-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('cash.list') !!}',
                columns: [
                    { data: 'id', name: 'id'},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'user_id', name: 'user_id'},
                    { data: 'status', name: 'status'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'desc']
            });
        });
    </script>
    @endrole
@stop
