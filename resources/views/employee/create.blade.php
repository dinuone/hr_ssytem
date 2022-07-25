@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Create an Employee </h1>
    <a href="{{ route('employee.index') }}" class="d-none d-sm-inline-block btn btn-dark shadow-sm"><i
            class="fa fa-arrow-left fa-sm text-white-50"></i> Back</a>
</div>

<div class="card">
    <div class="card-header">Create</div>

    <div class="card-body">
        <form method="POST" action="{{ route('employee.store') }}" id="emp_store">
            @csrf

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
                    <input id="address" type="text" class="form-control" name="address" value="{{ old('last_name') }}" autocomplete="last_name" autofocus>
                    <span class="text-danger error-text address_error"></span>
                </div>
            </div>

            <div class="row mb-3">
                <label for="dep" class="col-md-4 col-form-label text-md-end">Department</label>
                <div class="col-md-6">
                    <select class="form-control" id="department" name="department">
                        <option selected>--Select--</option>
                        @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{$department->name}}</option>
                        @endforeach
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
                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control" name="password">
                    <span class="text-danger error-text password_error"></span>
                </div>
            </div>



            <div class="row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        toastr.options.preventDuplicates = true;
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });

         $(document).ready(function() {
          $('#department').on('change', function(e) {
              var dep_id = e.target.value;
              $.ajax({
                  url: "{{ route('employee.get-dep') }}",
                  type: "POST",
                  data: {
                    dep_id: dep_id
                  },
                  success: function(data) {
                    console.log(data);
                      $('#job').empty();
                      $.each(data.details.jobs, function(index, job) {
                          $('#job').append('<option value="' + job.id + '">' + job.name + '</option>');
                      });
                  }
              })
          });

      });


       //store details
       $('#emp_store').on('submit',function(e){
          e.preventDefault();
          var form = this;
          $.ajax({
              url:$(form).attr('action'),
              method:$(form).attr('method'),
              data:new FormData(form),
              processData:false,
              dataType:'json',
              contentType:false,
              beforeSend:function(){
                  $(form).find('span.error-text').text('');
              },

              success:function(data){
                  if(data.code == 0){
                      $.each(data.error,function(prefix, val){
                          $(form).find('span.'+prefix+'_error').text(val[0]);
                      });
                  }else if(data.code == 2){
                      toastr.error(data.msg);
                  }
                  else{
                      $(form)[0].reset();
                      $('#job').empty();
                      // alert(data.msg);
                      toastr.success(data.msg);
                    //   window.location.href = "/pooja";

                  }
              }

          })
      });
    </script>
@endsection
