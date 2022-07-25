@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
 <div class="d-sm-flex align-items-center justify-content-between mb-4">
    @if (auth()->user()->is_admin == 1)
    <h1 class="h3 mb-0 text-gray-800">Employee Leaves</h1>
    @else
    <h1 class="h3 mb-0 text-gray-800">your Leaves</h1>
    @endif

    @if (auth()->user()->is_admin == 0)
    <a href="{{ route('leave.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
        class="fas fa-arrow-alt-circle-left fa-sm text-white-50"></i> Request a leave</a>
    @endif

</div>

  <div class="card">
      <div class="card-header">
       Requested Leaves
      </div>
      <div class="card-body">
        <table class="table" id="leave_table">
            <thead>
              <tr>
                <th scope="col">#</th>
                 <th scope="col">Section</th>
                <th scope="col">Employee Name</th>
                <th scope="col">Leave Date</th>
                <th scope="col">Status</th>
                <th scope="col">Created At</th>
                <th scope="col">Handle</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
      </div>
  </div>
   @include('leave.approve')
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
        $('#leave_table').DataTable({
            processing:true,
            info:true,
            ajax:"{{ route('leave.show') }}",
            "pageLength":5,
            "aLengthMenu":[[5,10,25,50,-1],[5,10,25,50,"All"]],
            columns:[
                // {data:'id', name:'id'},
                {data:'DT_RowIndex', name:'DT_RowIndex'},
                {data:'department', name:'department'},
                {data:'employee', name:'name'},
                {data:'request_date', name:'request_date'},
                {data:'status', name:'status'},
                {data:'created_at', name:'created_at'},
                {data:'actions', name:'actions'},
            ]
         });
      });



        //update leave
        $('#update_leave').on('submit', function(e){
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
                            $('.editLeave').find('form')[0].reset();
                            $('.editLeave').modal('hide');
                            // alert(data.msg);
                            if(data.code == 1){
                                toastr.success(data.msg);
                            }else if(data.code == 2){
                                toastr.error(data.msg);
                            }
                            toastr.success(data.msg);
                            $('#leave_table').DataTable().ajax.reload(null, false);
                        }
                }
            })
        });


         //delete
         $(document).on('click','#deleteLeave' , function(){
            var l_id = $(this).data('id');
            var url = '<?= route("leave.delete")?>';

            swal.fire({
              title:'Are you sure?',
              html:'you wont to <b>delete</b> this Leave?',
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
                  $.post(url,{l_id:l_id}, function(data){
                      if(data.code == 1){
                        $('#leave_table').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                      }else{
                        toastr.error(data.msg);
                      }
                  }, 'json');
              }
            })
        });

   $(document).on('click','#editLeave' , function(){
       var leave_id = $(this).data('id')
       $('.editLeave').find('form')[0].reset();
       $('.editLeave').find('span.error-test').text('');

       $.post('<?= route("leave.select") ?>', {leave_id:leave_id}, function(data){
           // alert(data.details.country_name);
           $('.editLeave').find('input[name="l_id"]').val(data.details.id);
           $('.editLeave').find('textarea[name="reason"]').val(data.details.reason);


           $('.editLeave').modal('show');
       },'json');
   });




</script>

@endsection
