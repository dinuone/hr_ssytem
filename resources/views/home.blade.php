@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->


        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
{{--            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i--}}
{{--                    class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>--}}
        </div>

        <!-- Content Row -->
    @if(auth()->user()->is_admin ==1)
        <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Employees</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$employees}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Department</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$departments}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-building fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Jobs</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$jobs}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Leaves Request</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$leaves}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-paperclip fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
{{-- user--}}
        <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Pending Approve</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$pending}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Reject Request</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$reject}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-building fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Approved Leaves</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$approve}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Available Leaves</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$avb}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-paperclip fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
        <!-- Content Row -->




    <!-- /.container-fluid -->


@endsection
