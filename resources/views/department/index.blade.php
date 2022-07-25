@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
 <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Department</h1>
    <a href="{{ route('department.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-plus fa-sm text-white-50"></i> Create a Department</a>
</div>

  <div class="card">
      <div class="card-header">
        Section List
      </div>
      <div class="card-body">
        <table class="table" id="department_table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Created at</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
      </div>
      @include('department.edit-modal')
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

        //get all countries
        $('#department_table').DataTable({
            processing:true,
            info:true,
            ajax:"{{ route('department.show') }}",
            "pageLength":5,
            "aLengthMenu":[[5,10,25,50,-1],[5,10,25,50,"All"]],
            columns:[
                // {data:'id', name:'id'},
                {data:'DT_RowIndex', name:'DT_RowIndex'},
                {data:'name', name:'name'},
                {data:'created_at', name:'created_at'},
                {data:'actions', name:'actions'},
            ]
         });
      });


      $(document).on('click','#editDepartment' , function(){
            var dep_id = $(this).data('id')
            $('.editDepartment').find('form')[0].reset();
            $('.editDepartment').find('span.error-test').text('');

            $.post('<?= route("department.select") ?>', {dep_id :dep_id}, function(data){
                // alert(data.details.country_name);
                $('.editDepartment').find('input[name="d_id"]').val(data.details.id);
                $('.editDepartment').find('input[name="name"]').val(data.details.name);
                $('.editDepartment').modal('show');
            },'json');
        });



        //update dept
         $('#update_dept').on('submit', function(e){
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

                success: function(data){
                    if(data.code == 0){
                            $.each(data.error,function(prefix, val){
                                $(form).find('span.'+prefix+'_error').text(val[0]);
                            });
                        }else{
                            $('.editDepartment').find('form')[0].reset();
                            $('.editDepartment').modal('hide');
                            // alert(data.msg);
                            toastr.success(data.msg);
                            $('#department_table').DataTable().ajax.reload(null, false);
                        }
                }
            })
        });


         //delete
         $(document).on('click','#deleteDepartment' , function(){
            var d_id = $(this).data('id');
            var url = '<?= route("department.delete")?>';

            swal.fire({
              title:'Are you sure?',
              html:'you wont to <b>delete</b> this Department?',
              showCancelButton:true,
              showCloseButton:true,
              cancelButtonText:'cancel',
              confirmButtonText:'yes, Delete',
              cancelButtonColor:'#d33',
              confirmButtonColor:'#556ee6',
              width:300,
              allowOutsideClick:false
            }).then(function(result){
              if(result.value){
                  $.post(url,{d_id:d_id}, function(data){
                      if(data.code == 1){
                        $('#department_table').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                      }else{
                        toastr.error(data.msg);
                      }
                  }, 'json');
              }
            })
        });


</script>

@endsection
