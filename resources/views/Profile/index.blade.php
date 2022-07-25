@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        @if($empData)
            <h1 class="h3 mb-0 text-gray-800">Profile </h1>
        @else
            <h1 class="h3 mb-0 text-gray-800">Admin Profile </h1>
        @endif


    </div>

    <div class="card">
        <div class="card-header">Update your profile</div>

        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}" id="emp_up">
                @csrf

                @if($empData)
                <div class="row mb-3">
                    <label for="username" class="col-md-4 col-form-label text-md-end">Username</label>

                    <div class="col-md-6">
                        <input id="username" type="text" class="form-control" name="username" value="{{$empData->username}}"  autocomplete="username" autofocus>
                        <span class="text-danger error-text username_error"></span>
                    </div>
                </div>
                @endif
                <div class="row mb-3">
                    <label for="first_name" class="col-md-4 col-form-label text-md-end">First Name</label>
                    <div class="col-md-6">
                        <input id="first_name" type="text" class="form-control" name="first_name" value="{{$userData->first_name}}" autocomplete="first_name" autofocus>
                        <span class="text-danger error-text first_name_error"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="last_name" class="col-md-4 col-form-label text-md-end">Last Name</label>
                    <div class="col-md-6">
                        <input id="last_name" type="text" class="form-control" name="last_name" value="{{$userData->last_name}}" autocomplete="last_name" autofocus>
                        <span class="text-danger error-text last_name_error"></span>
                    </div>
                </div>

                @if($empData)
                <div class="row mb-3">
                    <label for="address" class="col-md-4 col-form-label text-md-end">Address</label>
                    <div class="col-md-6">
                        <input id="address" type="text" class="form-control" name="address" value="{{$empData->address}}"  autofocus>
                        <span class="text-danger error-text address_error"></span>
                    </div>
                </div>
                @endif


                @if($empData)
                <div class="row mb-3">
                    <label for="birth" class="col-md-4 col-form-label text-md-end">Date of Birth</label>

                    <div class="col-md-6">
                        <input id="birth" type="date" class="form-control" name="birthdate" value="{{$empData->birth_date}}"  autocomplete="email">
                        <span class="text-danger error-text birthdate_error"></span>
                    </div>
                </div>
                @endif


                <div class="row mb-3">
                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="email" value="{{$userData->email}}"  autocomplete="email">
                        <span class="text-danger error-text email_error"></span>
                    </div>
                </div>


                <div class="row mb-3">
                    <label for="email" class="col-md-4 col-form-label text-md-end"></label>
                    <div class="col-md-6">
                        <div class="custom-control custom-checkbox small">
                            <input type="checkbox" class="custom-control-input"  name="new_psw" id="new_psw">
                            <label class="custom-control-label" for="new_psw">Create New Password?</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label id="lbl_psw" class="col-md-4 col-form-label text-md-end">New Password</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control" name="password" value=""  autocomplete="email">
                        <span class="text-danger error-text password_error"></span>
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


        //check box value
        $(document).ready(function(){
            $("#password").hide();
            $("#lbl_psw").hide();

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


        //store details
        $('#emp_up').on('submit',function(e){
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
                        //   window.location.href = "/pooja";

                    }
                }

            })
        });
    </script>
@endsection
