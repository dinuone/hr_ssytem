@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Request a leave </h1>
    <a href="{{ route('leave.index') }}" class="d-none d-sm-inline-block btn btn-dark shadow-sm"><i
            class="fa fa-arrow-left fa-sm text-white-50"></i> Back</a>
</div>

<div class="card">
    <div class="card-header">Leave Application</div>

    <div class="card-body">
        <form method="POST" action="{{ route('leave.store') }}" id="leave_store">
            @csrf
            <input type="hidden" name="emp_id" value="{{ $emp->id }}">
            <div class="row mb-3">
                <label for="reason" class="col-md-4 col-form-label text-md-end">Reason</label>

                <div class="col-md-6">
                    <textarea id="reason" rows="6" type="text" class="form-control @error('reason') is-invalid @enderror" name="reason" value="{{ old('reason') }}"  autofocus></textarea>
                    @error('reason')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                     @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="dep" class="col-md-4 col-form-label text-md-end">Section</label>
                <div class="col-md-6">
                    <input  type="text" class="form-control"  value="{{ $department->name }}"  readonly>
                   <input type="hidden" value="{{ $department->id }}" name="department">
                </div>

             </div>


             <div class="row mb-3">
                <label for="leave_date" class="col-md-4 col-form-label text-md-end">Leave Date</label>

                <div class="col-md-6">
                    <input id="leave_date" type="date" class="form-control @error('leave_date') is-invalid @enderror" name="leave_date" value="" >
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
                        Request
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
       $('#leave_store').on('submit',function(e){
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
                      $('#reason').empty();
                      $('#task_deadline').empty();
                    //   alert(data.msg);
                      toastr.success(data.msg);
                    //   window.location.href = "/pooja";

                  }
              }

          })
      });
    </script>
@endsection
