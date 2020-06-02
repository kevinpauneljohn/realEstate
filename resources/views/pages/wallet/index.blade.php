@extends('adminlte::page')

@section('title', 'Wallet')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Wallet</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Wallet</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container" style="max-width: 1000px;">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-money-bill-alt"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Current Balance</span>
                        <span class="info-box-number">&#8369; {{number_format($current_balance,2)}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fa fa-money-bill-alt"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Remaining Balance</span>
                        <span class="info-box-number">&#8369; {{$remaining_balance}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fa fa-money-bill-alt"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Cash Advances</span>
                        <span class="info-box-number">&#8369; {{number_format(0,2)}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
        </div>


        <div class="card">
            <div class="card-header">
                @can('withdraw money')
                    <button type="button" class="btn bg-primary btn-sm" data-toggle="modal" data-target="#withdraw-money-modal">Withdraw Money</button>
                @endcan

            </div>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table id="wallet-list" class="table table-hover" role="grid">
                        <thead>
                        <tr role="row">
                            <th>Date Received</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Sender</th>
                            <th>Status</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th>Date Received</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Sender</th>
                            <th>Status</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>


    @can('wihdraw money')
        <div class="modal fade" id="withdraw-money-modal">
            <form role="form" id="contact-form" class="form-submit">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Withdraw Money</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group title">
                                <label for="title">Title</label><span class="required">*</span>
                                <input type="text" name="title" class="form-control" id="title">
                            </div>
                            <div class="form-group contact_person">
                                <label for="contact_person">Contact Person</label><span class="required">*</span>
                                <input type="text" name="contact_person" class="form-control" id="contact_person">
                            </div>
                            <div class="form-group contact_details">
                                <label for="contact_details">Contact Details</label>
                                <textarea class="form-control" name="contact_details" id="contact_details" style="min-height: 150px;"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-contact-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end contacts modal-->
    @endcan


@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <style type="text/css">

    </style>
@stop

@section('js')
    @can('view wallet')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('js/validation.js')}}"></script>
        <script src="{{asset('js/contact.js')}}"></script>
        <script>
            $(function() {
                $('#wallet-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('wallet.list') !!}',
                    columns: [
                        { data: 'created_at', name: 'created_at'},
                        { data: 'amount', name: 'amount'},
                        { data: 'description', name: 'description'},
                        { data: 'category', name: 'category'},
                        { data: 'sender', name: 'sender'},
                        { data: 'status', name: 'status'},
                        // { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'desc']
                });
            });
        </script>
    @endcan
@stop
