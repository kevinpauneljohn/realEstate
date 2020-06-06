@extends('adminlte::page')

@section('title', 'Transaction History')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Transaction History</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Transaction History</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container" style="max-width: 1200px;">
        <div class="card">
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table id="transaction-list" class="table table-hover" role="grid">
                        <thead>
                        <tr role="row">
                            <th>Date</th>
                            <th>Details</th>
                            <th>Request #</th>
                            <th>Source</th>
                            <th>Status</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th>Date</th>
                            <th>Details</th>
                            <th>Request #</th>
                            <th>Source</th>
                            <th>Status</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>


    @can('withdraw money')
        <div class="modal fade" id="withdraw-money-modal">
            <form role="form" id="source-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Withdraw Money</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-withdraw-btn" value="Save">
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
        .request-pending{
            background-color:#e8f8fe;
        }
    </style>
@stop

@section('js')
    @can('view wallet')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('js/validation.js')}}"></script>
        <script src="{{asset('js/wallet.js')}}"></script>
        <script>
            $(function() {
                $('#transaction-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('transaction.list') !!}',
                    columns: [
                        { data: 'created_at', name: 'created_at'},
                        { data: 'details', name: 'details'},
                        { data: 'cash_request_id', name: 'cash_request_id'},
                        { data: 'category', name: 'category'},
                        { data: 'status', name: 'status'},
                    ],
                    responsive:true,
                    order:[2,'desc']
                });
            });
        </script>
    @endcan
@stop
