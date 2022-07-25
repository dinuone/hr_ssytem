@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Salary Packages</h1>
        <a href="{{ route('salary.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Create Salary Package</a>
    </div>

    <div class="card">
        <div class="card-header">
            Salary Package List
        </div>
        <div class="card-body">
            <table class="table" id="pkg_table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Department</th>
                    <th scope="col">Job</th>
                    <th scope="col">Basic</th>
                    <th scope="col">Allowance</th>
                    <th scope="col">EPF/ETF</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Handle</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pkgs as $pkg)
                    <tr>
                        <td>{{$pkg->id}}</td>
                        <td>{{$pkg->department->name}}</td>
                        <td>{{$pkg->job->name}}</td>
                        <td>{{$pkg->basic}}</td>
                        <td>{{$pkg->allowances}}</td>
                        <td>{{$pkg->epf_etf}}%</td>
                        <td>{{$pkg->amount}}</td>
                        <td>{{$pkg->created_at->toDatestring()}}</td>
                        <td>
                            <button class="btn btn-primary" id="editpkg" data-id="{{$pkg->id}}">Update</button>
                            <button class="btn btn-danger" data-id="{{$pkg->id}}" id="deletePkg">Delete</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('salary.edit-modal')
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
            $('#pkg_table').DataTable( {
                dom: 'Bfrtip',
                buttons: [

                    {
                        extend: 'excelHtml5',
                        title: 'Salary Package Report',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7 ]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Salary Package Report',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7]
                        },
                        pageSize: 'Legal'
                    },
                    'colvis'
                ]
            } );
        });

        $(document).on('click','#editpkg' , function(){
            var pkg_id = $(this).data('id')
            $('.editPkg').find('form')[0].reset();
            $('.editPkg').find('span.error-test').text('');

            $.post('<?= route("salary.edit") ?>', {pkg_id:pkg_id }, function(data){
                // alert(data.details.country_name);
                $('.editPkg').find('input[name="p_id"]').val(data.details.id);
                $('.editPkg').find('input[name="basic"]').val(data.details.basic);
                $('.editPkg').find('input[name="allowances"]').val(data.details.allowances);
                $('.editPkg').find('input[name="etf"]').val(data.details.epf_etf);
                $('.editPkg').find('input[name="amount"]').val(data.details.amount);
                $('.editPkg').find('select[name="job"]').append('<option value="'+ data.details.job_id +'" selected>'+ data.details.job.name +'</option>');
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


                $('.editPkg').modal('show');
            },'json');
        });


        //clear dropdown
        $('.editPkg').on('hidden.bs.modal', function () {
            $('select[name="department"]').empty();
        })

        //dropdown on change
        $(document).ready(function() {
            $('#department').on('change', function(e) {
                var dep_id = e.target.value;
                $.ajax({
                    url: "{{ route('salary.get-job') }}",
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

        //update dept
        $('#update_salary').on('submit', function(e){
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
                        $('.editPkg').find('form')[0].reset();
                        $('.editPkg').modal('hide');
                        // alert(data.msg);
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            })
        });


        //delete
        $(document).on('click','#deletePkg' , function(){
            var p_id = $(this).data('id');
            var url = '<?= route("salary.delete")?>';

            swal.fire({
                title:'Are you sure?',
                html:'you wont to <b>delete</b> this Salary Package?',
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
                    $.post(url,{p_id:p_id}, function(data){
                        if(data.code == 1){

                            toastr.success(data.msg);
                            window.location.reload()
                        }else{
                            toastr.error(data.msg);
                        }
                    }, 'json');
                }
            })
        });

        //inputs change
        $('#cal').on('click', function(e) {
            $('#update_salary').find('span.error-text').text('');
            $('#basic').removeClass("is-invalid");
            $('#allowances').removeClass("is-invalid");
            $('#etf').removeClass("is-invalid");

            var  basic = $('input[name="basic"]').val();
            var  basicval =  parseInt(basic);

            var allowance = $('input[name="allowances"]').val();
            var  allowanceVal =  parseInt(allowance);

            var etf = $('input[name="etf"]').val();
            var  etfVal =  parseInt(etf);

            var status = false;

            //check  basic value empty
            if (!$('#basic').val()) {
                $('#basic').addClass("is-invalid");
                var basic = 'basic'
                $('#update_salary').find('span.'+basic+'_error').text('Please enter basic amount');
                status =false;
            }else{
                //check enter value is integer
                if($.isNumeric(basicval)){
                    if(basicval >= 1 && basicval <= 1000000){
                        $('#basic').addClass("is-valid");
                        status =true;
                    }
                }else{
                    $('#basic').addClass("is-invalid");
                    var basic = 'basic'
                    $('#update_salary').find('span.'+basic+'_error').text('Please enter integer');
                    status =false;
                }
            }

            //check allowances inputs
            if (!$('#allowances').val()) {
                $('#allowances').addClass("is-invalid");
                var basic = 'allowances'
                $('#update_salary').find('span.'+basic+'_error').text('Please enter allowance amount');
                status = false;
            }else{
                //check enter value is integer
                if($.isNumeric(allowanceVal)){
                    if(allowanceVal >= 1 && allowanceVal <= 1000000){
                        $('#allowances').addClass("is-valid");
                        status = true;
                    }
                }else{
                    $('#allowances').addClass("is-invalid");
                    var basic = 'allowances'
                    $('#update_salary').find('span.'+basic+'_error').text('Please enter integer');
                    status = false
                }
            }

            //check etf value empty and integer
            if (!$('#etf').val()) {
                $('#etf').addClass("is-invalid");
                var basic = 'etf'
                $('#update_salary').find('span.'+basic+'_error').text('Please enter ETF/EPF amount');
                status = false;
            }else{
                //check enter value is integer
                if($.isNumeric(etfVal)){
                    if(etfVal >= 1 && etfVal <= 1000000){
                        $('#etf').addClass("is-valid");
                        status = true;
                    }
                }else{
                    $('#etf').addClass("is-invalid");
                    var basic = 'etf'
                    $('#update_salary').find('span.'+basic+'_error').text('Please enter integer');
                    status = false;
                }
            }

            if(status == true){
                var calEtf = (basicval* etfVal / 100);
                var setBasic = basicval-calEtf;
                var total = setBasic + allowanceVal;
                $('#amount').val(total)


            }

        });





    </script>

@endsection
