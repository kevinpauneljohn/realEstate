@extends('adminlte::page')

@section('title', 'Project Profile')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Project Profile</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('projects.index')}}">Projects</a> </li>
                <li class="breadcrumb-item active">Project Profile</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12 col-md-12 col-lg-9 order-2 order-md-1">
            <div class="card">
                <div class="card-header">
                    @can('add model unit')
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-new-model-modal">Add Model Unit</button>
                    @endcan
                    @can('view project')
                        <a href="{{route('projects.index')}}" class="btn btn-success btn-sm">All Projects</a>
                    @endcan
                </div>
                <div class="card-body">

                    <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <table id="model-units-list" class="table table-bordered table-striped" role="grid">
                            <thead>
                            <tr role="row">
                                <th>Model</th>
                                <th>House Type</th>
                                <th>Floor Level</th>
                                <th>Lot Area</th>
                                <th>Floor Area</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tfoot>
                            <tr>
                                <th>Model</th>
                                <th>House Type</th>
                                <th>Floor Level</th>
                                <th>Lot Area</th>
                                <th>Floor Area</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            @if(auth()->user()->can('add files'))
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Upload Files Here</h3>
                    </div>
                    <div class="card-body">
                        <div id="actions" class="row">
                            <div class="col-lg-6">
                                <div class="btn-group w-100">
                      <span class="btn btn-success col fileinput-button">
                        <i class="fas fa-plus"></i>
                        <span>Add files</span>
                      </span>
                                    <button type="submit" class="btn btn-primary col start">
                                        <i class="fas fa-upload"></i>
                                        <span>Start upload</span>
                                    </button>
                                    <button type="reset" class="btn btn-warning col cancel">
                                        <i class="fas fa-times-circle"></i>
                                        <span>Cancel upload</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-6 d-flex align-items-center">
                                <div class="fileupload-process w-100">
                                    <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                        <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table table-striped files" id="previews">
                            <div id="template" class="row mt-2">
                                <div class="col-auto">
                                    <span class="preview"><img src="data:," alt="" data-dz-thumbnail /></span>
                                </div>
                                <div class="col d-flex align-items-center">
                                    <p class="mb-0">
                                        <span class="lead" data-dz-name></span>
                                        (<span data-dz-size></span>)
                                    </p>
                                    <strong class="error text-danger" data-dz-errormessage></strong>
                                </div>
                                <div class="col-4 d-flex align-items-center">
                                    <div class="progress progress-striped active w-100" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                        <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                    </div>
                                </div>
                                <div class="col-auto d-flex align-items-center">
                                    <div class="btn-group">
                                        <button class="btn btn-primary start">
                                            <i class="fas fa-upload"></i>
                                            <span>Start</span>
                                        </button>
                                        <button data-dz-remove class="btn btn-warning cancel">
                                            <i class="fas fa-times-circle"></i>
                                            <span>Cancel</span>
                                        </button>
                                        <button data-dz-remove class="btn btn-danger delete">
                                            <i class="fas fa-trash"></i>
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <table id="project-files" class="table table-bordered table-striped" role="grid">
                            <thead>
                            <tr role="row">
                                <th>Name</th>
                                <th>Type</th>
                                <th>Updated By</th>
                                <th>Uploaded At</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-3 order-1 order-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-primary">{{$project->name}}</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">{{ucfirst($project->remarks)}}</p>
                    <br>
                    <div class="text-muted">
                        <p class="text-sm">Address
                            <b class="d-block">{{ucfirst($project->address)}}</b>
                        </p>
                        @if(auth()->user()->hasRole('super admin'))
                            <p class="text-sm">Commission Rate
                                <b class="d-block">{{$project->commission_rate}}%</b>
                            </p>
                        @endif

                    </div>
                </div>
            </div>

            <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Links</h3>
                        @can('add project links')
                        <span class="float-right">
                            <button class="btn btn-primary btn-sm" id="add-links">Add Links</button>
                        </span>
                        @endcan
                    </div>

                <div class="card-body">
                    <table id="project-links" class="table table-borderless table-hover w-100">
                        <thead>
                        <tr role="row" class="project-links-head">
                            <th width="90%"></th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
    </div>

    @can('add model unit')
        <!--add new model modal-->
        <div class="modal fade" id="add-new-model-modal">
            <form role="form" id="add-model-form" class="form-submit">
                @csrf
                <input type="hidden" name="project_id" value="{{$project->id}}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Model</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group model_name">
                                <label for="model_name">Model Name</label><span class="required">*</span>
                                <input type="text" name="model_name" class="form-control" id="model_name">
                            </div>
                            <div class="form-group house_type">
                                <label for="house_type">House Type</label><span class="required">*</span>
                                <select name="house_type" id="house_type" class="form-control" style="width: 100%;">
                                    <option value=""> -- Select -- </option>
                                    <option value="Single-attached">Single-attached</option>
                                    <option value="Single-detached">Single-detached</option>
                                    <option value="Duplex">Duplex</option>
                                    <option value="Townhouse">Townhouse</option>
                                    <option value="Rowhouse">Rowhouse</option>
                                    <option value="Condominium">Condominium</option>
                                    <option value="Lot">Lot</option>
                                </select>
                            </div>
                            <div class="form-group floor_level">
                                <label for="floor_level">Floor Level</label><span class="required">*</span>
                                <select class="form-control" name="floor_level" id="floor_level">
                                    <option value=""> -- Select -- </option>
                                    <option value="Lot">Lot</option>
                                    <option value="Bungalow">Bungalow</option>
                                    <option value="Two-storey">Two-storey</option>
                                    <option value="Three-storey">Three-storey</option>
                                    <option value="Four-storey">Four-storey</option>
                                    <option value="Five-storey">Five-storey</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group lot_area">
                                        <label for="lot_area">Lot Area</label><span class="required">*</span>
                                        <input type="number" name="lot_area" class="form-control" id="lot_area" step="any">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group floor_area">
                                        <label for="floor_area">Floor Area</label><span class="required">*</span>
                                        <input type="number" name="floor_area" class="form-control" id="floor_area" step="any">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group photo_url">
                                <label for="photo_url">Facebook Photo URL</label><span class="required">*</span>
                                <input type="text" name="photo_url" class="form-control" id="photo_url">
                            </div>
                            <div class="form-group remarks">
                                <label for="remarks">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control"  placeholder="Place some text here"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-model-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new model modal-->
    @endcan

    @can('edit model unit')
        <!--edit model modal-->
        <div class="modal fade" id="edit-model-modal">
            <form role="form" id="edit-model-form" class="form-submit">
                @csrf
                @method('PUT')
                <input type="hidden" name="edit_project_id" value="{{$project->id}}">
                <input type="hidden" name="model_id" id="model_id">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Model</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group edit_model_name">
                                <label for="edit_model_name">Model Name</label><span class="required">*</span>
                                <input type="text" name="edit_model_name" class="form-control" id="edit_model_name">
                            </div>
                            <div class="form-group edit_house_type">
                                <label for="edit_house_type">House Type</label><span class="required">*</span>
                                <select name="edit_house_type" id="edit_house_type" class="form-control" style="width: 100%;">
                                    <option value=""> -- Select -- </option>
                                    <option value="Single-attached">Single-attached</option>
                                    <option value="Single-detached">Single-detached</option>
                                    <option value="Duplex">Duplex</option>
                                    <option value="Townhouse">Townhouse</option>
                                    <option value="Rowhouse">Rowhouse</option>
                                    <option value="Condominium">Condominium</option>
                                    <option value="Lot">Lot</option>
                                </select>
                            </div>
                            <div class="form-group edit_floor_level">
                                <label for="edit_floor_level">Floor Level</label><span class="required">*</span>
                                <select class="form-control" name="edit_floor_level" id="edit_floor_level">
                                    <option value=""> -- Select -- </option>
                                    <option value="Lot">Lot</option>
                                    <option value="Bungalow">Bungalow</option>
                                    <option value="Two-storey">Two-storey</option>
                                    <option value="Three-storey">Three-storey</option>
                                    <option value="Four-storey">Four-storey</option>
                                    <option value="Five-storey">Five-storey</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group edit_lot_area">
                                        <label for="edit_lot_area">Lot Area</label><span class="required">*</span>
                                        <input type="number" name="edit_lot_area" class="form-control" id="edit_lot_area" step="0.1">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group edit_floor_area">
                                        <label for="edit_floor_area">Floor Area</label><span class="required">*</span>
                                        <input type="number" name="edit_floor_area" class="form-control" id="edit_floor_area" step="0.1">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group edit_photo_url">
                                <label for="edit_photo_url">Facebook Photo URL</label><span class="required">*</span>
                                <input type="text" name="edit_photo_url" class="form-control" id="edit_photo_url">
                            </div>
                            <div class="form-group edit_remarks">
                                <label for="edit_remarks">Remarks</label>
                                <textarea name="edit_remarks" id="edit_remarks" class="form-control"  placeholder="Place some text here"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary edit-model-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new model modal-->
    @endcan

    @can('view model unit')
        <div class="modal fade" id="view-model-unit-modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">View Model Details</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
        </div>
    @endcan

    @can('add project links')
        <form class="project-links-form">
            @csrf
            <div class="modal fade" id="project-links-modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group title">
                                <label for="title">Title</label><span class="required">*</span>
                                <input type="text" name="title" class="form-control" id="title" />
                            </div>
                            <div class="form-group url">
                                <label for="url">URL</label><span class="required">*</span>
                                <input type="url" name="url" class="form-control" id="url" />
                            </div>
                        </div>
                        <input type="hidden" name="project_id" value="{{$project->id}}" />
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary edit-model-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </form>
    @endcan
@stop
@section('plugins.DropZone',true)
@section('right-sidebar')
    <x-custom.right-sidebar />
@stop
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{asset('/vendor/timepicker/bootstrap-timepicker.min.css')}}">
            <style>
                .dataTables_wrapper {
                    overflow-x: hidden;
                }
                .delete-category:hover{
                    color:red;
                }
                .project-links-head{
                    display:none;
                }
            </style>
@stop

@section('js')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <!-- bootstrap datepicker -->
        <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
        <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
        <script src="{{asset('/vendor/timepicker/bootstrap-timepicker.min.js')}}"></script>
        <script src="{{asset('js/validation.js')}}"></script>
        <script src="{{asset('js/model_unit.js')}}"></script>

        <script>
            $(function() {
                $('#model-units-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('projects.model.units.list',['project_id' => $project->id]) !!}',
                    columns: [
                        { data: 'name', name: 'name'},
                        { data: 'house_type', name: 'house_type'},
                        { data: 'floor_level', name: 'floor_level'},
                        { data: 'lot_area', name: 'lot_area'},
                        { data: 'floor_area', name: 'floor_area'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });

                $('#project-files').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('get.project.files',['project_id' => $project->id]) !!}',
                    columns: [
                        { data: 'name', name: 'name'},
                        { data: 'extension', name: 'extension'},
                        { data: 'user_id', name: 'user_id'},
                        { data: 'updated_at', name: 'updated_at'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });

                $('#project-links').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('project.links.list',['project_id' => $project->id]) !!}',
                    columns: [
                        { data: 'title', name: 'name'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc'],
                    searching: false,
                    paging: false,
                    info:false
                });
            });
            //Initialize Select2 Elements
            $('.select2').select2();
        </script>

    <script>
        // DropzoneJS Demo Code Start
        Dropzone.autoDiscover = false

        // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
        var previewNode = document.querySelector("#template")
        previewNode.id = ""
        var previewTemplate = previewNode.parentNode.innerHTML
        previewNode.parentNode.removeChild(previewNode)

        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            url: '{{route('files.upload')}}', // Set the url
            method: 'post',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 20,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button", // Define the element that should be used as click trigger to select files.
            success: function (file, response) {
                // console.log(file)
                console.log(response)
                if(response.success === true)
                {
                    toastr.success(response.message);
                    $('#project-files').DataTable().ajax.reload(null, false);
                }
            },
            error: function (file, error) {
                console.log(error)
                toastr.error(error.message);
            }
        })

        myDropzone.on("addedfile", function(file) {
            // Hookup the start button
            file.previewElement.querySelector(".start").onclick = function() {
                myDropzone.enqueueFile(file)
                // console.log(file)
            }
        })

        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function(progress) {
            document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
        })

        myDropzone.on("sending", function(file, xhr, data) {
            data.append('project_id', '{{$project->id}}');
            // Show the total progress bar when upload starts
            document.querySelector("#total-progress").style.opacity = "1"
            // And disable the start button
            file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
        })

        // Hide the total progress bar when nothing's uploading anymore
        myDropzone.on("queuecomplete", function(progress) {
            document.querySelector("#total-progress").style.opacity = "0"
        })

        // Setup the buttons for all transfers
        // The "add files" button doesn't need to be setup because the config
        // `clickable` has already been specified.
        document.querySelector("#actions .start").onclick = function() {
            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
            // console.log(myDropzone)
        }
        document.querySelector("#actions .cancel").onclick = function() {
            myDropzone.removeAllFiles(true)
        }
        // DropzoneJS Demo Code End
    </script>

    @can('add project links')
        <script>
            let projectLinkModal = $('#project-links-modal');
            let projectLinkForm = $('.project-links-form');
            $(document).on('click','#add-links', function(){
                projectLinkModal.modal('toggle');
                projectLinkModal.find('.modal-title').text('Add External Links');
                projectLinkForm.attr('id','add-project-links-form')
            });

            $(document).on('submit','#add-project-links-form', function(form){
                form.preventDefault();
                let data = $(this).serializeArray();

                $.ajax({
                    url: '{{route('project-links.store')}}',
                    method: 'post',
                    data: data,
                    beforeSend: function(){
                        projectLinkModal.find('.text-danger').remove();
                        projectLinkModal.find('.is-invalid').removeClass('is-invalid');
                    }
                }).done(function(response){
                    console.log(response)
                    if(response.success === true)
                    {
                        $('#project-links').DataTable().ajax.reload(null, false);
                        toastr.success(response.message);
                        projectLinkForm.trigger('reset');
                        projectLinkModal.modal('toggle');
                    }
                }).fail(function(xhr, status, error){
                    console.log(xhr.responseJSON.errors)
                    $.each(xhr.responseJSON.errors, function(key, value){
                        projectLinkModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                        projectLinkModal.find('#'+key).addClass('is-invalid');
                    })
                }).always(function(){

                });
            });
        </script>
    @endcan

    @can('delete project links')
        <script>
            $(document).on('click','.delete-project-links',function(){
                let id = this.id;

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
                            'url' : '/project-links/'+id,
                            'type' : 'DELETE',
                            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            'data' : {'_method':'DELETE','id' : id},
                            beforeSend: function(){

                            },success: function(response){
                                if(response.success === true){
                                    $('#project-links').DataTable().ajax.reload(null, false);
                                }
                            },error: function(xhr, status, error){
                                console.log(xhr);
                            }
                        });

                    }
                });
            });
        </script>
    @endcan
@stop
