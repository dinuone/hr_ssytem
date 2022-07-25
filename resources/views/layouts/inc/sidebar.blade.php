 <!-- Sidebar -->
 <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon rotate-n-15">

        </div>
        <div class="sidebar-brand-text mx-3">HR system</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item  {{ request()->is('home*') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('home')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    @if (auth()->user()->is_admin == 1)
    <li class="nav-item {{ request()->is('employee*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('employee.index') }}">
            <i class="fas fa-fw fa-user-circle"></i>
            <span>Employee Management</span></a>
    </li>
    @endif





    @if (auth()->user()->is_admin == 1)
    <li class="nav-item  {{ request()->is('department') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('department.index') }}">
            <i class="fas fa-fw fa-building"></i>
            <span>Department Management</span></a>
    </li>
    <li class="nav-item {{ request()->is('job*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('jobs.index') }}">
            <i class="fas fa-fw fa-user-check"></i>
            <span>Job Management</span></a>
    </li>
    <li class="nav-item {{ request()->is('task*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('task.index') }}">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Task Management</span></a>
    </li>

    <li class="nav-item {{ request()->is('salary*') ? 'active' : '' }}">
         <a class="nav-link" href="{{ route('salary.index') }}">
             <i class="fas fa-fw fa-box"></i>
          <span>Salary Packages</span></a>
     </li>

     <li class="nav-item {{ request()->is('emp/salary*') ? 'active' : '' }}">
         <a class="nav-link" href="{{ route('emp-salary.index') }}">
             <i class="fas fa-fw fa-dollar-sign"></i>
             <span>Employees Salary</span></a>
     </li>


    @endif

    @if (auth()->user()->is_admin == 1 )
    <li class="nav-item {{ request()->is('leave*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('leave.index') }}">
            <i class="fas fa-fw fa-briefcase"></i>
            <span>Leaves Management</span></a>
    </li>

         <li class="nav-item" {{ request()->is('reports') ? 'active' : '' }}>
             <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                aria-expanded="true" aria-controls="collapsePages">
                 <i class="fas fa-fw fa-file-archive"></i>
                 <span>Reports</span>
             </a>
             <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                 <div class="bg-white py-2 collapse-inner rounded">
                     <h6 class="collapse-header">Employee Data:</h6>
                     <a class="collapse-item {{ request()->is('reports/emp-detail-report') ? 'active' : '' }}" href="{{route('EmpDetailReport.index')}}">Detail Report</a>

                     <div class="collapse-divider"></div>
                     <h6 class="collapse-header">Salary Data:</h6>
                     <a class="collapse-item {{ request()->is('reports/emp-salary-report') ? 'active' : '' }}" href="{{route('EmpSalaryReport.index')}}">Salary Report</a>

                 </div>
             </div>
         </li>

    @else
    <li class="nav-item {{ request()->is('leave*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('leave.index') }}">
            <i class="fas fa-fw fa-arrow-alt-circle-right"></i>
            <span>Leave Request</span></a>
    </li>

   <li class="nav-item {{ request()->is('emp') ? 'active' : '' }}">
       <a class="nav-link" href="{{ route('emp-salary.index') }}">
           <i class="fas fa-fw fa-dollar-sign"></i>
           <span>Salary</span></a>
    </li>
    @endif


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>



</ul>
<!-- End of Sidebar -->
