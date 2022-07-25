<?php

use App\Http\Controllers\Backend\DepartmentController;
use App\Http\Controllers\Backend\EmployeeController;
use App\Http\Controllers\Backend\JobController;
use App\Http\Controllers\Backend\TaskController;
use App\Http\Controllers\Backend\LeaveController;
use App\Http\Controllers\Backend\SalaryController;
use App\Http\Controllers\Backend\EmpSalaryController;
use App\Http\Controllers\Backend\EmpDetailReportController;
use App\Http\Controllers\Backend\EmpSalaryReportController;
use App\Http\Controllers\Backend\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware'=>'auth'],function(){
    Route::group(['middleware'=>'is_admin'], function(){

        //employee
        Route::get('employee',[EmployeeController::class,'index'])->name('employee.index');
        Route::get('employee/create',[EmployeeController::class,'create'])->name('employee.create');
        Route::post('employee/store',[EmployeeController::class,'store'])->name('employee.store');
        Route::get('employee/all',[EmployeeController::class,'show'])->name('employee.show');
        Route::post('employee/edit',[EmployeeController::class,'edit'])->name('employee.edit');
        Route::post('employee/update',[EmployeeController::class,'update'])->name('employee.update');
        Route::post('employee/delete', [EmployeeController::class, 'delete'])->name('employee.delete');
        Route::post('employee/get-dep',[EmployeeController::class,'getJob'])->name('employee.get-dep');
        Route::get('employee/send-mail',[EmployeeController::class,'sendMail'])->name('employee.sendmail');

        //job
        Route::get('job/index',[JobController::class,'index'])->name('jobs.index');
        Route::get('job/create',[JobController::class,'create'])->name('jobs.create');
        Route::post('job/store',[JobController::class,'store'])->name('jobs.store');
        Route::get('job/all',[JobController::class,'show'])->name('jobs.show');
        Route::post('job/select',[JobController::class,'getJob'])->name('jobs.select');
        Route::post('job/update',[JobController::class,'update'])->name('jobs.update');
        Route::post('job/delete', [JobController::class, 'delete'])->name('jobs.delete');

        //department
        Route::get('department/index',[DepartmentController::class,'index'])->name('department.index');
        Route::get('department/create',[DepartmentController::class,'create'])->name('department.create');
        Route::post('department/store',[DepartmentController::class,'store'])->name('department.store');
        Route::get('department/all',[DepartmentController::class,'show'])->name('department.show');
        Route::post('department/select',[DepartmentController::class,'getDepartment'])->name('department.select');
        Route::post('department/update',[DepartmentController::class,'update'])->name('department.update');
        Route::post('department/delete', [DepartmentController::class, 'delete'])->name('department.delete');

        //task
        Route::get('task/index',[TaskController::class,'index'])->name('task.index');
        Route::get('task/create',[TaskController::class,'create'])->name('task.create');
        Route::post('task/store',[TaskController::class,'store'])->name('task.store');
        Route::get('task/all',[TaskController::class,'show'])->name('task.show');
        Route::post('task/select',[TaskController::class,'getTask'])->name('task.select');
        Route::post('task/update',[TaskController::class,'updateTask'])->name('task.update');
        Route::post('task/delete', [TaskController::class, 'delete'])->name('task.delete');

        //leave
        Route::get('leave/index',[LeaveController::class,'index'])->name('leave.index');
        Route::get('leave/create',[LeaveController::class,'create'])->name('leave.create');
        Route::get('leave/all',[LeaveController::class,'show'])->name('leave.show');
        Route::post('leave/select',[LeaveController::class,'getLeave'])->name('leave.select');
        Route::post('leave/approve',[LeaveController::class,'update'])->name('leave.approve');
        Route::post('leave/delete', [LeaveController::class, 'delete'])->name('leave.delete');

        //salary
        Route::get('salary/index',[SalaryController::class,'index'])->name('salary.index');
        Route::get('salary/create',[SalaryController::class,'create'])->name('salary.create');
        Route::post('salary/store',[SalaryController::class,'store'])->name('salary.store');
        Route::post('salary/update',[SalaryController::class,'update'])->name('salary.update');
        Route::post('salary/delete', [SalaryController::class, 'delete'])->name('salary.delete');
        Route::post('salary/get-job',[SalaryController::class,'getJob'])->name('salary.get-job');
        Route::post('salarye/edit',[SalaryController::class,'edit'])->name('salary.edit');

        //emp - salary
        Route::get('emp/salary/index',[EmpSalaryController::class,'index'])->name('emp-salary.index');
        Route::get('emp/salary/create',[EmpSalaryController::class,'create'])->name('emp-salary.create');
        Route::post('emp/salary/store',[EmpSalaryController::class,'store'])->name('emp-salary.store');
        Route::post('emp/salary/update',[EmpSalaryController::class,'update'])->name('emp-salary.update');
        Route::post('emp/salary/delete', [EmpSalaryController::class, 'delete'])->name('emp-salary.delete');
        Route::post('emp/salary/get-emp',[EmpSalaryController::class,'getEmp'])->name('get-emp');
        Route::post('emp/salary/get-job',[EmpSalaryController::class,'getJob'])->name('get-job');
        Route::post('emp/salary/get-amount',[EmpSalaryController::class,'getAmount'])->name('get-amount');
        Route::post('emp/salary/edit',[EmpSalaryController::class,'edit'])->name('emp-salary.edit');

        //reports
        Route::get('/reports/emp-detail-report',[EmpDetailReportController::class,'index'])->name('EmpDetailReport.index');
        Route::post('/emp-details-generate',[EmpDetailReportController::class,'generate'])->name('EmpDetailReport.generate');

        //report-empSalary
        Route::get('/reports/emp-salary-report',[EmpSalaryReportController::class,'index'])->name('EmpSalaryReport.index');
        Route::post('/emp-salary-generate',[EmpSalaryReportController::class,'generate'])->name('EmpSalaryReport.generate');

        //Profile
        Route::get('/profile',[ProfileController::class,'index'])->name('profile.index');
        Route::post('/profile/update',[ProfileController::class,'update'])->name('profile.update');

    });

    Route::get('leave/index',[LeaveController::class,'index'])->name('leave.index');
    Route::get('leave/create',[LeaveController::class,'create'])->name('leave.create');
    Route::post('leave/store',[LeaveController::class,'store'])->name('leave.store');
    Route::get('leave/all',[LeaveController::class,'show'])->name('leave.show');
    Route::get('emp/salary/index',[EmpSalaryController::class,'index'])->name('emp-salary.index');
    //Profile
    Route::get('/profile',[ProfileController::class,'index'])->name('profile.index');
    Route::post('/profile/update',[ProfileController::class,'update'])->name('profile.update');
});
