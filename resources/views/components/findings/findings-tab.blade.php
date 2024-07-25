@if(auth()->user()->can('add findings'))
    <button type="button" class="btn btn-primary btn-sm add-findings-btn">Add Findings</button>
@endif

<div class="table-responsive mt-3">
    <table class="table table-bordered table-hover" id="findings-list">
        <thead>
            <tr>
                <th>Updated At</th>
                <th>Findings</th>
                <th>Updated by</th>
                <th>Action</th>
            </tr>
        </thead>
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


    @push('js')
        @once
            @if(auth()->user()->can('view findings'))
                <script>
                    $(function() {
                        $('#findings-list').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: '{!! route('findings.lists',['commission_request_id' => $commissionRequest->id]) !!}',
                            columns: [
                                { data: 'updated_at', name: 'updated_at'},
                                { data: 'description', name: 'description'},
                                { data: 'user_id', name: 'user_id'},
                                { data: 'action', name: 'action', orderable: false, searchable: false}
                            ],
                            responsive:true,
                            order:[0,'desc'],
                            pageLength: 50
                        });
                    });
                </script>
            @endif
            <script>
                @if(auth()->user()->can('add findings'))
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

                            let table = $('#findings-list').DataTable();
                            table.ajax.reload(null, false);
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

                @if(auth()->user()->can('delete findings'))
                $(document).on('click','.delete-findings',function(findings){
                    let id = this.id;

                    Swal.fire({
                        title: 'Delete Findings?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                    }).then((result) => {
                        console.log(result);
                        if (result.value) {

                            $.ajax({
                                'url' : '/findings/'+id,
                                'type' : 'delete',
                                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                success: function(response){
                                    console.log(response)
                                    if(response.success === true)
                                    {
                                        Swal.fire({
                                            title: response.message,
                                            icon: "success"
                                        });

                                        let table = $('#findings-list').DataTable();
                                        table.ajax.reload(null, false);
                                    }else{
                                        Swal.fire({
                                            title: response.message,
                                            icon: "error"
                                        });
                                    }
                                },error: function(xhr, status, error){
                                    console.log(xhr)
                                }
                            });

                        }
                    });


                });
                @endif
                @endif
            </script>
        @endonce
    @endpush

