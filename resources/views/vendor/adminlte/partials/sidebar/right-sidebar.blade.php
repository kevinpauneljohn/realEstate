<aside class="control-sidebar control-sidebar-{{config('adminlte.right_sidebar_theme')}}">
    @yield('right-sidebar')
    <div class="p-3 control-sidebar-content">
        <h5>Quick Tools</h5>
        <hr class="mb-2">
        <div class="mb-1">
            <button type="button" class="btn btn-flat btn-primary" data-toggle="modal" data-target="#canned-message">View Canned Messages</button>
        </div>
        <div class="mb-4">
            <button type="button" class="btn btn-flat bg-purple" data-toggle="modal" data-target="#canned-message">View Sample Computation</button>
        </div>
{{--        <h6>Navbar Variants</h6>--}}
{{--        <div class="d-flex">--}}
{{--            <div class="d-flex flex-wrap mb-3">--}}
{{--                test--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
</aside>

    <!--add new roles modal-->
    <div class="modal fade" id="canned-message">
            <div class="modal-dialog modal-lg">
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
                                            <div class="card-body" id="canned-body-{{$message->id}}">
                                                {!! \App\Repositories\CannedMessageRepository::filter($message->body) !!}
                                            </div>
                                            <div class="card-footer">
                                                <button type="button" class="btn btn-success btn-flat btn-xs copy-canned" id="{{$message->id}}"><i class="fas fa-copy"></i> Copy</button>
                                            </div>
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
