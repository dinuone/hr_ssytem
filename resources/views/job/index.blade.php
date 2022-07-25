@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
 <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Jobs</h1>
    <a href="{{ route('jobs.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-plus fa-sm text-white-50"></i> Create a Job</a>
</div>
  <div class="card">
      <div class="card-header">
        Job List
      </div>
      <div class="card-body">
        <table class="table" id="job_table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Section</th>
                <th scope="col">Name</th>
                <th scope="col">Created At</th>
                <th scope="col">Handle</th>
              </tr>
            </thead>
            <tbody>
            @foreach($jobs as $job)
            <tr>
                <td>{{$job->id}}</td>
                <td>{{$job->department->name}}</td>
                <td>{{$job->name}}</td>
                <td>{{$job->created_at->toDatestring()}}</td>
                <td>
                    <button class="btn btn-primary" data-id="{{$job->id}}" id="editJob">Update</button>
                     <button class="btn btn-danger" data-id="{{$job->id}}" id="deleteJob">Delete</button>
                </td>
            </tr>
            @endforeach
            </tbody>
          </table>
      </div>

  </div>
  @include('job.edit-modal')
@endsection



@section('scripts')

<script>
   toastr.options.preventDuplicates = true;
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });


      $(document).on('click','#editJob' , function(){
            var job_id = $(this).data('id')

            $('.editJob').find('form')[0].reset();
            $('.editJob').find('span.error-test').text('');

            $.post('<?= route("jobs.select") ?>', {job_id :job_id}, function(data){
                // alert(data.details.country_name);
                $('.editJob').find('input[name="j_id"]').val(data.details.id);
                $('.editJob').find('input[name="name"]').val(data.details.name);
                // $('select[name="department"]').empty();
                // $('#dept').append('<option value="'+data.details.department_id+'"selected>'+'-- '+data.details.department.name+' --'+'</option>');

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

                $('.editJob').modal('show');
            },'json');
        });



   $(document).ready(function() {
       $('#job_table').DataTable( {
           dom: 'Bfrtip',
           buttons: [

               {
                   extend: 'excelHtml5',
                   title: 'Job Report',
                   exportOptions: {
                       columns: [ 0, 1, 2,3 ]
                   }
               },
               {
                   extend: 'pdfHtml5',
                   title: 'Job Report',
                   exportOptions: {
                       columns: [ 0, 1, 2,3 ]
                   },
                   pageSize: 'a4'
               },
               'colvis'
           ]
       } );
   } );

        //clear dropdown
        $('.editJob').on('hidden.bs.modal', function () {
                $('select[name="department"]').empty();
        })



        //update dept
         $('#update_job').on('submit', function(e){
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
                            $('.editJob').find('form')[0].reset();
                            $('.editJob').modal('hide');
                            // alert(data.msg);
                            toastr.success(data.msg);
                            window.location.reload();
                        }
                }
            })
        });


         //delete
         $(document).on('click','#deleteJob' , function(){
            var j_id = $(this).data('id');
             console.log(j_id)
            var url = '<?= route("jobs.delete")?>';

            swal.fire({
              title:'Are you sure?',
              html:'you wont to <b>delete</b> this Job?',
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
                  $.post(url,{j_id:j_id}, function(data){
                      if(data.code == 1){
                        window.location.reload();
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
