<div class="modal fade editPkg" role="dialog"
     aria-labelledby="exampleModalLongTitle" aria-hidden="true" data-keyboad="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Salary Package</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{ route('salary.update') }}" method="POST" id="update_salary">
                    @csrf
                    <input type="hidden" name="p_id">


                    <div class="row mb-3">
                        <label for="dep" class="col-md-4 col-form-label text-md-end">Department</label>
                        <div class="col-md-6">
                            <select class="form-control" id="department" name="department">
                                <option selected>--Select--</option>
                            </select>
                            <span class="text-danger error-text department_error"></span>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <label for="job" class="col-md-4 col-form-label text-md-end">Job Position</label>
                        <div class="col-md-6">
                            <select class="form-control" id="job" name="job">
                            </select>
                            <span class="text-danger error-text job_error"></span>
                        </div>

                    </div>


                    <div class="row mb-3">
                        <label for="basic" class="col-md-4 col-form-label text-md-end">Basic</label>

                        <div class="col-md-6">
                            <input id="basic" type="text" class="form-control" name="basic" value="">
                            <span class="text-danger error-text basic_error"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="aloowances" class="col-md-4 col-form-label text-md-end">Allowances</label>

                        <div class="col-md-6">
                            <input id="allowances" type="text" class="form-control" name="allowances" value="">
                            <span class="text-danger error-text allowances_error"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="etf" class="col-md-4 col-form-label text-md-end">ETF/EPF</label>

                        <div class="col-md-6">
                            <input id="etf" type="text" class="form-control" name="etf" value="">
                            <span class="text-danger error-text etf_error"></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="amount" class="col-md-4 col-form-label text-md-end">Amount</label>

                        <div class="col-md-6">
                            <input id="amount" type="text" class="form-control" name="amount" value="" readonly>
                            <span class="text-danger error-text amount_error"></span>
                        </div>
                    </div>
                    <button class="btn btn-info btn-block" id="cal" type="button">Calculate</button>
                    <button class="btn btn-success btn-block" type="submit">Update</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div> {{-- model end --}}
