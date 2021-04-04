@extends('adminlte::page')

@section('title', 'Tasks Profile')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Bookings</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Bookings</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-ticket-alt"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Open</span>
                    <span class="info-box-number">1</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-yellow elevation-1"><i class="fas fa-ticket-alt"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Pending</span>
                    <span class="info-box-number">2</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-maroon elevation-1"><i class="fas fa-ticket-alt"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">On-going</span>
                    <span class="info-box-number">{3</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-ticket-alt"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Completed</span>
                    <span class="info-box-number">4</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn bg-primary btn-sm add-new-task mr-1" data-toggle="modal" data-target="#calendar-modal">Check Availability</button>
        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="staycation-clients-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Task #</th>
                        <th>Due Date</th>
                        <th>Title</th>
                        <th>Priority</th>
                        <th>Assigned To</th>
                        <th>Creator</th>
                        <th>Date Created</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th>Task #</th>
                        <th>Due Date</th>
                        <th>Title</th>
                        <th>Priority</th>
                        <th>Assigned To</th>
                        <th>Creator</th>
                        <th>Date Created</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('add staycation client')
        <!--add new roles modal-->
        <div class="modal fade" id="calendar-modal">
            <form role="form" id="client-booking-form">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Schedule Booking</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Availability:</label>

                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input name="bookings" type="text" class="form-control float-right" id="reservationtime">
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-task-btn" value="Save" disabled="disabled">
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
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <style>
        #remarks-list_length, #remarks-list_filter, .dataTables_info{
            display:none;
        }
        .dataTables_wrapper {
            overflow-x: hidden;
        }
    </style>
@stop

@section('js')
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('/vendor/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('/vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
    <script src="{{asset('js/custom-alert.js')}}"></script>
    <script>
        $('[data-mask]').inputmask();

        $('#reservationtime').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
                format: 'MM/DD/YYYY hh:mm A'
            }
        });

        let bookingForm = $('#client-booking-form');
        $(document).on('change','input[name=bookings]',function(){
            let schedule = this.value;

            $.ajax({
                'url' : '/staycation/availability',
                'type' : 'GET',
                'data' : {
                    'schedule' : schedule
                },
                beforeSend: function(){

                },success: function(response){
                    console.log(response);
                    bookingForm.find('.modal-body .row').after(clientFormContent());
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        });

        function clientFormContent()
        {
            return `<div class="row client-form">
                        <div class="col-lg-6">
                            <div class="form-group firstname">
                            <label for="firstname">Firstname</label><span class="required">*</span>
                            <input type="text" name="firstname" id="firstname" class="form-control">
                            </div>
                        </div>
                    <div class="col-lg-6">
                        <div class="form-group lastname">
                            <label for="lastname">Lastname</label><span class="required">*</span>
                            <input type="text" name="lastname" id="lastname" class="form-control">
                            </div>
                        </div>
                    </div>`;
        }
    </script>
@stop
