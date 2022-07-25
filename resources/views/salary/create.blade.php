@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Salary Package </h1>
        <a href="{{ route('salary.index') }}" class="d-none d-sm-inline-block btn btn-dark shadow-sm"><i
                class="fa fa-arrow-left fa-sm text-white-50"></i> Back</a>
    </div>

    <div class="card">
        <div class="card-header">Create</div>

        <div class="card-body">
            <form method="POST" action="{{ route('salary.store') }}" id="salary_store">
                @csrf
                <div class="row mb-3">
                    <label for="dep" class="col-md-4 col-form-label text-md-end">Department</label>
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
                    <label for="basic" class="col-md-4 col-form-label text-md-end">Basic</label>

                    <div class="col-md-6">
                        <input id="basic" type="text" class="form-control" name="basic" value="{{ old('basic') }}"  autofocus>
                        <span class="text-danger error-text basic_error"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="allowances" class="col-md-4 col-form-label text-md-end">Allowances</label>

                    <div class="col-md-6">
                        <input id="allowances" type="text" class="form-control" name="allowances" value="{{ old('allowances') }}"  autofocus>
                        <span class="text-danger error-text allowances_error"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="etf" class="col-md-4 col-form-label text-md-end">ETF / EPF</label>

                    <div class="col-md-6">
                        <input id="etf" type="text" class="form-control" name="etf" value="{{ old('etf') }}" placeholder="Enter percentage..."  autofocus>
                        <span class="text-danger error-text etf_error"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="amount" class="col-md-4 col-form-label text-md-end">Total</label>

                    <div class="col-md-6">
                        <input id="amount" type="text" class="form-control" name="amount" value=""  readonly>
                        <span class="text-danger error-text amount_error"></span>
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="button" class="btn btn-success" id="total">
                            Calculate
                        </button>
                    </div>
                    <div class="col-md-3 offset-md-4 mt-3">
                        <button type="submit" class="btn btn-primary" id="save">
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
            $('#save').hide();
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

            //inputs change
            $('#total').on('click', function(e) {
                $('#salary_store').find('span.error-text').text('');
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
                    $('#salary_store').find('span.'+basic+'_error').text('Please enter basic amount');
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
                        $('#salary_store').find('span.'+basic+'_error').text('Please enter integer');
                        status =false;
                    }
                }

                //check allowances inputs
                if (!$('#allowances').val()) {
                    $('#allowances').addClass("is-invalid");
                    var basic = 'allowances'
                    $('#salary_store').find('span.'+basic+'_error').text('Please enter allowance amount');
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
                        $('#salary_store').find('span.'+basic+'_error').text('Please enter integer');
                        status = false
                    }
                }

                //check etf value empty and integer
                if (!$('#etf').val()) {
                    $('#etf').addClass("is-invalid");
                    var basic = 'etf'
                    $('#salary_store').find('span.'+basic+'_error').text('Please enter ETF/EPF amount');
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
                        $('#salary_store').find('span.'+basic+'_error').text('Please enter integer');
                        status = false;
                    }
                }

                if(status == true){
                    var calEtf = (basicval* etfVal / 100);
                    var setBasic = basicval-calEtf;
                    var total = setBasic + allowanceVal;
                    $('#amount').val(total)
                    $('#save').show();

                }

            });


        });


        //store details
        $('#salary_store').on('submit',function(e){
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
                        // $('#job').empty();
                        // alert(data.msg);
                        toastr.success(data.msg);
                        window.location.href = "/salary/index";

                    }
                }

            })
        });
    </script>
@endsection
