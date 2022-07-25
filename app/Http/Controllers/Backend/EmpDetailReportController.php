<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmpDetailReportController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('Reports.EmpDetails.index',compact('departments',));
    }

    public function generate(Request $request)
    {
        $job = $request->job_id;
        $dep = $request->dep_id;

        $data = Employee::where('department_id',$dep)->where('job_id',$job)->with('user','department','job')->get();

        return response()->json(['details'=>$data]);
    }
}
