@extends('adminlte::page')

@section('title', 'Contest')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Contest</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Contest</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="card">

        @can('add contest')
            <div class="card-header">
                <button type="button" class="btn bg-gradient-primary btn-sm add-contest-btn" data-toggle="modal" data-target="#add-new-contest-modal"><i class="fa fa-plus-circle"></i> Add New</button>
            </div>
        @endcan

        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="contest-list" class="table table-bordered table-hover" role="grid">
                    <thead>
                    <tr role="row">
                        <th width="8%">Contest Id</th>
                        <th width="20%">Name</th>
                        <th>Allowed Rank</th>
                        <th>Cash Prize</th>
                        <th>Item Prize</th>
                        <th width="8%">Active</th>
                        <th width="8%">No. of Participants</th>
                        @if(auth()->user()->can('add contest'))
                            <th width="10%">Date Active</th>
                        @endif
                        <th>Winner</th>
                        <th width="10%"></th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th width="8%">Contest Id</th>
                        <th>Name</th>
                        <th width="20%">Allowed Rank</th>
                        <th>Cash Prize</th>
                        <th>Item Prize</th>
                        <th width="8%">Active</th>
                        <th width="8%">No. of Participants</th>
                        @if(auth()->user()->can('add contest'))
                            <th width="10%">Date Active</th>
                        @endif
                        <th>Winner</th>
                        <th></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('add contest')
        <!--add new roles modal-->
        <div class="modal fade" id="add-new-contest-modal">
            <form role="form" id="contest-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Contest</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch" name="is_active" value="1">
                                    <label class="custom-control-label" for="customSwitch">Active</label>
                                </div>
                            </div>
                            <div class="form-group title">
                                <label for="title">Title</label><span class="required">*</span>
                                <input type="text" name="title" class="form-control" id="title">
                            </div>
                            <div class="form-group description">
                                <label for="description">Description</label><span class="required">*</span>
                                <textarea name="description" class="form-control" id="description" style="min-height: 300px;"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="date">Date</label><span class="required">*</span>
                                    <input type="text" name="date_active" class="form-control datemask" id="date_active" value="{{today()->format('Y-m-d')}}" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy/mm/dd" data-mask="" im-insert="false">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-12 rank">
                                    <label for="rank">Rank</label><span class="required">*</span>
                                    <select class="form-control select2" name="rank[]" multiple="multiple" data-placeholder="Select rank" id="rank" style="width:100%;">
                                        <option value=""></option>
                                        @foreach($ranks as $rank)
                                            <option value="{{$rank->id}}">{{$rank->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <div class="form-group amount">
                                        <label for="amount">Amount</label><span class="required">*</span>
                                        <input type="number" name="amount" class="form-control" id="amount" step="0.1" value="0">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group points">
                                        <label for="points">Points</label><span class="required">*</span>
                                        <input type="number" name="points" class="form-control" id="points" step="0.1" value="0">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="form-group item">
                                        <label for="item">Item</label>
                                        <input type="text" name="item" class="form-control" id="item">
                                    </div>
                                </div>
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
@section('right-sidebar')
    <x-custom.right-sidebar />
@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
@stop

@section('js')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <!-- bootstrap datepicker -->
        <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
        <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
        <script src="{{asset('/vendor/daterangepicker/daterangepicker.js')}}"></script>
        <script src="{{asset('/vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
        <script src="{{asset('js/custom-alert.js')}}"></script>
        <script src="{{asset('js/contest.js')}}"></script>
        <script>
            $(function() {
                $('#contest-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('contest.list') !!}',
                    columns: [
                        { data: 'id', name: 'id'},
                        { data: 'name', name: 'name'},
                        { data: 'rank', name: 'rank'},
                        { data: 'cash', name: 'cash'},
                        { data: 'item', name: 'item'},
                        { data: 'active', name: 'active'},
                        { data: 'participants', name: 'participants'},
                        @if(auth()->user()->can('add contest'))
                            { data: 'date_working', name: 'date_working'},
                        @endif
                        { data: 'user_id', name: 'user_id'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'desc'],
                    pageLength: 50
                });
            });

            $('#date_active').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            }).datepicker("setDate", new Date());

            //Initialize Select2 Elements
            $('.select2').select2({
                allowClear: true
            });
        </script>
    @stop
