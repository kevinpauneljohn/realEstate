<div class="edit-client-modal-container">

@can('edit client payment')
    <!--add new payment modal-->
        <div class="modal fade" id="edit-client-payment-modal">
            <form role="form" id="edit-client-payment-form" class="form-submit">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Payment</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="form-group date_received">
                                <label for="date_received">Date of Payment</label>
                                <input type="date" name="date_received" class="form-control" id="date_received" value="{{$date}}">
                            </div>

                            <div class="form-group amount">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" class="form-control" id="amount" step="any" min="0" value="{{$client_payment->amount}}">
                            </div>
                            <div class="form-group description">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control" id="description">{{$client_payment->details}}</textarea>
                            </div>
                            <div class="form-group remarks">
                                <label for="remarks">Remarks</label>
                                <textarea name="remarks" class="form-control" id="remarks">{{$client_payment->remarks}}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary dhg-client-project-form-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new payment modal-->
    @endcan
</div>
