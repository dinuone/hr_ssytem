<div class="modal fade editJob" role="dialog"
    aria-labelledby="exampleModalLongTitle" aria-hidden="true" data-keyboad="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Job</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <form action="{{ route('jobs.update') }}" method="POST" id="update_job">
                @csrf
                <input type="hidden" name="j_id">
                <div class="form-group">
                    <label>Section</label>
                    <select name="department" id="dept" class="form-control">
                    </select>
                    <span class="text-danger error-text department_error"></span>
                </div>
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" name="name" placeholder="Name..." class="form-control">
                    <span class="text-danger error-text name_error"></span>
                </div>
                <button class="btn btn-success btn-block" type="submit">Update</button>
            </form>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div> {{-- model end --}}
