@extends('adminlte::page')

@section('title', 'Edit Leads')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Edit Leads</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('leads.index')}}">Leads</a></li>
                <li class="breadcrumb-item active">Edit Leads</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            @can('view lead')
                <a href="{{route('leads.index')}}"><button type="button" class="btn bg-gradient-success btn-sm">View all</button></a>
                <a href="{{route('leads.show',['lead' => $lead->id])}}"><button type="button" class="btn bg-gradient-info btn-sm">View details</button></a>
            @endcan

        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <form method="POST" action="{{route('leads.update',['lead' => $lead->id])}}" class="form-submit">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-6">
                            <!-- Date range -->
                            <div class="form-group">
                                <label>Date Inquired</label><span class="required">*</span>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" name="date_inquired" class="form-control datemask" id="datepicker" value="{{$lead->date_inquired}}" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy/mm/dd" data-mask="" im-insert="false">
                                </div>
                                @error('date_inquired')
                                <span class="invalid-feedback" role="alert">
                                   <strong>{{ $message }}</strong>
                               </span>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-lg-4 {{$errors->has('firstname') ? 'has-error' : ''}}">
                                    <label for="firstname">First Name</label><span class="required">*</span>
                                    <input type="text" name="firstname" value="{{$lead->firstname}}" class="form-control">
                                    @error('firstname')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-lg-4">
                                    <label for="middlename">Middle Name</label>
                                    <input type="text" name="middlename" value="{{$lead->middlename}}" class="form-control">
                                </div>
                                <div class="col-lg-4 {{$errors->has('lastname') ? 'has-error' : ''}}" class="form-control">
                                    <label for="lastname">Last Name</label><span class="required">*</span>
                                    <input type="text" name="lastname" value="{{$lead->lastname}}" class="form-control">
                                    @error('lastname')
                                    <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                   </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control" name="address">{{$lead->address}}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Landline</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" name="landline" value="{{$lead->landline}}" class="form-control" data-inputmask="'mask': ['999-999-9999 [x99999]', '+099 99 99 9999[9]-9999']" data-mask="" im-insert="true">
                                </div>
                                <!-- /.input group -->
                            </div>

                            <div class="form-group">
                                <label>Mobile No.</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-mobile"></i></span>
                                    </div>
                                    <input type="text" name="mobileNo" value="{{$lead->mobileNo}}" class="form-control" data-inputmask="&quot;mask&quot;: &quot;(9999) 999-9999&quot;" data-mask="" im-insert="true">
                                </div>
                                <!-- /.input group -->
                            </div>
                            <div class="form-group {{$errors->has('email') ? 'has-error' : ''}}">
                                <label for="email">Email</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" name="email" class="form-control" value="{{$lead->email}}">
                                </div>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                   </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="status">Civil Status</label>
                                <select class="form-control" name="status">
                                    <option value="">-- Select --</option>
                                    <option value="Single" @if($lead->status == "Single") selected="selected" @endif>Single</option>
                                    <option value="Married" @if($lead->status == "Married") selected="selected" @endif>Married</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="income">Income Range</label>
                                <select name="income_range" class="form-control" id="income_range">
                                    <option value=""> -- Select -- </option>
                                    <option value="Below 10K" @if($lead->income_range == "Below 10K") selected="selected" @endif>Below 10K</option>
                                    <option value="10K - 20K" @if($lead->income_range == "10K - 20K") selected="selected" @endif>10K - 20K</option>
                                    <option value="21K - 30K" @if($lead->income_range == "21K - 30K") selected="selected" @endif>21K - 30K</option>
                                    <option value="31K - 40K" @if($lead->income_range == "31K - 40K") selected="selected" @endif>31K - 40K</option>
                                    <option value="41K - 50K" @if($lead->income_range == "41K - 50K") selected="selected" @endif>41K - 50K</option>
                                    <option value="51K - 60K" @if($lead->income_range == "51K - 60K") selected="selected" @endif>51K - 60K</option>
                                    <option value="61K - 70K" @if($lead->income_range == "61K - 70K") selected="selected" @endif>61K - 70K</option>
                                    <option value="70K+" @if($lead->income_range == "70K+") selected="selected" @endif>70K+</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group {{$errors->has('point_of_contact') ? 'has-error' : ''}}">
                                <label for="point_of_contact">Point Of Contact</label><span class="required">*</span>
                                <select name="point_of_contact" class="form-control" id="point_of_contact">
                                    <option value=""> -- Select -- </option>
                                    <option value="Booth" @if($lead->point_of_contact == "Booth") selected="selected" @endif>Booth</option>
                                    <option value="Site" @if($lead->point_of_contact == "Site") selected="selected" @endif>Site</option>
                                    <option value="Online" @if($lead->point_of_contact == "Online") selected="selected" @endif>Online</option>
                                    <option value="Saturation" @if($lead->point_of_contact == "Saturation") selected="selected" @endif>Saturation</option>
                                    <option value="Referral" @if($lead->point_of_contact == "Referral") selected="selected" @endif>Referral</option>
                                    <option value="Youtube" @if(old('point_of_contact') == "Youtube") selected="selected" @endif>Youtube</option>
                                    <option value="Facebook" @if(old('point_of_contact') == "Facebook") selected="selected" @endif>Facebook</option>
                                    <option value="Phone Call" @if(old('point_of_contact') == "Phone Call") selected="selected" @endif>Phone Call</option>
                                    <option value="SMS" @if(old('point_of_contact') == "SMS") selected="selected" @endif>SMS</option>
                                    <option value="Others" @if($lead->point_of_contact == "Others") selected="selected" @endif>Others</option>
                                </select>
                                @error('point_of_contact')
                                <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                   </span>
                                @enderror
                            </div>

                            <div class="form-group project">
                                <label for="project">Project Interested</label>
                                <select class="select2" name="project[]" id="project" multiple="multiple" data-placeholder="Select a project" style="width: 100%;">
                                    @foreach($projects as $project)
                                        <option value="{{$project->name}}"{{\App\Http\Controllers\LeadController::selectedProject($lead->project,$project->name)}}>{{$project->name}}</option>
                                    @endforeach
                                </select>
                                @error('project')
                                <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                   </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea name="remarks" class="textarea" data-min-height="150" placeholder="Place some text here">{!! $lead->remarks !!}</textarea>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-primary submit-form-btn" style="width: 100%">
                                    <i class="spinner fa fa-spinner fa-spin"></i> Save
                                </button>
                            </div>
                        </div>
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
        .note-editable{
            min-height:200px;
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
                    // lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
                    lineHeights: ['1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
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
            });
            //Money Euro
            $('[data-mask]').inputmask()
            {{--$('.textarea').html('{!! old('remarks') !!}');--}}
        </script>
        @if(session('success') === true)
            <script>
                toastr.success('{{session('message')}}');
            </script>
            @elseif(session('success') === false)
            <script>
                toastr.warning('{{session('message')}}');
            </script>
        @endif
    @endcan
@stop
