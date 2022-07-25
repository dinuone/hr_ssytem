@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Employee Salary</h1>
        @if(auth()->user()->is_admin == 1)
            <a href="{{ route('emp-salary.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-arrow-alt-circle-right fa-sm text-white-50"></i> Send Salary</a>
        @endif

    </div>

    <div class="card">
        <div class="card-header">
            Employee Salary List
        </div>
        <div class="card-body">
            <table class="table" id="empSal_table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Section</th>
                    <th scope="col">Job</th>
                    <th scope="col">Basic</th>
                    <th scope="col">Allowance</th>
                    <th scope="col">EPF/ETF</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Month</th>
                    <th scope="col">Status</th>
                    <th scope="col">Created At</th>
                    @if(auth()->user()->is_admin == 1)
                        <th scope="col">Handle</th>
                    @endif

                </tr>
                </thead>
                <tbody>
                @foreach($empsalaries as $empData)
                    <tr>
                        <td>{{$empData->id}}</td>
                        <td>{{$empData->employee->user->first_name}}</td>
                        <td>{{$empData->employee->user->last_name}}</td>
                        <td>{{$empData->department->name}}</td>
                        <td>{{$empData->job->name}}</td>
                        <td>{{$empData->package->basic}}</td>
                        <td>{{$empData->package->allowances}}</td>
                        <td>{{$empData->package->epf_etf}}%</td>
                        <td>{{$empData->amount}}</td>
                        <td>{{ \Carbon\Carbon::parse($empData->month)->format('F')}}</td>
                        <td>@if($empData->status == 1)
                                <span class="badge badge-success">Sent</span>
                            @else
                                <span class="badge badge-danger">Not Sent</span>
                            @endif
                        </td>
                        <td>{{$empData->created_at->toDatestring()}}</td>
                        @if(auth()->user()->is_admin == 1)
                            <td>
                                <button class="btn btn-danger" id="deleteSal" data-id="{{$empData->id}}">Delete</button>
                            </td>
                        @endif
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
            $('#empSal_table').DataTable( {
                dom: 'Bfrtip',
                buttons: [

                    {
                        extend: 'excelHtml5',
                        title: 'Employee Salary Report',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7,8,9,10 ]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Employee Salary Report',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7,8,9,10 ]
                        },
                        pageSize: 'Legal'
                    },
                    'colvis'
                ]
            } );
        } );



        //delete
        $(document).on('click','#deleteSal' , function(){
            var s_id = $(this).data('id');
            var url = '<?= route("emp-salary.delete")?>';

            swal.fire({
                title:'Are you sure?',
                html:'you wont to <b>delete</b> this employee salary record?',
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
                    $.post(url,{s_id:s_id}, function(data){
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





    </script>

@endsection
