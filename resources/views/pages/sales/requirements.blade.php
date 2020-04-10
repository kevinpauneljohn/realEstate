@extends('adminlte::page')

@section('title', 'Requirements')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Requirements</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('sales.index')}}">Sales</a></li>
                <li class="breadcrumb-item active">Requirements</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <td>Client Name:</td>
                            <td>
                                <strong>
                                    {{ucfirst($lead->firstname)}}
                                    {{ucfirst($lead->middlename)}}
                                    {{ucfirst($lead->lastname)}}
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td>Project:</td>
                            <td>
                                <strong>
                                    {{$project->name}}
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td>Model Unit</td>
                            <td>
                                <strong>
                                    {{$modelUnit->name}}
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td>Total Contract Price</td>
                            <td>
                                <strong>
                                    {{number_format($sales->total_contract_price)}}
                                </strong>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body">
                     @if($sales->template_id == null)
                        <form id="sales-requirements-form">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="salesId" id="salesId" value="{{$sales->id}}">
                            <div class="form-group">
                                <label for="template">Select Requirements Template</label>
                                <select class="form-control" name="template" id="template">
                                    <option value=""> -- Select -- </option>
                                    @foreach($templates as $template)
                                        <option value="{{$template->id}}">{{$template->name}}</option>
                                    @endforeach
                                </select>
                                <div class="image-loader">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                         @else
                         @if($requirements != null)

                                @foreach($requirements as $requirement)
                                    @php
                                        $list = \App\SaleRequirement::where('requirement_id',$requirement->id);
                                    @endphp
                                    @if($list->count() == 0)

                                        <form role="form" action="{{route('requirements.image.upload')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="requirementId" value="{{$requirement->id}}">
                                            <input type="hidden" name="saleId" value="{{$sales->id}}">
                                            <div class="form-group">
                                                <label for="requirement_{{$requirement->id}}">{{$requirement->description}}</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" name="requirement_{{$requirement->id}}" class="custom-file-input {{$errors->has('requirement_'.$requirement->id) ? 'is-invalid' : ''}}" id="requirement_{{$requirement->id}}"
                                                               value="{{old('requirement_'.$requirement->id)}}">
                                                        <label class="custom-file-label" for="requirement_{{$requirement->id}}">Choose file</label>
                                                    </div>
                                                    <div class="input-group-append">
                                                        <button type="submit" class="input-group-text" id="">Upload</button>
                                                    </div>
                                                </div>

                                                @error('requirement_'.$requirement->id)
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </form>

                                        @else

                                    @endif
                                @endforeach
                                @if($lists->count() > 0)
                                    @foreach($lists->get() as $list)
                                        @php
                                            $description = \App\Requirement::find($list->requirement_id)->description
                                        @endphp
                                        <div class="requirements-image float-left">
                                            <label>{{$description}}</label><br/>
                                            <img src="{{asset('/thumbnail/'.$list->image)}}" alt="{{$description}}" class="img-thumbnail">
                                        </div>
                                    @endforeach
                                @endif

                             @endif
                     @endif
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.css')}}">
    <style type="text/css">
    </style>
@stop

@section('js')
    <!-- bootstrap datepicker -->
    <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('/vendor/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    @if($sales->template_id == null)
        <script src="{{asset('js/uploadRequirements.js')}}"></script>
    @endif
    <script type="text/javascript">
        $(document).ready(function () {
            bsCustomFileInput.init();
        });
    </script>
@stop
