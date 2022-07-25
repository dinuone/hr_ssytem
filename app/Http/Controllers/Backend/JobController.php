<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Department;
use DataTables;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::with('department')->get();
        return view('job.index',compact('jobs'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('job.create',compact('departments'));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'name'=>'required',
            'department'=>'required'
        ]);

        if(!$validator->passes()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
            $job = new Job();
            $job->name = $request->name;
            $job->department_id = $request->department;
            $query = $job->save();

            if(!$query){
                return response()->json(['code'=>0,'msg'=>'something wen wrong!']);
            }else{
                return response()->json(['code'=>1,'msg'=>'Successfully Added!']);
            }
        }
    }


    public function getJob(Request $request)
    {
        $job_id = $request->job_id;
        $jobDetails = Job::where('id',$job_id)->with('department')->first();
        $depts = Department::all();
        return response()->json(['details'=>$jobDetails,'depts'=>$depts]);
    }

    public function update(Request $request)
    {
        $job_id = $request->j_id;

        $validator = \Validator::make($request->all(),[
            'name'=>'required',
            'department'=>'required'
        ]);

        if(!$validator->passes()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
            $job = Job::find($job_id);
            $job->name = $request->name;
            $job->department_id = $request->department;
            $query = $job->save();

            if(!$query){
                return response()->json(['code'=>0,'msg'=>'something wen wrong!']);
            }else{
                return response()->json(['code'=>1,'msg'=>'Successfully Updated!']);
            }
        }
    }

    public function delete(Request $request)
    {
        $job_id = $request->j_id;
        $query = Job::find($job_id)->delete();

        if($query){
            return response()->json(['code'=>1 ,'msg'=>'Job has been deleted from Database']);
        }else{
            return response()->json(['code'=>1,'msg'=>'Something went wrong']);
        }
    }
}
