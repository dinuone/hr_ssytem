@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Create a Task </h1>
    <a href="{{ route('task.index') }}" class="d-none d-sm-inline-block btn btn-dark shadow-sm"><i
            class="fa fa-arrow-left fa-sm text-white-50"></i> Back</a>
</div>

<div class="card">
    <div class="card-header">Create</div>

    <div class="card-body">
        <form method="POST" action="{{ route('task.store') }}" id="task_store">
            @csrf

            <div class="row mb-3">
                <label for="task_name" class="col-md-4 col-form-label text-md-end">Task Name</label>

                <div class="col-md-6">
                    <input id="task_name" type="text" class="form-control @error('task_name') is-invalid @enderror" name="task_name" value="{{ old('task_name') }}"  autofocus>
                    @error('task_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                     @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="task_desc" class="col-md-4 col-form-label text-md-end">Task Description</label>

                <div class="col-md-6">
                    <textarea id="task_desc" rows="6" type="text" class="form-control @error('task_desc') is-invalid @enderror" name="task_desc" value="{{ old('task_desc') }}"  autofocus></textarea>
                    @error('task_desc')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                     @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="dep" class="col-md-4 col-form-label text-md-end">Department</label>
                <div class="col-md-6">
                    <select class="form-control @error('department') is-invalid @enderror" id="department" name="department">
                        <option selected>--Select--</option>
                        @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{$department->name}}</option>
                        @endforeach
                       </select>
                       @error('department')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                     @enderror
                </div>

             </div>


             <div class="row mb-3">
                <label for="task_deadline" class="col-md-4 col-form-label text-md-end">Deadline</label>

                <div class="col-md-6">
                    <input id="task_deadline" type="date" class="form-control @error('task_deadline') is-invalid @enderror" name="task_deadline" value="" >
                    @error('task_deadline')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
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
                  }else{
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
