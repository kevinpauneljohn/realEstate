@extends('adminlte::page')

@section('title', 'DHG Users')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">DHG Users</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">DHG Users</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container" style="max-width: 800px;">
        <div class="card">
            <div class="card-header">
                @can('add contacts')
                    <button type="button" class="btn bg-primary btn-sm" data-toggle="modal" data-target="#add-client-modal">Add</button>
                @endcan

            </div>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table id="client-list" class="table table-hover" role="grid">
                        <thead>
                        <tr role="row">
                            <th>Full Name</th>
                            <th>Address</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th>Full Name</th>
                            <th>Address</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>


    @can('add client')
        <!--add contacts modal-->
        <div class="modal fade" id="add-client-modal">
            <form role="form" id="client-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Client</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-4 firstname">
                                        <label for="firstname">First Name</label>
                                        <input type="text" name="firstname" id="firstname" class="form-control">
                                    </div>
                                    <div class="col-lg-4 middlename">
                                        <label for="middlename">Middle Name</label>
                                        <input type="text" name="middlename" id="middlename" class="form-control">
                                    </div>
                                    <div class="col-lg-4 lastname">
                                        <label for="lastname">Last Name</label>
                                        <input type="text" name="lastname" id="lastname" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group address">
                                <label for="address">Address</label>
                                <textarea class="form-control" name="address" id="address"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6 username">
                                        <label>Username</label>
                                        <input type="text" name="username" id="username" class="form-control">
                                    </div>
                                    <div class="col-lg-6 email">
                                        <label>Email</label>
                                        <input type="text" name="email" id="email" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6 password">
                                        <label>Password</label>
                                        <input type="password" name="password" id="password" class="form-control">
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Password Confirmation</label>
                                        <input type="password" name="password_confirmation" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 role">
                                    <label for="role">Role</label>
                                    <select class="select2 form-control" name="role" id="role" style="width: 100%;">
                                        <option value=""></option>
                                        <option value="client">Client</option>
                                        <option value="architect">Architect</option>
                                        <option value="builder admin">Builder Admin</option>
                                        <option value="builder member">Builder Member</option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group remarks">
                                        <label>Remarks</label>
                                        <textarea class="form-control" name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-contact-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end contacts modal-->
    @endcan


    @can('edit client')
        <!--edit contacts modal-->
        <div class="modal fade" id="edit-client-modal">
            <form role="form" id="edit-client-form" class="form-submit">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="updateClientId">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Client Details</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-4 edit_firstname">
                                        <label for="edit_firstname">First Name</label>
                                        <input type="text" name="edit_firstname" id="edit_firstname" class="form-control">
                                    </div>
                                    <div class="col-lg-4 edit_middlename">
                                        <label for="edit_middlename">Middle Name</label>
                                        <input type="text" name="edit_middlename" id="edit_middlename" class="form-control">
                                    </div>
                                    <div class="col-lg-4 edit_lastname">
                                        <label for="edit_lastname">Last Name</label>
                                        <input type="text" name="edit_lastname" id="edit_lastname" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group edit_address">
                                <label for="edit_address">Address</label>
                                <textarea class="form-control" name="edit_address" id="edit_address"></textarea>
                            </div>
                            <div class="form-group edit_remarks">
                                <label>Remarks</label>
                                <textarea class="form-control" name="edit_remarks" id="edit_remarks"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-edit-client-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end edit contacts modal-->
    @endcan

    @can('view client')
        <div class="modal fade" id="sign-in-password-modal">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <form id="sign-in-form">
                        @csrf
                    <div class="modal-header">
                        <h6 class="modal-title">Sign-in your password for security</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                            <div class="form-group password">
                                <input type="password" class="form-control" name="password" id="password" required>
                            </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary send-pw-btn" value="Send">
                    </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
        </div>
    @endcan

    @can('edit client')
        <div class="modal fade" id="edit-role-modal">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <form id="edit-role-form">
                        <div class="modal-header">
                            <h6 class="modal-title">Update Role</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="form-group role">
                                <label id="client-name"></label>
                                <select class="select2 form-control" name="role" id="role" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="client">Client</option>
                                    <option value="architect">Architect</option>
                                    <option value="builder admin">Builder Admin</option>
                                    <option value="builder member">Builder Member</option>
                                </select>
                            </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary role-btn" value="Save">
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
        </div>
    @endcan

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
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/validation.js')}}"></script>
    <script src="{{asset('js/client.js')}}"></script>
{{--    <script src="{{asset('js/contact.js')}}"></script>--}}
    <script>
        $(function() {
            $('#client-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('client.list') !!}',
                columns: [
                    { data: 'fullname', name: 'fullname'},
                    { data: 'address', name: 'address'},
                    { data: 'roles', name: 'roles'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'asc']
            });
        });

        //Initialize Select2 Elements
        $('.select2').select2();
    </script>
    @can('edit client')
        <script>

            $(document).on('click','.edit-role-btn',function () {
                rowId = this.id;
                $.ajax({
                    'url' : '/client-info/'+rowId,
                    'type' : 'GET',
                    beforeSend: function(){
                        $('#client-full-name').remove();
                        $('#edit-role-form input, #edit-role-form select').attr('disabled',true);
                        $('.role-btn').val('Saving ... ');
                    },success: function(result){
                        $('#client-name').append('<span id="client-full-name">'+result.firstname+' '+result.lastname+'</span>');
                        $('#edit-role-form #role').val(result.roles[0]['name']).change();

                        $('#edit-role-form input, #edit-role-form select').attr('disabled',false);
                        $('.role-btn').val('Save');
                    },error: function(xhr, status, error){
                        console.log(xhr);
                    }
                });
            });


            $(document).on('submit','#edit-role-form',function(form){
                form.preventDefault();

                let data = $(this).serialize();


                Swal.fire({
                    title: 'Input your password for security',
                    input: 'password',
                    inputAttributes: {
                        autocapitalize: 'off',
                        name: 'password'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Send',
                    showLoaderOnConfirm: true,
                    preConfirm: (login) => {
                        return fetch('/clients/update-role/'+rowId,{
                            method: 'PUT',
                            headers: {
                                'Accept': 'application/json, text/plain, */*',
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
                            },
                            body: data+`&password=${login}`
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(response.statusText)
                                }
                                return response.json()
                            })
                            .catch(error => {
                                Swal.showValidationMessage(
                                    `Request failed: ${error}`
                                )
                            })
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if(result.value.success === true)
                    {
                        toastr.success(result.value.message);

                        $('#client-list').DataTable().ajax.reload();

                        $('#edit-role-modal').modal('toggle').then(()=>{
                            return Swal.fire(
                                'updated!',
                                'Project has been successfully updated.',
                                'success'
                            );
                        });

                    }else if(result.value.success === false && result.value.change === false)
                    {
                        toastr.warning(result.value.message);
                    }
                    $.each(result.value, function (key, value) {
                        let element = $('.edit_'+key);

                        element.find('.error-edit_'+key).remove();
                        element.append('<p class="text-danger error-edit_'+key+'">'+value+'</p>');
                    });
                }).catch((error) => {

                });
            });

        </script>
    @endcan
    @can('delete client')
        <script>

            $(document).on('click','.delete-client-btn',function(){
                let id = this.id;
                let access;

                $tr = $(this).closest('tr');

                let data = $tr.children("td").map(function () {
                    return $(this).text();
                }).get();


                Swal.fire({
                    title: 'Input your password for security',
                    input: 'password',
                    inputAttributes: {
                        autocapitalize: 'off',
                        name: 'password'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Send',
                    showLoaderOnConfirm: true,
                    preConfirm: (login) => {
                        access = login;
                        return fetch('/dhg-project-access',{
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json, text/plain, */*',
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
                            },
                            body: `password=${login}`
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(response.statusText)
                                }
                                return response.json()
                            })
                            .catch(error => {
                                Swal.showValidationMessage(
                                    `Request failed: ${error}`
                                )
                            })
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if(result.value.success === true)
                    {
                        Swal.fire({
                            title: `Delete?&nbsp;<a href="#">#${data[0]}</a>`,
                            text: "You won't be able to revert this!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.value) {

                                $.ajax({
                                    'url' : '/clients/'+id,
                                    'type' : 'DELETE',
                                    'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                    'data' : {'password':access},
                                    beforeSend: function(){

                                    },success: function(output){
                                        if(output.success === true){
                                            $('#client-list').DataTable().ajax.reload();

                                            Swal.fire(
                                                'Deleted!',
                                                output.message,
                                                'success'
                                            );
                                        }
                                    },error: function(xhr, status, error){
                                        console.log(xhr);
                                    }
                                });

                            }
                        });
                    }

                }).catch((error) => {

                });
            });
        </script>
    @endcan
@stop
