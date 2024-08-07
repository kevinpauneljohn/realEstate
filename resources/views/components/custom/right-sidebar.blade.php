@if(!auth()->user()->hasAnyRole('architect|client'))
    <div class="p-3 control-sidebar-content">
        <h5>Quick Tools</h5>
        <hr class="mb-2">
        <div class="mb-1">
            <a href="#" data-toggle="modal" data-target="#canned-message"><i class="far fa-comment-dots text-success"></i>&nbsp; Canned Messages</a>
        </div>
        <div class="mb-1">
            <a href="#" data-toggle="modal" data-target="#sample-computation-modal"><i class="fas fa-file-alt text-success"></i> &nbsp;Sample Computation</a>
        </div>
        <div class="mb-1">
            <a href="#" class="calculator-template-btn" data-toggle="modal" data-target="#calculator-modal"><i class="fas fa-calculator text-success"></i> &nbsp;Mortgage Calculator</a>
        </div>
        <div class="mb-1">
            <a href="#" class="calculator-template-btn" data-toggle="modal" data-target="#requirements-modal"><i class="far fa-file-archive text-success"></i> &nbsp;Requirements</a>
        </div>
        @can('view contacts')
            <div class="mb-1">
                <a href="#" class="contacts-tools-btn" data-toggle="modal" data-target="#view-contact-list-tools"><i class="far fa-address-book text-success"></i> &nbsp;Contact List</a>
            </div>
        @endcan
    </div>
    @endif
    </aside>

    @if(!auth()->user()->hasAnyRole('architect|client'))

        @can('view contacts')
            <!--add new roles modal-->
            <div class="modal fade" id="view-contact-list-tools">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Select Contact List</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <select class="form-control select2" id="select-contact-list" style="width:100%;">
                                <option value=""> -- Select -- </option>
                                @foreach(\App\Contact::all() as $contact)
                                    <option value="{{$contact->id}}">{{$contact->title}} - {{$contact->contact_person}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--end add new roles modal-->
        @endcan


        <!--add new roles modal-->
        <div class="modal fade" id="canned-message">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Canned Messages</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="accordion" class="canned-accordion">
                            <!-- we are adding the .class so bootstrap.js collapse plugin detects it -->
                            @foreach(\App\CannedCategory::all() as $category)
                                @if(\App\CannedMessageModel::where('canned_categories_id',$category->id)->count() > 0)<h5>{{$category->name}}</h5>@endif
                                @foreach(\App\CannedMessageModel::where([['canned_categories_id','=',$category->id],['status','=','Publish']])->get() as $message)
                                    <div class="card card-info">
                                        <div class="card-header" style="padding:6px;">
                                            <h6 class="card-title" style="color: white">
                                                {{ucfirst($message->title)}}
                                            </h6>
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse-{{$message->id}}" class="collapsed float-right accordion-dropdown" aria-expanded="false" style="color: white" id="canned-accordion-{{$message->id}}">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </div>
                                        <div id="collapse-{{$message->id}}" class="panel-collapse in collapse" style="">
                                            <button type="button" class="btn btn-success btn-flat btn-xs copy-canned float-right" id="{{$message->id}}"><i class="fas fa-copy"></i> Copy</button>
                                            <div class="card-body" id="canned-body-{{$message->id}}">
                                                {!! \App\Repositories\CannedMessageRepository::filter($message->body) !!}
                                            </div>

                                            {{--                                            <div class="card-footer">--}}
                                            {{--                                                <button type="button" class="btn btn-success btn-flat btn-xs copy-canned" id="{{$message->id}}"><i class="fas fa-copy"></i> Copy</button>--}}
                                            {{--                                            </div>--}}
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach

                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--end add new roles modal-->

        <!--view sample computation modal-->
        <div class="modal fade" id="sample-computation-modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Sample Computation</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="sample-computation-form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="project_label">Project</label>
                                        <select class="form-control select2" name="project_label" id="project_label" style="width:100%;">
                                            <option value=""> -- Select -- </option>
                                            @foreach(\App\Project::all() as $project)
                                                <option value="{{$project->id}}">{{$project->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="model_unit_label">Model Unit</label>
                                        <select class="form-control" name="model_unit_label" id="model_unit_label">
                                            <option value=""> -- Select -- </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" class="btn btn-primary search-btn" value="Search">
                        </form>
                        <div class="display-computation"></div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--end sample computation modal-->

        <!--calculator modal-->
        <div class="modal fade" id="calculator-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Select Calculator</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <select class="form-control" id="calculator-template">
                                <option value=""> -- Select -- </option>
                                <option value="Apec Homes HDMF">Apec Homes HDMF</option>
                                <option value="Bank">Mortgage Calculator</option>
                                <option value="Hausland Bank Calculator">Hausland Bank Calculator</option>
                                <option value="Hausland Inhouse Calculator">Hausland Inhouse Calculator</option>
                            </select>

                            <div class="row display-calculator"></div>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--end calculator modal-->

        <!--calculator modal-->
        <div class="modal fade" id="requirements-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Select Requirements Template</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <select class="form-control select2" id="requirement-template" style="width:100%;">
                                <option value=""> -- Select -- </option>
                                @foreach(\App\Template::all() as $template)
                                    <option value="{{$template->id}}">{{ucfirst($template->name)}}</option>
                                @endforeach
                            </select>

                            <div class="row requirement-display"></div>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--end calculator modal-->

    @endif

    @php
        $display_sensitive_data = \Illuminate\Support\Facades\DB::table('settings')->where('title','sensitive_data')->first()->show;
    @endphp
    @if(auth()->user()->hasRole(['super admin','Finance Admin']))
        @if(!$display_sensitive_data)
            @section('plugins.Settings',true)
        @endif
    @endif
