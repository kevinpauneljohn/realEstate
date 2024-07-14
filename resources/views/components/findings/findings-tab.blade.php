@if(auth()->user()->can('add findings'))
    <button type="button" class="btn btn-primary btn-sm add-findings-btn">Add Findings</button>
@endif

<div class="table-responsive mt-3">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Updated At</th>
                <th>Findings</th>
                <th>Updated by</th>
            </tr>
        </thead>
        <tbody>
            @if(collect($findings)->count() < 1)
                <tr><td colspan="3" class="text-center">No available data</td></tr>
            @else
                @foreach($findings as $finding)
                    <tr>
                        <td>{{$finding->updated_at->format('m-d-Y')}}</td>
                        <td class="w-50">{!! nl2br($finding->description) !!}</td>
                        <td>{{$finding->user->fullname}}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>


@if(auth()->user()->can('add findings'))
    <form id="findings-form">
        @csrf
        <div class="modal fade" id="findings-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group findings">
                            <textarea class="form-control" name="findings" id="findings" style="height:200px;"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="commission_request_id" value="{{$commissionRequest->id}}">
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save">Save</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>
@endif

@if(auth()->user()->can('add findings'))
    @push('js')
        @once
            <script>
                let findingsModal = $('#findings-modal');
                $(document).on('click','.add-findings-btn', function(){
                    findingsModal.modal('toggle')
                    findingsModal.find('.modal-title').text('Add Findings')
                })

                $(document).on('submit','#findings-form', function(form){
                    form.preventDefault();
                    let data = $(this).serializeArray();
                    $.ajax({
                        url: '{{route('findings.store')}}',
                        type: 'post',
                        data: data,
                        beforeSend: function(){
                            $('.text-danger').remove();
                        }
                    }).done(function(response){
                        if(response.success === true)
                        {
                            $('#findings-form').trigger('reset')
                            findingsModal.modal('toggle')
                            Swal.fire({
                                title: response.message,
                                icon: "success"
                            });
                            setTimeout(function(){
                                window.location.reload();
                            },1500)
                        }else{
                            Swal.fire({
                                title: response.message,
                                icon: "error"
                            });
                        }
                    }).fail(function(xhr, status, error){
                        console.log(xhr)
                        $.each(xhr.responseJSON.errors, function(key, value){
                            findingsModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                        })
                    }).always(function(){

                    })
                })
            </script>
        @endonce
    @endpush
@endif
