@extends('adminlte::page')

@section('title', 'Dream Home Guide Projects')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">DHG Projects</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">DHG Projects</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                @can('add dhg project')
                    <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-new-project-modal"><i class="fa fa-plus-circle"></i> Add Project</button>
                @endcan

            </div>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table id="project-list" class="table table-bordered table-striped" role="grid">
                        <thead>
                        <tr role="row">
                            <th>Project Code</th>
                            <th>Date Started</th>
                            <th>Client Name</th>
                            <th>Builder</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th>Project Code</th>
                            <th>Date Started</th>
                            <th>Client Name</th>
                            <th>Builder</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @can('add dhg project')
        <!--add new dhg project modal-->
        <div class="modal fade" id="add-new-project-modal">
            <form role="form" id="add-project-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Project</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                {{--First Column--}}
                                <div class="col-lg-6">
                                    <div class="form-group date_created">
                                        <label for="date_created">Date Created</label><span class="required">*</span>
                                        <input type="date" name="date_created" class="form-control" id="date_created">
                                    </div>
                                    <div class="form-group client">
                                        <label for="client">Client</label><span class="required">*</span>
                                        <select class="select2" name="client" id="client" data-placeholder="Select a client" style="width: 100%;">
                                            <option value=""></option>
                                            @foreach($clients as $client)
                                                <option value="{{$client['id']}}">{{ucwords($client['firstname'].' '.$client['lastname'])}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group builder">
                                        <label for="builder">Builder</label>
                                        <select class="select2" name="builder" id="builder" data-placeholder="Select a builder" style="width: 100%;">
                                            <option value=""></option>
                                            @foreach($builders as $builder)
                                                <option value="{{$builder['id']}}">{{ucwords($builder['name'])}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group address">
                                        <label for="address">Address</label><span class="required">*</span>
                                        <textarea name="address" id="address" class="form-control" placeholder="Place some text here"></textarea>
                                    </div>
                                    <div class="form-group lot_area">
                                        <label for="lot_area">Lot Area</label>
                                        <input type="number" min="0" name="lot_area" id="lot_area" step="any" class="form-control">
                                    </div>
                                    <div class="form-group floor_area">
                                        <label for="floor_area">Floor Area</label>
                                        <input type="number" min="0" name="floor_area" id="floor_area" step="any" class="form-control">
                                    </div>
                                </div>
                                {{--End of First Column--}}
                                {{--Second Column--}}
                                <div class="col-lg-6">
                                    <div class="form-group lot_price">
                                        <label for="lot_price">Lot Price</label>
                                        <input type="number" class="form-control" name="lot_price" id="lot_price" step="any" min="0">
                                    </div>
                                    <div class="form-group house_price">
                                        <label for="house_price">House Price</label>
                                        <input type="number" class="form-control" name="house_price" id="house_price" step="any" min="0">
                                    </div>
                                    <div class="form-group description">
                                        <label for="description">Description</label><span class="required">*</span>
                                        <textarea name="description" id="description" class="textarea" data-min-height="250" placeholder="Place some text here"></textarea>
                                    </div>
                                </div>
                                {{--End of Second Column--}}
                            </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary dhg-project-form-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new dhg project modal-->
    @endcan

    @can('edit dhg project')
        <!--edit dhg project modal-->
        <div class="modal fade" id="edit-project-modal">
            <form role="form" id="edit-project-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Project</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                {{--First Column--}}
                                <div class="col-lg-6">
                                    <div class="form-group edit_date_created">
                                        <label for="edit_date_created">Date Created</label><span class="required">*</span>
                                        <input type="date" name="edit_date_created" class="form-control" id="edit_date_created">
                                    </div>
                                    <div class="form-group edit_client">
                                        <label for="edit_client">Client</label><span class="required">*</span>
                                        <select class="select2" name="edit_client" id="edit_client" data-placeholder="Select a client" style="width: 100%;">
                                            <option></option>
                                            @foreach($clients as $client)
                                                <option value="{{$client['id']}}">{{ucwords($client['firstname'].' '.$client['lastname'])}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group edit_builder">
                                        <label for="edit_builder">Builder</label>
                                        <select class="select2" name="edit_builder" id="edit_builder" data-placeholder="Select a builder" style="width: 100%;">
                                            <option></option>
                                            @foreach($builders as $builder)
                                                <option value="{{$builder['id']}}">{{ucwords($builder['name'])}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group edit_address">
                                        <label for="edit_address">Address</label><span class="required">*</span>
                                        <textarea name="edit_address" id="edit_address" class="form-control" placeholder="Place some text here"></textarea>
                                    </div>
                                    <div class="form-group edit_lot_area">
                                        <label for="edit_lot_area">Lot Area</label>
                                        <input type="number" min="0" name="edit_lot_area" id="edit_lot_area" step="any" class="form-control">
                                    </div>
                                    <div class="form-group edit_floor_area">
                                        <label for="edit_floor_area">Floor Area</label>
                                        <input type="number" min="0" name="edit_floor_area" id="edit_floor_area" step="any" class="form-control">
                                    </div>
                                </div>
                                {{--End of First Column--}}
                                {{--Second Column--}}
                                <div class="col-lg-6">
                                    <div class="form-group edit_lot_price">
                                        <label for="edit_lot_price">Lot Price</label>
                                        <input type="number" class="form-control" name="edit_lot_price" id="edit_lot_price" step="any" min="0">
                                    </div>
                                    <div class="form-group edit_house_price">
                                        <label for="edit_house_price">House Price</label>
                                        <input type="number" class="form-control" name="edit_house_price" id="edit_house_price" step="any" min="0">
                                    </div>
                                    <div class="form-group edit_description">
                                        <label for="edit_description">Description</label><span class="required">*</span>
                                        <textarea name="edit_description" id="edit_description" class="textarea" data-min-height="250" placeholder="Place some text here"></textarea>
                                    </div>
                                </div>
                                {{--End of Second Column--}}
                            </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary dhg-project-form-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end edit dhg project modal-->
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
    @can('view dhg project')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('js/dhg-project.js')}}"></script>
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
                        ['height', ['height']],
                        ['view', ['fullscreen']],
                    ],
                    // lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
                    lineHeights: ['1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
                });
            });
            $(function() {
                $('#project-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('dhg.project.list') !!}',
                    columns: [
                        { data: 'id', name: 'id'},
                        { data: 'date_started', name: 'date_started'},
                        { data: 'user_id', name: 'user_id'},
                        { data: 'builder_id', name: 'builder_id'},
                        { data: 'status', name: 'status'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });
            //Initialize Select2 Elements
            $('.select2').select2();

            @can('edit dhg project')

            $(document).on('click','.edit-project', function () {
                rowId = this.id;

                $.ajax({
                    'url' : '/dhg-projects/'+rowId+'/edit',
                    'type' : 'GET',
                    beforeSend: function () {
                        $('#edit-project-form input, #edit-project-form textarea, #edit-project-form select').attr('disabled',true);
                        $('#edit-project-form .textarea').summernote('disable');
                    },success: function (res) {
                        $('#edit_date_created').val(res.date_created).change();
                        $('#edit_client').val(res.user_id).change();
                        $('#edit_builder').val(res.builder_id).change();
                        $('#edit_address').val(res.address).change();
                        $('#edit_lot_area').val(res.lot_area);
                        $('#edit_floor_area').val(res.floor_area);
                        $('#edit_lot_price').val(res.lot_price);
                        $('#edit_house_price').val(res.house_price);
                        $('#edit_description').summernote('code',res.description);
                        $('#edit-project-form input, #edit-project-form textarea, #edit-project-form select').attr('disabled',false);
                        $('#edit-project-form .textarea').summernote('enable');
                    }
                });
            });
            $(document).on('submit','#edit-project-form', function (form) {
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
                        return fetch('/dhg-projects/'+rowId,{
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

                        $('#project-list').DataTable().ajax.reload();

                        $('#edit-project-modal').modal('toggle').then(()=>{
                            return Swal.fire(
                                'updated!',
                                'Project has been successfully updated.',
                                'success'
                            );
                        });

                    }else if(result.value.success === false)
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
                clear_errors('edit_client','edit_address','edit_builder');
            });
            @endcan


            @can('delete dhg project')
            $(document).on('click','.delete-btn',function(){
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
                                    'url' : '/dhg-projects/'+id,
                                    'type' : 'DELETE',
                                    'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                    'data' : {'password':access},
                                    beforeSend: function(){

                                    },success: function(output){
                                        if(output.success === true){
                                            $('#project-list').DataTable().ajax.reload();

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
            @endcan
        </script>
    @endcan
@stop
