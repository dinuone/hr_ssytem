<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Empsalary;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmpSalaryReportController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('Reports.EmpSalary.index',compact('departments'));
    }

    public function generate(Request $request)
    {
        $job = $request->job_id;
        $dep = $request->dep_id;
        $month = $request->month;

        $dateMonthArray = explode('/', $month);
        $month = $dateMonthArray[0];
        $year = $dateMonthArray[1];
        $date = Carbon::createFromDate($year, $month, );

        $data = Empsalary::where('dep_id',$dep)->where('job_id',$job)->whereMonth('month',$date )->with('user','employee','department','job','package')->get();

        return response()->json(['details'=>$data]);
    }


}
