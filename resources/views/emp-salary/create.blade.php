@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Send Employees Salary </h1>
        <a href="{{ route('emp-salary.index') }}" class="d-none d-sm-inline-block btn btn-dark shadow-sm"><i
                class="fa fa-arrow-left fa-sm text-white-50"></i> Back</a>
    </div>

    <div class="card">
        <div class="card-header">Create</div>

        <div class="card-body">
            <form method="POST" action="{{ route('emp-salary.store') }}" id="Empsalary_store">
                @csrf
                <div class="row mb-3">
                    <label for="dep" class="col-md-4 col-form-label text-md-end">Section</label>
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
                    <label for="job" class="col-md-4 col-form-label text-md-end">Job</label>
                    <div class="col-md-6">
                        <select class="form-control" id="job" name="job">
                            <option selected>--Select--</option>

                        </select>
                        <span class="text-danger error-text job_error"></span>
                    </div>

                </div>

                <div class="row mb-3">
                    <label for="amount" class="col-md-4 col-form-label text-md-end">Salary Amount</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="amount" name="amount" readonly>
                        <input type="hidden" id="pkg_id" name="pkg_id">
                        <span class="text-danger error-text amount_error"></span>
                    </div>

                </div>

                <div class="row mb-3">
                    <label for="emp_type" class="col-md-4 col-form-label text-md-end">Select salary sending option</label>

                    <div class="col-md-6">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="emptype" id="all" value="1" checked>
                            <label class="form-check-label" for="all">Send All</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="emptype" id="indv" value="0">
                            <label class="form-check-label" for="indv">Send Individual</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="emp_type" class="col-md-4 col-form-label text-md-end"></label>

                    <div class="col-md-6">
                        <input type="text" id="MonthFormat" class='form-control' name="MonthFormat">
                        <span class="text-danger error-text MonthFormat_error"></span>
                    </div>
                </div>



                <div id="indv_emp">
                    <div class="row mb-3">
                        <label for="empid" class="col-md-4 col-form-label text-md-end"></label>

                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="NIC" id="reqdata" name="search">
                                <button class="btn btn-outline-success" type="button" id="search">Search</button>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="fname" class="col-md-4 col-form-label text-md-end">First Name</label>

                        <div class="col-md-6">
                            <input id="fname" type="text" class="form-control"  readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="lname" class="col-md-4 col-form-label text-md-end">Last Name</label>

                        <div class="col-md-6">
                            <input id="lname" type="text" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="dept" class="col-md-4 col-form-label text-md-end">Department</label>

                        <div class="col-md-6">
                            <input id="dept" type="text" class="form-control" name="dept" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="jobname" class="col-md-4 col-form-label text-md-end">Job</label>

                        <div class="col-md-6">
                            <input id="jobname" type="text" class="form-control" name="jobname" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="nic" class="col-md-4 col-form-label text-md-end">NIC</label>

                        <div class="col-md-6">
                            <input id="nic" type="text" class="form-control" name="nic" readonly>
                        </div>
                    </div>



                </div>

                <div class="row mb-0">
                    <div class="col-md-3 offset-md-4 mt-3">
                        <button type="submit" class="btn btn-primary" id="save">
                            Send
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
            var selectedVal = "";
            var selected = $("input[type='radio'][name='emptype']:checked");
            if (selected.length > 0) {
                selectedVal = selected.val();
                console.log(selectedVal);
            }

            $('#indv_emp').hide();
            //clear fileds
            $('#fname').val('');
            $('#lname').val('');
            $('#dept').val('');
            $('#jobname').val('');
            $('#nic').val('');
        });


        $('#all').change(function() {
            var selectedVal = "";
            var selected = $("input[type='radio'][name='emptype']:checked");
            if (selected.length > 0) {
                selectedVal = selected.val();
                if(selectedVal == 1){
                    $('#indv_emp').hide();

                    //clear fileds
                    $('#fname').val('');
                    $('#lname').val('');
                    $('#dept').val('');
                    $('#jobname').val('');
                    $('#nic').val('');
                }
            }
        });

        $('#indv').change(function() {
            var selectedVal = "";
            var selected = $("input[type='radio'][name='emptype']:checked");
            if (selected.length > 0) {
                selectedVal = selected.val();
                if(selectedVal == 0){
                    $('#indv_emp').show();

                    //clear fileds
                    $('#fname').val('');
                    $('#lname').val('');
                    $('#dept').val('');
                    $('#jobname').val('');
                    $('#nic').val('');
                }
            }
        });

        $('#search').on('click', function(e) {
            var emp_id = $('#reqdata').val();

            $.ajax({
                url: "{{ route('get-emp') }}",
                type: "POST",
                data: {
                    emp_id: emp_id
                },
                success: function(data) {
                    if(data.details == null ){
                        toastr.error('Employee record cannot found!');
                    }else{
                        console.log(data)
                        toastr.success('Employee record found!');
                        $('#fname').val(data.details.user.first_name);
                        $('#lname').val(data.details.user.last_name);
                        $('#dept').val(data.details.department.name);
                        $('#jobname').val(data.details.job.name);
                        $('#contact').val(data.details.contact);
                        $('#nic').val(data.details.nic);
                    }

                    // $('#job').empty();
                    // $.each(data.details.jobs, function(index, job) {
                    //     $('#job').append('<option value="' + job.id + '">' + job.name + '</option>');
                    // });
                }
            })
        });

        $(function() {
            // $('#MonthFormat').MonthPicker();
            $("#MonthFormat").MonthPicker({
                Button: '<button class="btn btn-sm btn-success mt-3" type="button">Select Month</button>'
            });

        });

        $(document).ready(function() {
            $('#department').on('change', function(e) {
                var dep_id = e.target.value;
                $.ajax({
                    url: "{{ route('get-job') }}",
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


        $(document).ready(function() {
            $('#job').on('change', function(e) {
                var job_id = e.target.value;
                $.ajax({
                    url: "{{ route('get-amount') }}",
                    type: "POST",
                    data: {
                        job_id: job_id
                    },
                    success: function(data) {
                        console.log(data);
                        $('#amount').val(data.details.amount);
                        $('#pkg_id').val(data.details.id);
                    }
                })
            });

        });

        //store details
        $('#Empsalary_store').on('submit',function(e){
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


                    }
                }

            })
        });

    </script>

@endsection

