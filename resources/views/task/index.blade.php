@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
 <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Task</h1>
    <a href="{{ route('task.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-plus fa-sm text-white-50"></i> Create Task</a>
</div>

  <div class="card">
      <div class="card-header">
       Task List
      </div>
      <div class="card-body">
        <table class="table" id="task_table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Department</th>
                <th scope="col">Task Name</th>
                <th scope="col">Task Description</th>
                <th scope="col">Task Deadline</th>
                <th scope="col">Status</th>
                <th scope="col">Task Progress</th>
                <th scope="col">Created At</th>
                <th scope="col">Handle</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
      </div>
  </div>
  @include('task.edit-modal')
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
        $('#task_table').DataTable({
            processing:true,
            info:true,
            ajax:"{{ route('task.show') }}",
            "pageLength":5,
            "aLengthMenu":[[5,10,25,50,-1],[5,10,25,50,"All"]],
            columns:[
                // {data:'id', name:'id'},
                {data:'DT_RowIndex', name:'DT_RowIndex'},
                {data:'department', name:'department.name'},
                {data:'task_name', name:'task_name'},
                {data:'task_desc', name:'task_desc'},
                {data:'deadline', name:'deadline'},
                {data:'due', name:'due'},
                {data:'is_complete', name:'is_complete'},
                {data:'created_at', name:'created_at'},
                {data:'actions', name:'actions'},
            ]
         });
      });


      $(document).on('click','#editTask' , function(){
            var task_id = $(this).data('id')
            $('.editTask').find('form')[0].reset();
            $('.editTask').find('span.error-test').text('');

            $.post('<?= route("task.select") ?>', {task_id:task_id}, function(data){
                // alert(data.details.country_name);
                $('.editTask').find('input[name="t_id"]').val(data.details.id);
                $('.editTask').find('input[name="task_name"]').val(data.details.task_name);
                $('.editTask').find('textarea[name="task_desc"]').val(data.details.task_desc);
                $('.editTask').find('input[name="task_deadline"]').val(data.details.deadline);

                $.each(data.depts, function(key, value) {

                       if(value.id == data.details.department_id)
                       {
                           $('select[name="department"]').append('<option value="'+ value.id +'" selected>'+ value.name +'</option>');
                       }
                       else
                       {
                           $('select[name="department"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');

                       }
                   });
                $('.editTask').modal('show');
            },'json');
        });


         //clear dropdown
         $('.editTask').on('hidden.bs.modal', function () {
                $('select[name="department"]').empty();
        })



        //update dept
        $('#update_task').on('submit', function(e){
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
                            $('.editTask').find('form')[0].reset();
                            $('.editTask').modal('hide');
                            // alert(data.msg);
                            toastr.success(data.msg);
                            $('#task_table').DataTable().ajax.reload(null, false);
                        }
                }
            })
        });


         //delete
         $(document).on('click','#deleteTask' , function(){
            var t_id = $(this).data('id');
            var url = '<?= route("task.delete")?>';

            swal.fire({
              title:'Are you sure?',
              html:'you wont to <b>delete</b> this Task',
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
                  $.post(url,{t_id:t_id}, function(data){
                      if(data.code == 1){
                        $('#task_table').DataTable().ajax.reload(null, false);
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
