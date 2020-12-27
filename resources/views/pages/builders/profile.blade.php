@extends('adminlte::page')

@section('title', 'Builder Profile')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Builder Profile</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Builder Profile</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header">
                        <h5>Members</h5>
                    </div>
                    <div class="card-body">
                        @can('add builder member')
                            <button type="button" class="btn btn-default btn-sm" data-target="#builder-member-modal" data-toggle="modal">Add member</button>
                        @endcan

                            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <table id="member-list" class="table table-bordered table-striped" role="grid">
                                    <thead>
                                    <tr role="row">
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>

                                    <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">

            </div>
        </div>
    </section>
    <!-- /.content -->

    @can('add builder member')
        <!--add builder-member modal-->
        <div class="modal fade" id="builder-member-modal">
            <form role="form" id="builder-member-form" class="form-submit">
                @csrf
                <input type="hidden" name="builder" value="{{$builder->id}}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Member</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @php $ctr = 0; @endphp
                            <div class="form-group members">
                                <label for="edit_client">Select Members</label><span class="required">*</span>
                                <select class="select2" name="members[]" id="members" multiple="multiple" data-placeholder="Select a member" style="width: 100%;">
                                    <option></option>

                                    @foreach($members as $member)
                                        <option value="{{$member->id}}"{{ empty($selected[$ctr]) ? '' : ' disabled'}}>{{ucwords($member->firstname.' '.$member->lastname)}}</option>
                                        @php $ctr++; @endphp
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary member-btn" value="Add">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add builder-member modal-->
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
    @can('add builder member')
        <script src="{{asset('js/builder-profile.js')}}"></script>

        <script>
            $(function() {
                $('#member-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('builder.member.list',['builder' => $builder->id]) !!}',
                    columns: [
                        { data: 'name', name: 'name'},
                        { data: 'role', name: 'role'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });
        </script>
    @endcan
@stop
