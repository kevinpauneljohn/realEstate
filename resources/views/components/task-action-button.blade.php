
<div class="start_task_component_user_span hidden">
    (Click button to start task) 
    <button type="button" class="btn btn-primary btn-xs start_task_component_button" data-name="start" name="start_task" value="{{$tasks->id}}">Start</button>
</div>

<div class="start_task_component_span hidden">
    <span class="text-bold">Status:</span> <span class="text-warning">Pending</span>
</div>

<div class="ongoing_task_component_user_span hidden">
    (Click button if task was finished) 
    <button type="button" class="btn btn-primary btn-primary btn-xs ongoing_task_component_button" data-name="completed" name="start_task" value="{{$tasks->id}}">Completed</button>
</div>

<div class="ongoing_task_component_span hidden">
    <span class="text-bold">Status:</span> <span class="text-primary">On-going</span>
</div>

<div class="completed_task_component_user_span hidden">
    Status: <span class="text-success text-bold">Completed</span>, 
    <i>Do you want to </i>
    <button type="button" class="btn btn-xs btn-warning completed_task_component_button" data-name="reopen" value="{{$tasks->id}}" data-toggle="modal" data-target="#set-status">Re-open?</button>
</div>

<div class="text-bold completed_task_component_span hidden">
    <span class="text-bold">Status:</span> <span class="text-success">Completed</span>
</div>

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
                    <div class="form-group remarks">
                        <textarea class="form-control textEditor" name="remarks" id="remarks" style="min-height: 300px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary remarks-btn" value="Update Status">
                </div>
            </div>
        </div>
    </form>
</div>
