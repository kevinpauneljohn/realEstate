@extends('adminlte::page')

@section('title', 'Add Sales')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Add Sales</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('sales.index')}}">Sales</a></li>
                <li class="breadcrumb-item active">Add Sales</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <a href="{{route('sales.index')}}"><button type="button" class="btn bg-gradient-success btn-sm">View all</button></a>
        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                @if(session('success') === true)
                    <div class="alert alert-success">
                        Successfully Submitted!
                    </div>
                @endif
                <form method="POST" action="{{route('leads.store')}}" class="form-submit">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <!-- Date range -->
                                <div class="form-group edit_reservation_date">
                                    <label>Reservation Date</label><span class="required">*</span>
                                    <input type="text" name="edit_reservation_date" id="edit_reservation_date" class="form-control datemask" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy/mm/dd" data-mask="" im-insert="false">
                                </div>
                                <div class="form-group edit_buyer">
                                    <label for="edit_buyer">Buyer's Name</label><span class="required">*</span>
                                    <select name="edit_buyer" id="edit_buyer" class="form-control select2" style="width: 100%;">
                                        <option value=""> -- Select -- </option>
                                        @foreach($leads ?? '' as $lead)
                                            <option value="{{$lead->id}}">{{ucfirst($lead->firstname)}} {{ucfirst($lead->lastname)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group edit_project">
                                    <label for="edit_project">Project</label><span class="required">*</span>
                                    <select name="edit_project" id="edit_project" class="form-control" style="width: 100%;">
                                        <option value=""> -- Select -- </option>
                                        @foreach($projects as $project)
                                            <option value="{{$project->id}}">{{$project->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group edit_model_unit">
                                    <label for="edit_model_unit">Model Unit</label><span class="required">*</span>
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
                                        <label for="edit_phase">Phase</label>
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
                                    <label>Total Contract Price</label><span class="required">*</span>
                                    <input type="number" name="edit_total_contract_price" id="edit_total_contract_price" class="form-control">
                                </div>
                                <div class="form-group edit_discount">
                                    <label>Discount</label>
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
                                    <label>Financing</label>
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
                                </div>
                                <div class="form-group">
                                    <label for="edit_details">Details</label>
                                    <textarea name="edit_details" id="edit_details" class="textarea" data-min-height="150" placeholder="Place some text here"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                            <div>
                                <button type="submit" class="btn btn-primary submit-form-btn" style="width: 100%">
                                    <i class="spinner fa fa-spinner fa-spin"></i> Save
                                </button>
                            </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{asset('/vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.css')}}">
    <style type="text/css">
        .delete_role{
            font-size: 20px;
        }
    </style>
@stop

@section('js')
    @can('view lead')
        <!-- bootstrap datepicker -->
        <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
        <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
        <script src="{{asset('js/user.js')}}"></script>
        <script src="{{asset('js/formSubmit.js')}}"></script>
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
                        ['insert', ['link']],
                        ['height', ['height']],
                        ['view', ['fullscreen']],
                    ],
                    lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
                });
            })
        </script>
        <script>
            $(function() {
                $('#users-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('users.list') !!}',
                    columns: [
                        { data: 'fullname', name: 'fullname'},
                        { data: 'username', name: 'username'},
                        { data: 'email', name: 'email'},
                        { data: 'mobileNo', name: 'mobileNo'},
                        { data: 'roles', name: 'roles'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'desc']
                });
            });
            //Initialize Select2 Elements
            $('.select2').select2();
            //Date picker
            $('#datepicker').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            }).datepicker("setDate", new Date());
            //Money Euro
            $('[data-mask]').inputmask()
            $('.textarea').html('{!! old('remarks') !!}');
        </script>
    @endcan
@stop
