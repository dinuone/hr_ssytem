<div class="modal fade editTask" role="dialog"
    aria-labelledby="exampleModalLongTitle" aria-hidden="true" data-keyboad="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Task</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <form action="{{ route('task.update') }}" method="POST" id="update_task">
                @csrf
                <input type="hidden" name="t_id">
                <div class="form-group">
                    <label>Department</label>
                            <select name="department" id="department" class="form-control form-control-border">
                            </select>
                    <span class="text-danger error-text department_error"></span>
                </div>
                <div class="form-group">
                    <label for="">Task Name</label>
                    <input type="text" name="task_name" placeholder="Task Name..." class="form-control">
                    <span class="text-danger error-text task_name_error"></span>
                </div>
                <div class="form-group">
                    <label for="">Task Desc</label>
                    <textarea type="text" name="task_desc" placeholder="Description..." class="form-control"></textarea>
                    <span class="text-danger error-text task_desc_error"></span>
                </div>

                <div class="form-group">
                    <label for="">Deadline</label>
                    <input id="task_deadline" type="date" class="form-control" name="task_deadline">
                    <span class="text-danger error-text task_deadline_error"></span>
                </div>
                <button class="btn btn-success btn-block" type="submit">Update</button>
            </form>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div> {{-- model end --}}
