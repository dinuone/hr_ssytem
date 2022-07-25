<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Job;
use App\Models\Leave;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(auth()->user()->is_admin ==1){
            $employees = Employee::count();
            $leaves = Leave::count();
            $departments = Department::count();
            $jobs = Job::count();
            return view('home',compact('employees','leaves','departments','jobs'));
        }else{
            $data = Employee::where('user_id',auth()->user()->id)->first();
            $approve =  Leave::where('status',1)->where('emp_id',$data->id)->count();
            $pending = Leave::where('status',2)->where('emp_id',$data->id)->count();
            $reject = Leave::where('status',0)->where('emp_id',$data->id)->count();
            $avb = $data->avb_leave;
            return view('home',compact('approve','pending','reject','avb'));
        }





    }
}
