@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Commissions</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('users.profile',['user' => $user->id])}}">{{ucfirst($user->firstname)}} {{ucfirst($user->lastname)}}</a></li>
                <li class="breadcrumb-item active">Commissions</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            @can('add user')
                <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-new-user-modal"><i class="fa fa-plus-circle"></i> Add New</button>
            @endcan

        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="users-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th>Role</th>
                        <th width="20%">Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('add user')
        <!--add new users modal-->
        <div class="modal fade" id="add-new-user-modal">
            <form role="form" id="user-form">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New User</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4 firstname">
                                    <label for="firstname">First Name</label><span class="required">*</span>
                                    <input type="text" name="firstname" id="firstname" class="form-control">
                                </div>
                                <div class="col-lg-4 middlename">
                                    <label for="middlename">Middle Name</label>
                                    <input type="text" name="middlename" id="middlename" class="form-control">
                                </div>
                                <div class="col-lg-4 lastname">
                                    <label for="lastname">Last Name</label><span class="required">*</span>
                                    <input type="text" name="lastname" id="lastname" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mobileNo">
                                    <label for="mobileNo">Mobile No.</label><span class="required">*</span>
                                    <input type="text" name="mobileNo" id="mobileNo" class="form-control">
                                </div>
                                <div class="col-lg-6 date_of_birth">
                                    <label for="date_of_birth">Date of Birth</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control">
                                </div>
                            </div>
                            <div class="form-group address">
                                <label for="mobileNo">Address</label>
                                <textarea class="form-control" name="address" id="address"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 email">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control">
                                </div>
                                <div class="col-lg-6 username">
                                    <label for="username">Username</label><span class="required">*</span>
                                    <input type="text" name="username" id="username" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 password">
                                    <label for="password">Password</label><span class="required">*</span>
                                    <input type="password" name="password" id="password" class="form-control">
                                </div>
                                <div class="col-lg-6">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new user modal-->
    @endcan

    @can('edit user')
        <!--edit role modal-->
        <div class="modal fade" id="edit-user-modal">
            <form role="form" id="edit-user-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="updateUserId">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update User</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4 edit_firstname">
                                    <label for="edit_firstname">First Name</label><span class="required">*</span>
                                    <input type="text" name="edit_firstname" id="edit_firstname" class="form-control">
                                </div>
                                <div class="col-lg-4 edit_middlename">
                                    <label for="edit_middlename">Middle Name</label>
                                    <input type="text" name="edit_middlename" id="edit_middlename" class="form-control">
                                </div>
                                <div class="col-lg-4 edit_lastname">
                                    <label for="edit_lastname">Last Name</label><span class="required">*</span>
                                    <input type="text" name="edit_lastname" id="edit_lastname" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 edit_mobileNo">
                                    <label for="edit_mobileNo">Mobile No.</label><span class="required">*</span>
                                    <input type="text" name="edit_mobileNo" id="edit_mobileNo" class="form-control">
                                </div>
                                <div class="col-lg-6 edit_date_of_birth">
                                    <label for="edit_date_of_birth">Date of Birth</label>
                                    <input type="date" name="edit_date_of_birth" id="edit_date_of_birth" class="form-control">
                                </div>
                            </div>
                            <div class="form-group edit_address">
                                <label for="edit_address">Address</label>
                                <textarea class="form-control" name="edit_address" id="edit_address"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 edit_email">
                                    <label for="edit_email">Email</label>
                                    <input type="email" name="edit_email" id="edit_email" class="form-control">
                                </div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add user modal-->
    @endcan

    @can('delete user')
        <!--delete user-->
        <div class="modal fade" id="delete-user-modal">
            <form role="form" id="delete-user-form">
                @csrf
                @method('DELETE')
                <input type="hidden" name="deleteUserId" id="deleteUserId">
                <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                        <div class="modal-body">
                            <p class="delete_user">Delete User: <span class="delete-user-name"></span></p>
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
        <!--end delete user modal-->
    @endcan
@stop
@section('right-sidebar')
    <x-custom.right-sidebar />
@stop
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <style type="text/css">
        .delete_role{
            font-size: 20px;
        }
    </style>
@stop

@section('js')
    @can('view user')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('js/user.js')}}"></script>
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
        </script>
    @endcan
@stop
