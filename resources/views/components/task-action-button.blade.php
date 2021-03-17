@if($tasks->status === "pending")
    @if($tasks->assigned_to === auth()->user()->id)
        (Click button to start task) <button type="button" class="btn btn-primary btn-xs" name="start_task" value="{{$tasks->id}}">Start</button>
        @else
        <span class="text-bold">Status: <span class="text-warning">Pending</span></span>
        @endif
@elseif($tasks->status === "on-going")
    @if($tasks->assigned_to === auth()->user()->id)
        (Click button if task was finished) <button type="button" class="btn btn-primary btn-primary btn-xs" name="start_task" value="{{$tasks->id}}">Completed</button>
    @else
        <span class="text-bold">Status: <span class="text-primary">On-going</span></span>
    @endif
@elseif($tasks->status === "completed")
    @if(auth()->user()->hasRole(['super admin','admin','account manager']))
        <button type="button" class="btn btn-xs btn-warning" value="{{$tasks->id}}" data-toggle="modal" data-target="#set-status">Re-open</button>

        <div class="modal fade" id="set-status">
            <form role="form" id="set-status-form">
                @csrf
                <input type="hidden" name="task_id" value="{{$tasks->id}}">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Remarks</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <textarea class="form-control" name="remarks" style="min-height: 300px;"></textarea>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-checklist-btn" value="Update Status">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        @else
        <span class="text-bold">Status: <span class="text-success">Completed</span></span>
        @endif
@endif
