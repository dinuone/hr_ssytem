<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Job;
use App\Models\SalaryPkg;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index()
    {
        $pkgs = SalaryPkg::all();
        return view('salary.index',compact('pkgs'));
    }

    public function create()
    {
        $jobs = Job::all();
        $departments = Department::all();
        return view('salary.create',compact('departments','jobs'));
    }

    public function store(Request $request)
    {

        $validator = \Validator::make($request->all(),[
            'department'=>'required',
            'job'=>'required',
            'basic'=>'required|integer',
            'etf'=>'required|integer',
            'allowances'=>'required|integer'
        ]);

        if(!$validator->passes()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
            $job_id = $request->job;
            $SalaryData = SalaryPkg::where('job_id','=',$job_id)->get();

            if(!empty($SalaryData)){
                foreach ($SalaryData as $data){
                    if($data->job_id == $job_id){
                        return response()->json(['code'=>2,'msg'=>'Salary package already added into this job']);
                    }
                }

                $pkg =  new SalaryPkg();
                $pkg->job_id = $job_id;
                $pkg->department_id = $request->department;
                $pkg->basic = $request->basic;
                $pkg->allowances = $request->allowances;
                $pkg->epf_etf = $request->etf;
                $pkg->amount = $request->amount;
                $save = $pkg->save();

                if(!$save){
                    return response()->json(['code'=>0,'msg'=>'something wen wrong!']);
                }else{
                    return response()->json(['code'=>1,'msg'=>'Successfully Added!']);
                }
            }


        }
    }

    public function edit(Request $request)
    {
        $pkg_id = $request->pkg_id;
        $pkgDetails = SalaryPkg::where('id',$pkg_id)->with('department','job')->first();
        $depts = Department::all();
        $jobs = Job::all();
        return response()->json(['details'=>$pkgDetails ,'depts'=>$depts,'jobs'=>$jobs]);
    }

    public function update(Request $request)
    {
        $pkg_id = $request->p_id;

        $validator = \Validator::make($request->all(),[
            'department'=>'required',
            'job'=>'required',
            'basic'=>'required|integer',
            'etf'=>'required|integer',
            'allowances'=>'required|integer'
        ]);

        if(!$validator->passes()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
            $pkg = SalaryPkg::find($pkg_id);
            $pkg->department_id = $request->department;
            $pkg->basic = $request->basic;
            $pkg->allowances = $request->allowances;
            $pkg->epf_etf = $request->etf;
            $pkg->amount = $request->amount;
            $query = $pkg->save();

            if(!$query){
                return response()->json(['code'=>0,'msg'=>'something wen wrong!']);
            }else{
                return response()->json(['code'=>1,'msg'=>'Successfully Updated!']);
            }
        }
    }

    public function delete(Request $request)
    {
        $pkg_id = $request->p_id;
        $query = SalaryPkg::find($pkg_id)->delete();

        if($query){
            return response()->json(['code'=>1 ,'msg'=>'Job has been deleted from Database']);
        }else{
            return response()->json(['code'=>1,'msg'=>'Something went wrong']);
        }
    }

    public function getJob(Request $request)
    {
        $department_id = $request->dep_id;
        $details = Department::where('id',$department_id)->with('jobs')->first();
        return response()->json(['details'=>$details]);
    }
}
