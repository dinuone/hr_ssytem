<div class="modal fade editEmp" role="dialog"
    aria-labelledby="exampleModalLongTitle" aria-hidden="true" data-keyboad="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit an Employee</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <form action="{{ route('employee.update') }}" method="POST" id="update_emp">
                @csrf
                <input type="hidden" name="e_id">
                <input type="hidden" name="user_id">
                <div class="row mb-3">
                    <label for="username" class="col-md-4 col-form-label text-md-end">Username</label>

                    <div class="col-md-6">
                        <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}"  autocomplete="username" autofocus>
                        <span class="text-danger error-text username_error"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="first_name" class="col-md-4 col-form-label text-md-end">First Name</label>
                    <div class="col-md-6">
                        <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" autocomplete="first_name" autofocus>
                        <span class="text-danger error-text first_name_error"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="last_name" class="col-md-4 col-form-label text-md-end">Last Name</label>
                    <div class="col-md-6">
                        <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" autocomplete="last_name" autofocus>
                        <span class="text-danger error-text last_name_error"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="nic" class="col-md-4 col-form-label text-md-end">NIC</label>
                    <div class="col-md-6">
                        <input id="nic" type="text" class="form-control" name="nic" value="{{ old('nic') }}" autocomplete="nic" autofocus>
                        <span class="text-danger error-text nic_error"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="address" class="col-md-4 col-form-label text-md-end">Address</label>
                    <div class="col-md-6">
                        <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}" autocomplete="last_name" autofocus>
                        <span class="text-danger error-text address_error"></span>
                    </div>
                </div>

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
                    <label for="birth" class="col-md-4 col-form-label text-md-end">Date of Birth</label>

                    <div class="col-md-6">
                        <input id="birth" type="date" class="form-control" name="birthdate" value=""  autocomplete="email">
                        <span class="text-danger error-text birthdate_error"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="date_hired" class="col-md-4 col-form-label text-md-end">Date hired</label>

                    <div class="col-md-6">
                        <input id="date_hired" type="date" class="form-control" name="date_hired"  autocomplete="email">
                        <span class="text-danger error-text date_hired_error"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"  autocomplete="email">
                        <span class="text-danger error-text email_error"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="custom-control custom-checkbox small">
                            <input type="checkbox" class="custom-control-input"  name="new_psw" id="new_psw">
                            <label class="custom-control-label" for="new_psw">Create New Password?</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label id="lbl_psw" class="col-md-4 col-form-label text-md-end">New Password</label>

                    <div class="col-md-6">
                        <input id="password" type="text" class="form-control" name="password" value="{{ old('password') }}"  autocomplete="email">
                        <span class="text-danger error-text password_error"></span>
                    </div>
                </div>

                <button class="btn btn-success btn-block" type="submit">Update</button>
            </form>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div> {{-- model end --}}
