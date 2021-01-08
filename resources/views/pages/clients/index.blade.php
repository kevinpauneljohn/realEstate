@extends('adminlte::page')

@section('title', 'Clients')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Clients</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Clients</li>
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
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th>Full Name</th>
                            <th>Address</th>
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
                            <div class="form-group remarks">
                                <label>Remarks</label>
                                <textarea class="form-control" name="remarks" id="remarks"></textarea>
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
                    { data: 'user_address', name: 'user_address'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'asc']
            });
        });
    </script>
    @can('delete client')
        <script>
            $(document).on('click','.delete-client-btn',function(){
                rowId = this.id;
                $('#sign-in-password-modal').modal('toggle');
            });

            $(document).on('submit','#sign-in-form',function(form){
                form.preventDefault();
                let data = $(this).serializeArray();

                $.ajax({
                    'url'   : '/admin/credential',
                    'type'  : 'POST',
                    'data'  : data,
                    beforeSend: function(){
                        $('.send-pw-btn').val('Sending ... ').attr('disabled',true);
                    },success: function(result){
                        console.log(result);
                        if(result.success === true)
                        {
                            $('#sign-in-form').trigger('reset');
                            $('#sign-in-password-modal').modal('toggle');
                           deleteClient();
                        }else if(result.success === false){
                            toastr.error("You're not allowed to remove the client");
                        }

                        $.each(result, function (key, value) {
                            let element = $('.'+key);

                            element.find('.'+key).remove();
                            element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
                        });

                        $('.send-pw-btn').val('Send').attr('disabled',false);
                    },error: function(xhr,status,error){
                        console.log(xhr);
                    }
                });
            });


            function deleteClient()
            {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            'url' : '/clients/'+rowId,
                            'type' : 'DELETE',
                            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            'data' : {'_method':'DELETE','id' : rowId},
                            beforeSend: function(){

                            },success: function(output){

                                if(output.success === true){
                                    Swal.fire(
                                        'Deleted!',
                                        output.message,
                                        'success'
                                    );

                                    let table = $('#client-list').DataTable();
                                    table.ajax.reload();
                                }else{
                                    toastr.error(output.message);
                                }
                            },error: function(xhr, status, error){
                                console.log(xhr);
                            }
                        });

                    }
                });
            }
        </script>
    @endcan
@stop
