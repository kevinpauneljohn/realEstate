@extends('adminlte::page')

@section('title', 'DHG Project Profile')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">DHG Project Profile</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">DHG Project Profile</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">


                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                        <div class="row">
                            <div class="col-12">
                                <div class="post">
                                    <h4 class="text-blue">Latest Activity</h4>
                                    <p>
                                        Lorem ipsum represents a long-held tradition for designers,
                                        typographers and the like. Some people hate it and argue for
                                        its demise, but others ignore.
                                    </p>

                                    <p>
                                        <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v2</a>
                                    </p>
                                </div>

                                <div class="post clearfix">
                                    <h4 class="text-blue">Payment History</h4>

                                    @can('add client payment')
                                        <p>
                                            <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#add-new-client-payment">Add Payment</button>
                                        </p>
                                    @endcan

                                    <table id="payment-list" class="table table-bordered table-striped" role="grid">
                                        <thead>
                                        <tr role="row">
                                            <th>Date Of Payment</th>
                                            <th>Amount</th>
                                            <th>Description</th>
                                            <th>Remarks</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>

                                        <tfoot>
                                        <tr>
                                            <th>Date Of Payment</th>
                                            <th>Amount</th>
                                            <th>Description</th>
                                            <th>Remarks</th>
                                            <th>Action</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                        <h3 class="text-primary"><i class="fas fa-code"></i> {!! $project_code !!}</h3>
                        <p class="text-muted">
                            {!! $client_project->description !!}
                        </p>
                        <br>
                        <div class="text-muted">
                            <p class="text-sm">Client Name
                                <b class="d-block">{{$client_project->client->fullName}}</b>
                            </p>
                            <p class="text-sm">Architect
                                <b class="d-block">{{$client_project->architect->fullName}}</b>
                            </p>
                            <p class="text-sm">Builder
                                <b class="d-block">{{$client_project->builder->name}}</b>
                            </p>
                        </div>

                        <h5 class="mt-5 text-muted">Project files</h5>
                        <ul class="list-unstyled">
                            <li>
                                <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i> Functional-requirements.docx</a>
                            </li>
                            <li>
                                <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-pdf"></i> UAT.pdf</a>
                            </li>
                            <li>
                                <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-envelope"></i> Email-from-flatbal.mln</a>
                            </li>
                            <li>
                                <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-image "></i> Logo.png</a>
                            </li>
                            <li>
                                <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i> Contract-10_12_2014.docx</a>
                            </li>
                        </ul>
                        <div class="text-center mt-5 mb-3">
                            <a href="#" class="btn btn-sm btn-primary">Add files</a>
                            <a href="#" class="btn btn-sm btn-warning">Report contact</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->

    @can('add client payment')
        <!--add new payment modal-->
        <div class="modal fade" id="add-new-client-payment">
            <form role="form" id="add-client-payment-form" class="form-submit">
                @csrf
                <input type="hidden" name="dhg_project_id" value="{{$client_project->id}}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Payment</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="form-group date_received">
                                <label for="date_received">Date of Payment</label>
                                <input type="date" name="date_received" class="form-control" id="date_received">
                            </div>

                            <div class="form-group amount">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" class="form-control" id="amount" step="any" min="0">
                            </div>
                            <div class="form-group description">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control" id="description"></textarea>
                            </div>
                            <div class="form-group remarks">
                                <label for="remarks">Remarks</label>
                                <textarea name="remarks" class="form-control" id="remarks"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary dhg-client-project-form-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new payment modal-->
    @endcan

    @can('edit client payment')
        <!--add new payment modal-->
        <div class="modal fade" id="check-admin-credential-modal">
            <form role="form" id="check-admin-credential-form" class="form-submit">
                @csrf
                <input type="hidden" name="payment_id">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Enter your password</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group password">
                                <input type="password" name="password" class="form-control" id="password">
                            </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary check-admin-credential-form-btn" value="Send">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new payment modal-->
    @endcan

@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.css')}}">
    <style type="text/css">

    </style>
@stop

@section('js')
    @can('view user')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('js/dhg-project.js')}}"></script>
        <script>
            $(function() {
                $('#payment-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('client.payment.list',['project' => $client_project->id]) !!}',
                    columns: [
                        { data: 'date_received', name: 'date_received'},
                        { data: 'amount', name: 'amount'},
                        { data: 'details', name: 'details'},
                        { data: 'remarks', name: 'remarks'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });
        </script>
    @endcan
@stop
