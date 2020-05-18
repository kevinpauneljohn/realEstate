@extends('adminlte::page')

@section('title', 'Leads')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Leads</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Leads</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fab fa-hotjar"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Hot</span>
                    <span class="info-box-number">{{$total_hot_leads}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-fire"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Warm</span>
                    <span class="info-box-number">{{$total_warm_leads}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-snowflake"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Cold</span>
                    <span class="info-box-number">{{$total_cold_leads}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-user-plus"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Qualified</span>
                    <span class="info-box-number">{{$total_qualified_leads}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <div class="row">
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-gray-dark"><i class="fas fa-user-minus"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Inquiry Only</span>
                    <span class="info-box-number">{{$total_inquiry_only_leads}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-orange"><i class="fas fa-user-slash"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Not Interested Anymore</span>
                    <span class="info-box-number">{{$total_not_interested_leads}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-purple"><i class="fas fa-street-view"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">For tripping</span>
                    <span class="info-box-number">{{$total_for_tripping_leads}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-user-check"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Reserved</span>
                    <span class="info-box-number">{{$total_reserved_leads}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <div class="card">
        <div class="card-header">
            @can('add lead')
                <a href="{{route('leads.create')}}"><button type="button" class="btn bg-gradient-primary btn-sm"><i class="fa fa-plus-circle"></i> Add New</button></a>
            @endcan

        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">

                <table id="leads-list" class="table table-bordered table-hover" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Date Inquired</th>
                        <th>Name</th>
                        <th>Mobile No.</th>
                        <th>Email</th>
                        <th>Source</th>
                        <th>Important</th>
                        <th>Lead Status</th>
                        <th>Last Contacted</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th>Date Inquired</th>
                        <th>Name</th>
                        <th>Mobile No.</th>
                        <th>Email</th>
                        <th>Source</th>
                        <th width="5%">Important</th>
                        <th width="12%">Lead Status</th>
                        <th>Last Contacted</th>
                        <th width="12%">Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('delete lead')
        <!--delete permission-->
        <div class="modal fade" id="delete-lead-modal">
            <form role="form" id="delete-lead-form">
                @csrf
                @method('DELETE')
                <input type="hidden" name="deleteLeadId" id="deleteLeadId">
                <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                        <div class="modal-body">
                            <p class="delete_lead">Delete Lead: <span class="delete-lead-name"></span></p>
                            <p class="lead-details"></p>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-outline-light">Delete</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end delete permission modal-->
    @endcan

    @can('edit lead')
        <!--add new schedule modal-->
        <div class="modal fade" id="lead-details">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Lead Details</h4>
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
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--end add new schedule modal-->
    @endcan

    @can('edit lead')
        <!--add new schedule modal-->
        <div class="modal fade" id="set-status">
            <form role="form" id="change-status-form" class="form-submit">
                @csrf
                <input type="hidden" name="lead_url" value="{{url()->current()}}">
                <input type="hidden" name="lead_id" id="lead_id">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Change Lead Status</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group status">
                                <label for="status">Status</label><span class="required">*</span>
                                <select class="change-status form-control" name="status" id="status">
                                @php
                                $status = array('Hot','Warm','Cold','Qualified','Not qualified','For tripping','Inquiry Only','Not Interested Anymore');
                                $data = '';

                                    foreach ($status as $stats)
                                    {

                                    if($stats === 'Hot' || $stats === 'Warm' || $stats === 'Cold')
                                    {
                                    $disabled = "disabled";
                                    }else{
                                    $disabled = "";
                                    }

                                    $data.= '<option value="'.$stats.'" '.$disabled.'>'.$stats.'</option>';
                                    }
                                echo $data;
                                @endphp
                                </select>
                            </div>
                            <div class="form-group notes">
                                <label for="notes">Details</label><span class="required">*</span>
                                <textarea class="form-control" name="notes" id="notes"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Save</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new schedule modal-->
    @endcan

@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

    <style type="text/css">
        table{
            table-layout:fixed!important;
        }
        table td{
            word-wrap: break-word;
        }

    </style>
@stop

@section('js')
    @can('view lead')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('js/lead.js')}}"></script>
        <script src="{{asset('vendor/moment/moment.min.js')}}"></script>
        <script src="{{asset('vendor/daterangepicker/daterangepicker.js')}}"></script>
        <script src="{{asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
        <script>
            $(function() {
                $('#leads-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('leads.list') !!}',
                    columns: [
                        { data: 'date_inquired', name: 'date_inquired'},
                        { data: 'fullname', name: 'fullname'},
                        { data: 'mobileNo', name: 'mobileNo'},
                        { data: 'email', name: 'email'},
                        { data: 'point_of_contact', name: 'point_of_contact'},
                        { data: 'important', name: 'important'},
                        { data: 'lead_status', name: 'lead_status'},
                        { data: 'last_contacted', name: 'last_contacted'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'desc']
                });
            });
            //Initialize Select2 Elements
            $('.select2').select2();

            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                locale: {
                    format: 'MM/DD/YYYY hh:mm A'
                }
            })
        </script>
    @endcan
@stop
