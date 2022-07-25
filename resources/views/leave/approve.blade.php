<div class="modal fade editLeave" role="dialog"
     aria-labelledby="exampleModalLongTitle" aria-hidden="true" data-keyboad="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Check Leave Request</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{ route('leave.approve') }}" method="POST" id="update_leave">
                    @csrf
                    <input type="hidden" name="l_id">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="1">Approved</option>
                            <option value="0">Reject</option>
                            <option value="2">Pending</option>
                        </select>
                        <span class="text-danger error-text status_error"></span>
                    </div>
                    <div class="form-group">
                        <textarea id="reason" rows="6" type="text" class="form-control" name="reason"  readonly></textarea>
                    </div>
                    <button class="btn btn-success btn-block" type="submit">Submit</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div> {{-- model end --}}
