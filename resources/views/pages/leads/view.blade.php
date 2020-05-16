@extends('adminlte::page')

@section('title', 'Lead Details')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Manage Leads</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('leads.index')}}">Leads</a> </li>
                <li class="breadcrumb-item active">Lead Details</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-9 lead-profile">
            <div class="card card-primary">
                <div class="card-header main-profile">
                    <span class="float-right">
                        <a href="{{route('leads.index')}}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> View all</a>
                        <button type="button" class="btn btn-sm btn-primary set-status" data-toggle="modal" data-target="#set-status"><i class="fa fa-thermometer-three-quarters"></i> Update Status</button>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#log-touches"><i class="fa fa-address-book"></i> Log Activity</button>
                        <a href="{{route('leads.edit',['lead' => $lead->id])}}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                        <a href="{{route('sales.create')}}?leadId={{$lead->id}}" class="btn btn-sm btn-primary"><i class="fa fa-exchange-alt"></i> Convert to sales</a>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                       <span class="col-lg-2">
                           <div class="text-center">
                                <img class="img-thumbnail" src="{{asset('images/leadAvatar.jpg')}}" alt="Lead Profile Picture">
                           </div>
                       </span>
                        <span class="col-lg-7">
                            <strong class="full-name">{{$lead->fullname}}</strong>
                            <img class="star" src="@if($lead->important === 0) {{asset('/images/empty-star.svg')}} @else {{asset('/images/filled-star.svg')}} @endif" height="25" data-toggle="tooltip" title="@if($lead->important === 0) Mark as important @else Unmarked important @endif">
                                <div class="profile-details">
                                    <table>
                                        <tr><td><strong>Address </strong></td><td>: {{$lead->address}}</td></tr>
                                        <tr><td><strong>Email </strong></td><td>: <a href="mailto:{{$lead->email}}" data-toggle="tooltip" data-placement="right" title="Click to send email">{{$lead->email}}</a></td></tr>
                                        <tr><td><strong>Phone </strong></td><td>: <a href="tel:{{$lead->mobileNo}}" data-toggle="tooltip" data-placement="right" title="Click to call client">{{$lead->mobileNo}}</a></td></tr>
                                        <tr><td><strong>Land line </strong></td><td>: <a href="tel:{{$lead->landline}}">{{$lead->landline}}</a></td></tr>
                                        <tr><td><strong>Civil Status </strong></td><td>: {{$lead->status}}</td></tr>
                                        <tr><td><strong>Income Range </strong></td><td>: {{$lead->income_range}} php</td></tr>
                                        <tr><td><strong>Project Interested </strong></td><td>: {{$lead->project}}</td></tr>
                                    </table>
                                </div>
                        </span>
                        <span class="col-lg-3">
                            <div class="card card-subtitle right-status">
                                <div class="card-header">
                                    <strong>Status</strong>
                                </div>
                                <div class="card-body">
                                    <i class="fa fa-comment"></i> <strong>Last Contacted</strong>
                                    <p class="last-contacted">
                                        @if($lead->LogTouches->count() > 0)
                                            {{$lead->LogTouches->pluck('date')[0]->format('M d, yy')}}
{{--                                            {{$lead->LogTouches->pluck('date')->last()->format('M d, yy')}} {{$lead->LogTouches->pluck('time')->last()}}<br/>--}}
                                            <a href="#">{{$lead->LogTouches->pluck('date')->last()->diffForHumans()}}</a>
                                        @endif

                                    </p>
                                    <hr/>
                                    <div class="quick-preview">
                                    <div class="caption"><i class="fas fa-filter"></i> Details</div>
                                       <table class="status-details">
                                          <tbody><tr>
                                             <td>Status</td>
                                             <td>: {!! $label->setStatusBadge($lead->lead_status) !!}</td>
                                              </tr>
                                              <tr>
                                                 <td>Source</td>
                                                 <td>: {{$lead->point_of_contact}}</td>
                                              </tr>
                                              <tr>
                                                 <td>Created</td>
                                                 <td>: {{$lead->created_at->diffForHumans()}} </td>
                                              </tr>
                                           </tbody>
                                       </table>
                                    </div>
                                </div>
                            </div>
                        </span>
                    </div>
                </div>
            </div>

            <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#lea-remarks" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Remarks</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#lead-notes" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Notes <span class="note-count">({{$leadNotes->count()}})</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-two-messages-tab" data-toggle="pill" href="#lead-reminders" role="tab" aria-controls="custom-tabs-two-messages" aria-selected="false">Reminders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-two-settings-tab" data-toggle="pill" href="#activity-logs" role="tab" aria-controls="custom-tabs-two-settings" aria-selected="false">Activity Logs</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-two-tabContent">
                        <div class="tab-pane fade show active" id="lea-remarks" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
                            {!! $lead->remarks !!}
                        </div>
                        <div class="tab-pane fade" id="lead-notes" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" class="form-submit" id="notes-form">
                                        @csrf
                                        <input type="hidden" name="leadId" value="{{$lead->id}}">
                                        <span class="required">*</span> (5000 characters max only)
                                        <div class="form-group notes">
                                            <textarea class="form-control" name="notes" id="notes" maxlength="5000"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary submit-form-btn float-right"><i class="spinner fa fa-spinner fa-spin"></i> Post</button>
                                    </form>
                                </div>
                            </div>

                            <div class="row note-lists">
                                @if($leadNotes->count() > 0)
                                    @foreach($leadNotes->orderBy('created_at','desc')->get() as $leadNote)
                                        <div class="col-lg-12" id="note-row-{{$leadNote->id}}">
                                            <div class="info-box bg-light">
                                                <div class="info-box-content">
                                                    <div class="row" id="lead-note-{{$leadNote->id}}">
                                                        <span class="col-lg-11" id="note-list-{{$leadNote->id}}">
                                                            <span class="info-box-text text-muted">@if($leadNote->created_at === $leadNote->updated_at) Note Added @else Note Updated @endif {{$leadNote->updated_at}}</span>
                                                            <span class="info-box-number text-muted mb-0" id="note-content-{{$leadNote->id}}">{!! $leadNote->notes !!}</span>
                                                        </span>
                                                        <span class="col-lg-1">
                                                            <button type="button" class="btn btn-primary btn-xs edit-note" id="{{$leadNote->id}}"><i class="fa fa-edit"></i></button>
                                                            <button type="button" class="btn btn-danger btn-xs delete-note" id="{{$leadNote->id}}"><i class="fa fa-trash"></i></button>
                                                        </span>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="lead-reminders" role="tabpanel" aria-labelledby="custom-tabs-two-messages-tab">
                            <div class="card card-default">
                                <div class="card-header">
                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#new-reminder">New Reminder</button>
                                </div>
                                <div class="card-body">
                                    <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow-x:auto;">
                                        <table id="reminder-list" class="table table-bordered table-hover" role="grid">
                                            <thead>
                                            <tr role="row">
                                                <th>Date Scheduled</th>
                                                <th>Recent</th>
                                                <th>Category</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>

                                            <tfoot>
                                            <tr>
                                                <th>Date Reserved</th>
                                                <th>Recent</th>
                                                <th>Category</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="activity-logs" role="tabpanel" aria-labelledby="custom-tabs-two-settings-tab">

                            <div class="row">
                                <div class="col-md-12">
                                    <!-- The time line -->
                                    <div class="timeline">
                                    @if($activity_logs->count() > 0)
                                        @foreach($activity_logs->orderBy('date','asc')->get() as $logs)
                                            <!-- timeline time label -->
                                                <div class="time-label logs-{{$logs->id}}">
                                                    <span class="{{$label->getDateClassLabel($logs->medium)}}">{{$logs->date->format('M d, Y')}}</span>
                                                </div>
                                                <!-- timeline item -->
                                                <div class="logs-{{$logs->id}}">
                                                    {!! $label->getTimelineIcon($logs->medium) !!}
{{--                                                    <i class="fas fa-envelope bg-blue"></i>--}}
                                                    <div class="timeline-item">
                                                        <span class="time"><i class="fas fa-clock"></i> {{$logs->time}}</span>
                                                        <h3 class="timeline-header"><a href="#">{{$logs->medium}}</a> {{$logs->resolution}}</h3>

                                                        <div class="timeline-body">
                                                            {!! $logs->description !!}
                                                        </div>
                                                        <div class="timeline-footer">
                                                            <a href="#" class="btn btn-primary btn-sm edit-timeline" id="{{$logs->id}}" data-toggle="modal" data-target="#edit-log-touches">Edit</a>
                                                            <a href="#" class="btn btn-danger btn-sm delete-timeline" id="{{$logs->id}}">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- END timeline item -->
                                        @endforeach
                                    @endif
                                        <div>
                                            <i class="fas fa-clock bg-gray"></i>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <div class="col-lg-3">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <strong style="font-size: 18px;">Quick Links</strong>
                            <button class="btn btn-secondary btn-xs float-right" data-toggle="modal" data-target="#social-links">Add</button>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-pills flex-column url-links">
                                @foreach($website_links->get() as $link)
                                    <li class="nav-item" id="link-{{$link->id}}">
                                        <a class="nav-link">
                                            <a href="{{$link->website_url}}" target="_blank" title="Click the link">{{$link->website_name}}</a>
                                            <span class="float-right text-danger">
                                                <button type="button" class="btn btn-xs remove-link" id="{{$link->id}}" title="Remove link"><i class="fa fa-times-circle"></i></button>
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @can('add lead')
        <!--add new schedule modal-->
        <div class="modal fade" id="social-links">
            <form role="form" id="website-link-form" class="form-submit">
                @csrf
                <input type="hidden" name="lead_id" value="{{$lead->id}}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Website Link</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group website_name">
                                <label for="website_name">Website Name</label><span class="required">*</span>
                                <input type="text" name="website_name" id="website_name" class="form-control">
                            </div>
                            <div class="form-group url">
                                <label for="url">URL</label><span class="required">*</span> (http:// or https:// must be included)
                                <input type="text" name="url" id="url" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-form-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new schedule modal-->
    @endcan
    @can('edit lead')
        <!--add new schedule modal-->
        <div class="modal fade" id="log-touches">
            <form role="form" id="log-touches-form" class="form-submit">
                @csrf
                <input type="hidden" name="lead_id" value="{{$lead->id}}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Log Activity</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="medium">Medium</label><span class="required">*</span>
                                        <select class="form-control" name="medium">
                                            <option value="Phone Call">Phone Call</option>
                                            <option value="SMS">SMS</option>
                                            <option value="Email">Email</option>
                                            <option value="Meeting">Meeting</option>
                                            <option value="Social Network">Social Network</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="date">Date</label><span class="required">*</span>
                                        <input type="text" name="date" class="form-control datemask" id="datepicker" value="{{old('date_inquired')}}" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy/mm/dd" data-mask="" im-insert="false">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="time">Time</label><span class="required">*</span>

                                        <div class="input-group date" id="timepicker" data-target-input="nearest">
                                            <input name="time" type="text" class="form-control datetimepicker-input" data-target="#timepicker">
                                            <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                                            </div>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="resolution">Resolution</label><span class="required">*</span>
                                <select name="resolution" class="form-control">
                                    <option value="No Resolution">No Resolution</option>
                                    <option value="Successful">Successful</option>
                                    <option value="Unsuccessful">Unsuccessful</option>
                                    <option value="Abandoned">Abandoned</option>
                                    <option value="Sent SMS">Sent SMS</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>(Optional)(3000 characters max)
                                <textarea class="form-control" name="description" maxlength="3000"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Save</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new schedule modal-->
    @endcan

    @can('edit lead')
        <div class="modal fade" id="edit-log-touches">
            <form role="form" id="edit-log-touches-form" class="form-submit">
                @csrf
                @method('PUT')
                <input type="hidden" name="lead_url" value="{{url()->current()}}">
                <input type="hidden" name="lead_id" value="{{$lead->id}}">
                <input type="hidden" name="log_id" id="log_id">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Log Activity</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="edit_medium">
                                            <label for="edit_medium">Medium</label><span class="required">*</span>
                                            <select class="form-control" name="edit_medium" id="edit_medium">
                                                <option value="Phone Call">Phone Call</option>
                                                <option value="SMS">SMS</option>
                                                <option value="Email">Email</option>
                                                <option value="Meeting">Meeting</option>
                                                <option value="Social Network">Social Network</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="edit_date">
                                            <label for="edit_date">Date</label><span class="required">*</span>
                                            <input type="text" name="edit_date" class="form-control datemask" id="edit_date" value="{{old('date_inquired')}}" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy/mm/dd" data-mask="" im-insert="false">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="edit_time">Time</label><span class="required">*</span>

                                            <div class="input-group edit_time" id="edit-timepicker" data-target-input="nearest">
                                                <input name="edit_time" id="edit_time" type="text" class="form-control datetimepicker-input" data-target="#edit-timepicker">
                                                <div class="input-group-append" data-target="#edit-timepicker" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group edit_resolution">
                                <label for="edit_resolution">Resolution</label><span class="required">*</span>
                                <select name="edit_resolution" class="form-control" id="edit_resolution">
                                    <option value="No Resolution">No Resolution</option>
                                    <option value="Successful">Successful</option>
                                    <option value="Unsuccessful">Unsuccessful</option>
                                    <option value="Abandoned">Abandoned</option>
                                    <option value="Sent SMS">Sent SMS</option>
                                </select>
                            </div>

                            <div class="form-group edit_description">
                                <label for="edit_description">Description</label>(Optional)(3000 characters max)
                                <textarea class="form-control" name="description" maxlength="3000" id="edit_description"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Save</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new schedule modal-->
    @endcan

    @can('add lead')
        <!--add new schedule modal-->
        <div class="modal fade" id="new-reminder">
            <form role="form" id="new-reminder-form" class="form-submit">
                @csrf
                <input type="hidden" name="lead_id" value="{{$lead->id}}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">New Reminder</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="reminder_date">
                                        <label for="date">Date</label><span class="required">*</span>
                                        <input type="text" name="reminder_date" class="form-control datemask" id="reminder_date" value="{{today()->format('Y-m-d')}}" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy/mm/dd" data-mask="" im-insert="false">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="time">Time</label><span class="required">*</span>

                                    <div class="input-group reminder_time" id="reminder-time" data-target-input="nearest">
                                        <input name="reminder_time" id="reminder_time" type="text" class="form-control datetimepicker-input" data-target="#reminder-time" value="{{now()->format('h:i A')}}">
                                        <div class="input-group-append" data-target="#reminder-time" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="form-group reminder_category">
                                <label for="reminder_category">Category</label><span class="required">*</span>
                                <select class="form-control" name="reminder_category" id="reminder_category">
                                    <option value="Tripping">Tripping</option>
                                    <option value="Follow-up">Follow-up</option>
                                    <option value="Send Project Details">Send Project Details</option>
                                    <option value="Send Requirements">Send Requirements</option>
                                    <option value="Assist">Assist</option>
                                </select>
                            </div>
                            <div class="form-group reminder_details">
                                <label for="reminder_details">Details</label><span class="required">*</span>
                                <textarea class="form-control" name="reminder_details" id="reminder_details"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Save</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new schedule modal-->
    @endcan

    @can('edit lead')
        <div class="modal fade" id="edit-reminder">
            <form role="form" id="edit-reminder-form" class="form-submit">
                @csrf
                @method('PUT')
                <input type="hidden" name="lead_id" value="{{$lead->id}}">
                <input type="hidden" name="reminderId" id="reminderId">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Reminder</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="edit_reminder_date">
                                        <label for="date">Date</label><span class="required">*</span>
                                        <input type="text" name="edit_reminder_date" class="form-control datemask" id="edit_reminder_date" value="{{today()->format('Y-m-d')}}" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy/mm/dd" data-mask="" im-insert="false">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="time">Time</label><span class="required">*</span>

                                    <div class="input-group edit_reminder_time" id="edit_reminder-time" data-target-input="nearest">
                                        <input name="edit_reminder_time" id="edit_reminder_time" type="text" class="form-control datetimepicker-input" data-target="#edit_reminder-time" value="{{now()->format('h:i A')}}">
                                        <div class="input-group-append" data-target="#edit_reminder-time" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="form-group edit_reminder_category">
                                <label for="edit_reminder_category">Category</label><span class="required">*</span>
                                <select class="form-control" name="edit_reminder_category" id="edit_reminder_category">
                                    <option value="Tripping">Tripping</option>
                                    <option value="Follow-up">Follow-up</option>
                                    <option value="Send Project Details">Send Project Details</option>
                                    <option value="Send Requirements">Send Requirements</option>
                                    <option value="Assist">Assist</option>
                                </select>
                            </div>
                            <div class="form-group edit_reminder_details">
                                <label for="edit_reminder_details">Details</label><span class="required">*</span>
                                <textarea class="form-control" name="edit_reminder_details" id="edit_reminder_details"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Save</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new schedule modal-->
    @endcan

    @can('edit lead')
        <!--add new schedule modal-->
        <div class="modal fade" id="set-status">
            <form role="form" id="change-status-form" class="form-submit">
                @csrf
                <input type="hidden" name="lead_url" value="{{url()->current()}}">
                <input type="hidden" name="lead_id" id="lead_id" value="{{$lead->id}}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Change Lead Status</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group status">
                                <label for="status">Status</label><span class="required">*</span>
                                <select class="change-status form-control" name="status" id="status">
                                    @php
                                        $status = array('Hot','Warm','Cold','Qualified','Not qualified','Inquiry Only','Not Interested Anymore');
                                        $data = '';

                                            foreach ($status as $stats)
                                            {

                                            if($stats === 'Hot' || $stats === 'Warm' || $stats === 'Cold')
                                            {
                                            $disabled = "disabled";
                                            }else{
                                            $disabled = "";
                                            }

                                            $data.= '<option value="'.$stats.'" '.$disabled.'>'.$stats.'</option>';
                                            }
                                        echo $data;
                                    @endphp
                                </select>
                            </div>
                            <div class="form-group notes">
                                <label for="notes">Details</label><span class="required">*</span>
                                <textarea class="form-control" name="notes" id="notes"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Save</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new schedule modal-->
    @endcan
@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    {{--<!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{asset('/vendor/timepicker/bootstrap-timepicker.min.css')}}">--}}
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.css')}}">
    <style type="text/css">
        .remove-link:hover{
            color:red;
        }
        #reminder-list td{
            padding:8px!important;
        }
        #reminder-list{
            width:100%!important;
        }
        .note-lists .col-lg-1{
            margin-top:18px!important;
        }
        .note-lists .info-box-text{
            color:#1365ec!important;
        }
        small{
            margin: 2px;
        }
        .full-name{
            font-size:22px;
            color:#3168f3;
        }
        .lead-profile table{
            margin-bottom:20px;
        }
        .lead-profile table td{
            padding:0px!important;
        }
        .lead-profile table td:nth-child(2)
        {
            font-size:16px;
            word-wrap: break-word;
        }
        .lead-profile table td:nth-child(1)
        {
            font-weight:bold;

        }
        .img-thumbnail{
            max-height:250px;
            margin-bottom:15px;
        }
        .star{
            margin-left:5px;
            margin-top:-11px;
        }
        .star:hover{
            cursor:pointer;
        }
        .lead-profile table{
            width:0px!important;
            padding:0px!important;
        }
        .right-status i{
            color:#0cc60c;
        }
        .note-lists{
            margin-top:20px;
        }
    </style>
@stop

@section('js')
    @can('view lead')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <!-- bootstrap datepicker -->
        <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
        <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
        <script src="{{asset('/vendor/daterangepicker/daterangepicker.js')}}"></script>
        <script src="{{asset('/vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>

{{--        <script src="{{asset('/vendor/timepicker/bootstrap-timepicker.min.js')}}"></script>--}}
        <!-- Summernote -->
        <script src="{{asset('vendor/summernote/summernote-bs4.min.js')}}"></script>
        <script src="{{asset('js/leadActivity.js')}}"></script>
        <script src="{{asset('js/schedule.js')}}"></script>
        <script src="{{asset('js/leadNotes.js')}}"></script>
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
                    lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
                });
            })
        </script>
        <script>
            $(function() {
                $('#reminder-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('leads.activity.list',['lead' => $lead->id]) !!}',
                    columns: [
                        { data: 'schedule', name: 'schedule'},
                        { data: 'recent', name: 'recent'},
                        { data: 'category', name: 'category'},
                        { data: 'status', name: 'status'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'desc']
                });
            });
            $('#datepicker, #reminder_date, #edit_reminder_date, #edit_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            }).datepicker("setDate", new Date());
            //Initialize Select2 Elements
            $('.select2').select2();

            //Timepicker
            $('#timepicker, #reminder-time,#edit_reminder-time, #edit-timepicker').datetimepicker({
                format: 'LT',
                defaultDate: new Date()
            });
            //$('#timepicker').data("DateTimePicker").date(moment(new Date ).format('DD/MM/YYYY HH:mm'));
            $('[data-toggle="tooltip"]').tooltip();

            $(document).on('click','.star', function(){
                let value = this.src;
                if(value === "{{asset('images/empty-star.svg')}}"){
                    $('.star').attr({'src':'{{asset('images/filled-star.svg')}}','data-original-title':'Unmark important'}).tooltip('show');
                    toastr.success('Lead marked as important');
                }else{
                    $('.star').attr({'src':'{{asset('images/empty-star.svg')}}','data-original-title':'Mark as important'}).tooltip('show');
                    toastr.info('Lead unmarked as important');
                }
                $.ajax({
                    'url' : '{{route('leads.important')}}',
                    'type' : 'POST',
                    'data' : {'id':'{{$lead->id}}'},
                    'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function(){

                    },
                    success: function (result) {

                    },error: function(xhr,status,error){
                        console.log(xhr, status, error);
                    }
                });
            });


            $(document).on('click','.set-status', function(){

                $('.change-status').val('{{$lead->lead_status}}').change();
            });

            $(document).on('submit','#change-status-form',function(form){
                form.preventDefault();

                let data = $(this).serializeArray();

                $.ajax({
                    'url' : '/leads/status/update',
                    'type' : 'POST',
                    'data' : data,
                    beforeSend: function(){
                        $('.submit-form-btn').attr('disabled',true);
                        $('.spinner').show();
                    },
                    success: function (result) {

                        if(result.success === true)
                        {
                            toastr.success(result.message);
                            setTimeout(function(){
                                location.reload();
                            },1000);
                        }else if(result.success === false){
                            toastr.error(result.message);
                        }

                        $('.submit-form-btn').attr('disabled',false);
                        $('.spinner').hide();

                        $.each(result, function (key, value) {
                            let element = $('.'+key);

                            element.find('.error-'+key).remove();
                            element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
                        });

                    },error: function(xhr,status,error){
                        console.log(xhr, status, error);
                    }
                });

                clear_errors('status','notes');
                ///
            });
        </script>
    @endcan

@stop
