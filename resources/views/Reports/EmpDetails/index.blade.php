@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Employees Detail Report</h1>

    </div>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-4">
                    <label for="dep" class="col-md-4 col-form-label text-md-end">Department</label>
                    <select class="form-control" id="department" name="department">
                        <option selected>--Select--</option>
                            @foreach ($departments as $department)
                               <option value="{{ $department->id }}">{{$department->name}}</option>
                            @endforeach
                    </select>
                </div>

                <div class="col-lg-3">
                    <label for="job" class="col-md-4 col-form-label text-md-end">Job</label>
                    <select class="form-control" id="job" name="job">
                        <option selected>--Select--</option>
                    </select>
                </div>

                <div class="col-lg-1">
                    <div class="mt-3">
                        <button class="btn btn-info mt-3"  type="button" id="gen">Generate</button>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-secondary mt-3"  type="button" id="refresh">Clear</button>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-3">
                    <button class="btn btn-primary mt-3" id="excel"> <span><i class="fa fa-download"></i></span> Download Excel Report</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table  class="table table-bordered" id="emp_table" width="100%" cellspacing="0">
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
                </tr>
                </thead>
                <tbody class="tbody">
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

        $(function() {
            // $('#MonthFormat').MonthPicker();
            $("#MonthFormat").MonthPicker({
                Button: '<button class="btn btn-sm btn-success mt-3" type="button">Select Month</button>'
            });

            $('#refresh').hide();
            $('#excel').hide();


        });
        // $(function(){
        //     $("#password").hide();
        //     $("#lbl_psw").hide();
        //
        // });

        // $(document).ready(function() {
        //     $('#emp_table').DataTable( {
        //         dom: 'Bfrtip',
        //         buttons: [
        //
        //             {
        //                 extend: 'excelHtml5',
        //                 title: 'Employee Report',
        //                 exportOptions: {
        //                     columns: [ 0, 1, 2,3,4,5,6,7,8,9,10 ]
        //                 }
        //             },
        //             {
        //                 extend: 'pdfHtml5',
        //                 title: 'Employee Report',
        //                 exportOptions: {
        //                     columns: [ 0, 1, 2,3,4,5,6,7,8,9,10 ]
        //                 },
        //                 pageSize: 'Legal'
        //             },
        //             'colvis'
        //         ]
        //     } );
        // } );

        $('#refresh').on('click',function (e){
            window.location.reload();
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

        //store details
        $('#gen').on('click',function(e) {
            var dep_id = $('#department').val();
            var job_id = $('#job').val();
            $.ajax({
                url: "{{ route('EmpDetailReport.generate') }}",
                type: "POST",
                data: {
                    dep_id: dep_id,
                    job_id :job_id,
                },
                success: function(data) {
                    console.log(data);

                    var FinalResult=data.details
                    num_rows = data.length;

                    $.each(FinalResult, function(index, value){
                        $('tbody').append(
                            '<tr>' +
                            '<td>'+value.id+ '</td>' +
                            '<td>'+value.user.first_name+'</td>' +
                            '<td>'+value.user.last_name+'</td>' +
                            '<td>'+value.nic+'</td>' +
                            '<td>'+value.address+'</td>' +
                            '<td>'+value.username+'</td>' +
                            '<td>'+value.user.email+'</td>' +
                            '<td>'+value.department.name+'</td>' +
                            '<td>'+value.job.name+'</td>' +
                            '<td>'+value.avb_leave+'</td>' +
                            '<td>'+value.birth_date+'</td>' +
                            '<td>'+value.date_hired+'</td>' +
                            '</tr>');
                    });

                    $('#gen').hide();
                    $('#refresh').show();
                    $('#excel').show();

                }
            })


        })

        $('#excel').on('click',function (e){
            $("#emp_table").tableHTMLExport({

                // csv, txt, json, pdf
                type:'csv',

                // default file name
                filename: 'Employee_Details.csv',

                // for csv
                separator: ',',
                newline: '\r\n',
                trimContent: true,
                quoteFields: true,

                // CSS selector(s)
                ignoreColumns: '',
                ignoreRows: '',

                // your html table has html content?
                htmlContent: false,

                // debug
                consoleLog: false,



            });
        });
    </script>

@endsection
