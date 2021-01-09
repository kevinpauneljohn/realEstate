
    <div class="modal fade" id="edit-role-modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form id="edit-role-form">
                    <div class="modal-header">
                        <h6 class="modal-title">Update Role</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group role">
                            <label id="client-name"></label>
                            <select class="select2 form-control" name="role" id="role" style="width: 100%;">
                                <option value=""></option>
                                <option value="client">Client</option>
                                <option value="architect">Architect</option>
                                <option value="builder admin">Builder Admin</option>
                                <option value="builder member">Builder Member</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary role-btn" value="Save">
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>

