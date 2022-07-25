<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Empsalary;
use App\Models\SalaryPkg;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmpSalaryController extends Controller
{
    public function index()
    {
        if(auth()->user()->is_admin == 1){
            $empsalaries = Empsalary::with('department','job','package','employee')->get();
            return view('emp-salary.index',compact('empsalaries'));
        }else{
            $id = auth()->user()->id;
            $empdata =Employee::where('user_id',$id)->first();
            $empsalaries = Empsalary::with('department','job','package','employee')->where('emp_id',$empdata->id)->get();
            return view('emp-salary.index',compact('empsalaries'));
        }

    }

    public function create()
    {
        $departments = Department::all();
        return view('emp-salary.create',compact('departments'));
    }

    public function getEmp(Request $request)
    {
        $reqdata = $request->emp_id;
        $data = Employee::where('id',$reqdata)->orwhere('nic',$reqdata)->with('user','department','job')->first();
        return response()->json(['details'=>$data]);
    }

    public function getJob(Request $request)
    {
        $department_id = $request->dep_id;
        $details = Department::where('id',$department_id)->with('jobs')->first();
        return response()->json(['details'=>$details]);
    }

    public function getAmount(Request $request)
    {
        $job_id = $request->job_id;
        $details = SalaryPkg::where('job_id',$job_id)->first();
        return response()->json(['details'=>$details]);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'department'=>'required',
            'job'=>'required',
            'MonthFormat'=>'required',
            'amount'=>'required',
        ]);

        if(!$validator->passes()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
            if($request->emptype == 1){
                //send all

                $dateMonthArray = explode('/', $request->MonthFormat);
                $month = $dateMonthArray[0];
                $year = $dateMonthArray[1];

                $empSalData = Empsalary::whereMonth('month',$month)->whereYear('month',$year)->where('job_id',$request->job)->first();

                if($empSalData != null){
                    return response()->json(['code'=>2,'msg'=>'Salary already sent to the selected department!']);
                }else{
                    $date = Carbon::createFromDate($year, $month, );

                    $emp = Employee::all();
                    foreach ($emp as $data){
                        $userdata = User::find($data->user_id);

                        $empSal =  new Empsalary();
                        $empSal->emp_id = $data->id;
                        $empSal->dep_id = $request->department;
                        $empSal->job_id = $request->job;
                        $empSal->amount = $request->amount;
                        $empSal->pkg_id = $request->pkg_id;
                        $empSal->user_id = $userdata->id;
                        $empSal->month = $date;
                        $empSal->status = 1;
                        $empSal->save();
                    }
                    return response()->json(['code'=>1,'msg'=>'Successfully Added!']);
                }


            //indv sent salary
            }else{


                $dateMonthArray = explode('/', $request->MonthFormat);
                $month = $dateMonthArray[0];
                $year = $dateMonthArray[1];
                $date = Carbon::createFromDate($year, $month, );

                $emp = Employee::where('nic',$request->search)->first();
                $empSalData = Empsalary::whereMonth('month',$month)->whereYear('month',$year)->where('job_id',$emp->job_id)->first();


                if($empSalData != null){
                    return response()->json(['code'=>2,'msg'=>'Salary already sent to the employee!']);
                }else{
                    $emp = Employee::where('nic',$request->search)->first();
                    $usr = User::find($emp->user_id);

                    $empSal =  new Empsalary();
                    $empSal->emp_id = $emp->id;
                    $empSal->dep_id = $emp->department_id;
                    $empSal->job_id = $emp->job_id;
                    $empSal->amount = $request->amount;
                    $empSal->pkg_id = $request->pkg_id;
                    $empSal->month = $date;
                    $empSal->user_id = $usr->id;
                    $empSal->status = 1;
                    $empSal->save();

                    return response()->json(['code'=>1,'msg'=>'Successfully Added!']);
                }


            }



        }
    }

    public function delete(Request $request)
    {
        $empSal_id = $request->s_id;
        // $user_id = $request->user_id;

        $query = Empsalary::find($empSal_id)->delete();

        if($query){
            return response()->json(['code'=>1 ,'msg'=>'Employee has been deleted from Database']);
        }else{
            return response()->json(['code'=>1,'msg'=>'Something went wrong']);
        }
    }
}
