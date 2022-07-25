@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Create a Department </h1>
    <a href="{{ route('department.index') }}" class="d-none d-sm-inline-block btn btn-dark shadow-sm"><i
            class="fa fa-arrow-left fa-sm text-white-50"></i> Back</a>
</div>

<div class="card">
    <div class="card-header">Create</div>

    <div class="card-body">
        <form method="POST" action="{{ route('department.store') }}" id="department">
            @csrf

            <div class="row mb-3">
                <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

                <div class="col-md-6">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}"  autocomplete="Name" autofocus>
                    <span class="text-danger error-text name_error"></span>
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


      $(function(){

      //add new country
      $('#department').on('submit',function(e){
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
                      // alert(data.msg);
                      $('#ctg_table').DataTable().ajax.reload(null, false);
                      toastr.success(data.msg);
                  }
              }

          })
      });

    });



</script>

@endsection
