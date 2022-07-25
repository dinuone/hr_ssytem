<div class="modal fade editDepartment" role="dialog"
    aria-labelledby="exampleModalLongTitle" aria-hidden="true" data-keyboad="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Department</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <form action="{{ route('department.update') }}" method="POST" id="update_dept">
                @csrf
                <input type="hidden" name="d_id">
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" name="name" placeholder="Department Name..." class="form-control">
                    <span class="text-danger error-text name_error"></span>
                </div>
                <button class="btn btn-success btn-block" type="submit">Update</button>
            </form>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div> {{-- model end --}}
