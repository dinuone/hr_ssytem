@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
 <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Employees</h1>
    <a href="{{ route('employee.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-user fa-sm text-white-50"></i> Create an Employee</a>
</div>
  <div class="card">
      <div class="card-header">
       Employee List
      </div>
      <div class="card-body">
        <table class="table-responsive" id="emp_table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                  <th scope="col">NIC</th>
                <th scope="col">Address</th>
                <th scope="col">Username</th>
                <th scope="col">e-mail</th>
                <th scope="col">Department</th>
                <th scope="col">Job</th>
                <th scope="col">Available Leaves</th>
                <th scope="col">Date of Birth</th>
                <th scope="col">Date Hired</th>
                <th scope="col" width="150px">Handle</th>
              </tr>
            </thead>
            <tbody>
            @foreach($employees as $emp)
                <tr>
                    <td>{{$emp->id}}</td>
                    <td>{{$emp->user->first_name}}</td>
                    <td>{{$emp->user->last_name}}</td>
                    <td>{{$emp->nic}}</td>
                    <td>{{$emp->address}}</td>
                    <td>{{$emp->username}}</td>
                    <td>{{$emp->user->email}}</td>
                    <td>{{$emp->department->name}}</td>
                    <td>{{$emp->job->name}}</td>
                    <td>{{$emp->avb_leave}}</td>
                    <td>{{$emp->birth_date}}</td>
                    <td>{{$emp->date_hired}}</td>
                    <td  width="150px">
                        <button class="btn btn-primary btn-sm" data-id="{{$emp->id}}" id="editEmp">Update</button>
                        <button class="btn btn-danger btn-sm" data-id="{{$emp->id}}" id="deleteEmp">Delete</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
          </table>
      </div>
@include('employee.edit-modal')
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
        $("#password").hide();
        $("#lbl_psw").hide();

      });

   $(document).ready(function() {
       $('#emp_table').DataTable( {
           dom: 'Bfrtip',
           buttons: [

               {
                   extend: 'excelHtml5',
                   title: 'Employee Report',
                   exportOptions: {
                       columns: [ 0, 1, 2,3,4,5,6,7,8,9,10 ]
                   }
               },
               {
                   extend: 'pdfHtml5',
                   title: 'Employee Report',
                   exportOptions: {
                       columns: [ 0, 1, 2,3,4,5,6,7,8,9,10 ]
                   },
                   pageSize: 'Legal'
               },
               'colvis'
           ]
       } );
   } );

         //clear dropdown
         $('.editTask').on('hidden.bs.modal', function () {
                $('select[name="department"]').empty();
        })


        //check box value
        $(document).ready(function(){
                $('input[type="checkbox"]').click(function(){
                    if($(this).prop("checked") == true){
                        $("#password").show();
                        $("#lbl_psw").show();
                    }
                    else if($(this).prop("checked") == false){
                        $("#password").hide();
                        $("#lbl_psw").hide();
                    }
                });
         });

      $(document).on('click','#editEmp' , function(){
            var emp_id = $(this).data('id');
            var user_id = $('.editEmp').find('input[name="user_id"]').val();

            $('.editEmp').find('form')[0].reset();
            $('.editEmp').find('span.error-test').text('');

            $.post('<?= route("employee.edit") ?>', {emp_id:emp_id,user_id:user_id}, function(data){
                // alert(data.details.country_name);
                $('.editEmp').find('input[name="e_id"]').val(data.details.id);
                $('.editEmp').find('input[name="user_id"]').val(data.details.user_id);
                $('.editEmp').find('input[name="username"]').val(data.details.username);
                $('.editEmp').find('input[name="first_name"]').val(data.details.user.first_name);
                $('.editEmp').find('input[name="last_name"]').val(data.details.user.last_name);
                $('.editEmp').find('input[name="address"]').val(data.details.address);
                $('.editEmp').find('input[name="email"]').val(data.details.user.email);
                $('.editEmp').find('input[name="date_hired"]').val(data.details.date_hired);
                $('.editEmp').find('input[name="nic"]').val(data.details.nic);
                $('.editEmp').find('input[name="birthdate"]').val(data.details.birth_date);
                $('.editEmp').find('select[name="job"]').append('<option value="'+ data.details.job_id +'" selected>'+ data.details.job.name +'</option>');
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

                $('.editEmp').modal('show');
            },'json');
        });

         //update employee
         $('#update_emp').on('submit', function(e){
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
                            $('.editEmp').find('form')[0].reset();
                            $('.editEmp').modal('hide');
                            // alert(data.msg);
                            toastr.success(data.msg);
                            window.location.reload();
                        }
                }
            })
        });

        //dropdown on change
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

         //delete
         $(document).on('click','#deleteEmp' , function(){
            var e_id = $(this).data('id');
            var url = '<?= route("employee.delete")?>';

            swal.fire({
              title:'Are you sure?',
              html:'you wont to <b>delete</b> this employee?',
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
                  $.post(url,{e_id:e_id}, function(data){
                      if(data.code == 1){
                        // $('#emp_table').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                          window.location.reload();
                      }else{
                        toastr.error(data.msg);
                      }
                  }, 'json');
              }
            })
        });


</script>

@endsection
