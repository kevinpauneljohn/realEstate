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

    <div class="card" id="add-sale-container">
        <div class="card-header">
            <a href="{{route('sales.index')}}"><button type="button" class="btn bg-gradient-success btn-sm">View all</button></a>
        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <form method="POST" action="{{route('leads.store')}}" class="form-submit" id="add-sales-form">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <!-- Date range -->
                                <div class="form-group reservation_date">
                                    <label>Reservation Date</label><span class="required">*</span>
                                    <input type="text" name="reservation_date" id="reservation_date" class="form-control datemask" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy/mm/dd" data-mask="" im-insert="false" value="{{today()->format('Y-m-d')}}">
                                </div>
                                <div class="form-group buyer">
                                    <label for="buyer">Buyer's Name</label><span class="required">*</span>
                                    <select name="buyer" id="buyer" class="form-control select2" style="width: 100%;">
                                        <option value=""> -- Select -- </option>
                                        @foreach($leads ?? '' as $lead)
                                            <option value="{{$lead->id}}" @if($lead->id === $leadId) selected @endif>{{ucfirst($lead->firstname)}} {{ucfirst($lead->lastname)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group project">
                                    <label for="project">Project</label><span class="required">*</span>
                                    <select name="project" id="project" class="form-control select2" style="width: 100%;">
                                        <option value=""> -- Select -- </option>
                                        @foreach($projects as $project)
                                            <option value="{{$project->id}}">{{$project->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group model_unit">
                                    <label for="model_unit">Model Unit</label><span class="required">*</span>
                                    <select name="model_unit" id="model_unit" class="form-control" style="width: 100%;">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6 lot_area">
                                        <label for="lot_area">Lot Area</label>
                                        <input type="text" name="lot_area" id="lot_area" class="form-control" />
                                    </div>
                                    <div class="form-group col-lg-6 floor_area">
                                        <label for="floor_area">Floor Area</label>
                                        <input type="text" name="floor_area" id="floor_area" class="form-control" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4 phase">
                                        <label for="phase">Phase</label> <i class="text-muted">(optional)</i>
                                        <input type="text" name="phase" id="phase" class="form-control" />
                                    </div>
                                    <div class="form-group col-lg-4 block_number">
                                        <label for="block_number">Block</label>
                                        <input type="text" name="block_number" id="block_number" class="form-control" />
                                    </div>
                                    <div class="form-group col-lg-4 lot_number">
                                        <label for="lot_number">Lot</label>
                                        <input type="text" name="lot_number" id="lot_number" class="form-control" />
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-4">
                                <div class="form-group total_contract_price">
                                    <label>Total Contract Price</label><span class="required">*</span>
                                    <input step="any" type="number" name="total_contract_price" id="total_contract_price" class="form-control">
                                </div>
                                <div class="form-group discount">
                                    <label>Discount</label>
                                    <input step="any" type="number" name="discount" id="discount" class="form-control" value="0">
                                </div>
                                <div class="form-group processing_fee">
                                    <label for="processing_fee">Processing Fee</label>
                                    <input step="any" type="number" name="processing_fee" id="processing_fee" class="form-control" value="0">
                                </div>
                                <div class="form-group reservation_fee">
                                    <label>Reservation Fee</label>
                                    <input step="any" type="number" name="reservation_fee" id="reservation_fee" class="form-control" value="0">
                                </div>
                                <div class="form-group equity">
                                    <label>Equity/Down Payment</label>
                                    <input step="any" type="number" name="equity" id="equity" class="form-control" value="0">
                                </div>
                                <div class="form-group loanable_amount">
                                    <label>Loanable Amount</label>
                                    <input step="any" type="number" name="loanable_amount" id="loanable_amount" class="form-control" value="0">
                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="form-group financing">
                                    <label>Financing</label>
                                    <select name="financing" id="financing" class="form-control select2" style="width: 100%;">
                                        <option value=""> -- Select -- </option>
                                        <option value="Cash">Cash</option>
                                        <option value="INHOUSE">INHOUSE</option>
                                        <option value="HDMF">HDMF</option>
                                        <option value="Bank">Bank</option>
                                    </select>
                                </div>
                                <div class="form-group dp_terms">
                                    <label for="dp_terms">Equity / Down Payment Terms</label>
{{--                                    <input type="text" name="dp_terms" id="dp_terms" class="form-control">--}}
                                    <select name="dp_terms" id="dp_terms" class="form-control">
                                        <option value=""> -- Select Terms -- </option>
                                        @for($months = 1; $months <= 60; $months++)
                                            <option value="{{$months}}"> {{$months}} month/s</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="details">Details</label>
                                    <textarea name="details" id="details" class="textarea" data-min-height="150" placeholder="Place some text here"></textarea>
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
        <script src="{{asset('js/sales.js')}}"></script>
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
            $('#reservation_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            }).datepicker("setDate", new Date());
            //Money Euro
            $('[data-mask]').inputmask()
            $('.textarea').html('{!! old('remarks') !!}');
        </script>
    @endcan
@stop
