@extends('adminlte::page')

@section('title', 'Rank')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Time Sheet</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Time Sheet</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary" data-toggle="modal" data-target="#ShowCalendar">Calendar</button>
            </div>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table id="rank-list" class="table table-bordered table-striped" role="grid">
                        <thead>
                        <tr role="row">
                            <th></th>
                            <th width='15%'>User Id</th>
                            <th width="15%">Time In</th>
                            <th width="15%">Break In</th>
                            <th width="15%">Break Out</th>
                            <th width="15%">Time Out</th>
                            <th width='15%'>Late(TI)</th>
                            <th width='15%'>Late(BFL)</th>
                            <th width='15%'>Hours Rendered</th>
                           
                        </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--@can('add rank')
        //<!--add new rank modal-
        <div class="modal fade" id="create-rank-modal">
            <form role="form" id="rank-form" class="form-submit">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Rank</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group rank">
                                <label for="rank">Rank Name</label><span class="required">*</span>
                                <input type="text" name="rank" class="form-control" id="rank">
                            </div>
                            <div class="form-group">
                                <label for="start_points">Points</label><span class="required">*</span>
                                <div class="row">
                                    <div class="col-lg-6 start_points">
                                        <input type="number" step="0.1" name="start_points" class="form-control" id="start_points" placeholder="Start Points">
                                    </div>
                                    <div class="col-lg-6 end_points">
                                        <input type="number" step="0.1" name="end_points" class="form-control" id="end_points" placeholder="End Points">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group time_line">
                                <label for="time_line">Timeline</label>
                                <select class="form-control" id="time_line" name="time_line">
                                    <option value=""> -- Select -- </option>
                                    <option value="lifetime">Lifetime</option>
                                    <option value="1 year">1 year</option>
                                    <option value="2 years">2 year</option>
                                    <option value="3 years">3 year</option>
                                    <option value="6 months">6 months</option>
                                </select>
                            </div>
                            <div class="form-group description">
                                <label for="description">Description</label><span class="required">*</span>
                                <textarea class="form-control" name="description" id="description"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary rank-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new rank modal-->
    @endcan-->

    <!--@can('edit rank')
        <!--add new rank modal--
        <div class="modal fade" id="edit-rank-modal">
            <form role="form" id="edit-rank-form" class="form-submit">
                @csrf
                @method('PUT')
                <input type="hidden" name="rank_id" id="rank_id">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Rank</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group edit_rank">
                                <label for="edit_rank">Rank Name</label><span class="required">*</span>
                                <input type="text" name="edit_rank" class="form-control" id="edit_rank">
                            </div>
                            <div class="form-group">
                                <label for="edit_start_points">Points</label><span class="required">*</span>
                                <div class="row">
                                    <div class="col-lg-6 edit_start_points">
                                        <input type="number" step="0.1" name="edit_start_points" class="form-control" id="edit_start_points" placeholder="Start Points">
                                    </div>
                                    <div class="col-lg-6 edit_end_points">
                                        <input type="number" step="0.1" name="edit_end_points" class="form-control" id="edit_end_points" placeholder="End Points">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group edit_time_line">
                                <label for="edit_time_line">Timeline</label>
                                <select class="form-control" id="edit_time_line" name="edit_time_line">
                                    <option value=""> -- Select -- </option>
                                    <option value="lifetime">Lifetime</option>
                                    <option value="1 year">1 year</option>
                                    <option value="2 years">2 year</option>
                                    <option value="3 years">3 year</option>
                                    <option value="6 months">6 months</option>
                                </select>
                            </div>
                            <div class="form-group edit_description">
                                <label for="edit_description">Description</label><span class="required">*</span>
                                <textarea class="form-control" name="edit_description" id="edit_description"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary rank-btn-edit" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content --
                </div>
                <!-- /.modal-dialog --
            </form>
        </div>
        <!--end add new rank modal--
    @endcan-->

@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <style type="text/css">
    </style>
@stop

