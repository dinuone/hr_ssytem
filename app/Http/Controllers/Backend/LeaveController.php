<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;
use DataTables;

class LeaveController extends Controller
{
    public function index()
    {
        return view('leave.index');
    }

    public function create()
    {
        $user = auth()->user()->id;
        $emp = Employee::where('user_id',$user)->first();

        $department = Department::find($emp->department_id);

        return view('leave.create',compact('department','emp'));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'reason'=>'required',
            'leave_date'=>'required'
        ]);

        if(!$validator->passes()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
            $leave = new Leave();
            $leave->reason = $request->reason;
            $leave->request_date = $request->leave_date;
            $leave->emp_id = $request->emp_id;
            $leave->status = 2;
            $query = $leave->save();

            if(!$query){
                return response()->json(['code'=>0,'msg'=>'something wen wrong!']);
            }else{
                return response()->json(['code'=>1,'msg'=>'Request Sent!']);
            }
        }
    }

    public function show()
    {
        if(request()->ajax()) {
            if(auth()->user()->is_admin == 1){
                $model = Leave::with('employee');
                return DataTables::eloquent($model)
                    ->addColumn('employee',function(Leave $leave){
                        $name = $leave->employee->user->first_name;
                        return $name;
                    })

                    ->addColumn('department',function(Leave $leave){
                        return $leave->employee->department->name;
                    })

                    ->editColumn('status', function ($leave) {
                        if ($leave->status == 0) return 'Reject';
                        if ($leave->status == 1) return 'Approved';
                        if ($leave->status == 2) return 'Pending';
                    })


                    ->editColumn('created_at', function ($leave) {
                        return $leave->created_at ? with(new Carbon($leave->created_at))->format('m/d/Y') : '';
                    })

                    ->addColumn('actions',function($row){
                        $user='<div class="btn-group">
                                    <button class="btn btn-danger" data-id="'.$row['id'].'" id="deleteJob">Delete</button>
                            </div>';

                        $admin = '<div class="btn-group">
                                    <button class="btn btn-primary" data-id="'.$row['id'].'" id="editLeave">Check</button>
                                    <button class="btn btn-danger" data-id="'.$row['id'].'" id="deleteLeave">Delete</button>
                            </div>';

                        if(auth()->user()->is_admin == 1){
                            return $admin;
                        }else{
                            return $user;
                        }
                    })
                    ->rawColumns(['actions'])
                    ->addIndexColumn()
                    ->make(true);
            }else{

                $data = User::find(auth()->user()->id);
                $emp = Employee::where('user_id',$data->id)->first();

                $model = Leave::where('emp_id',$emp->id)->with('employee');
                return DataTables::eloquent($model)
                    ->addColumn('employee',function(Leave $leave){
                        $name = $leave->employee->user->first_name;
                        return $name;
                    })

                    ->addColumn('department',function(Leave $leave){
                        return $leave->employee->department->name;
                    })

                    ->editColumn('status', function ($leave) {
                        if ($leave->status == 0) return 'Reject';
                        if ($leave->status == 1) return 'Approved';
                        if ($leave->status == 2) return 'Pending';
                    })


                    ->editColumn('created_at', function ($leave) {
                        return $leave->created_at ? with(new Carbon($leave->created_at))->format('m/d/Y') : '';
                    })

                    ->addColumn('actions',function($row){
                        $user='<div class="btn-group">
                                    <button class="btn btn-danger" data-id="'.$row['id'].'" id="deleteLeave">Delete</button>
                            </div>';

                        $admin = '<div class="btn-group">
                                    <button class="btn btn-primary" data-id="'.$row['id'].'" id="editLeave">Check</button>
                                    <button class="btn btn-danger" data-id="'.$row['id'].'" id="deleteLeave">Delete</button>
                            </div>';

                        if(auth()->user()->is_admin == 1){
                            return $admin;
                        }else{
                            return $user;
                        }
                    })
                    ->rawColumns(['actions'])
                    ->addIndexColumn()
                    ->make(true);
            }

        }
    }

    public function getLeave(Request $request)
    {
        $leave_id = $request->leave_id;
        $Details = Leave::where('id',$leave_id)->first();
        return response()->json(['details'=>$Details,]);
    }

    public function update(Request $request)
    {
        $leave_id = $request->l_id;

        $validator = \Validator::make($request->all(),[
            'status'=>'required'
        ]);

        if(!$validator->passes()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
            $lv = Leave::find($leave_id);
            $emp_id = $lv->emp_id;

            //get department
            $emp_data = Employee::find($emp_id);
            $department_id = $emp_data->department_id;

            //check task due
            $task_data = Task::where('department_id',$department_id)->first();

            $date = Carbon::Today()->format('Y-m-d');
            if(!empty($task_data)){
                if($task_data->deadline < $date ){

                    if($task_data->is_complete == 1){
                        //complete
                        $lv->status = $request->status;
                        $avb = $emp_data->avb_leave;
                        Employee::find($emp_id)->update([
                            'avb_leave'=>$avb-1
                        ]);
                        $query = $lv->save();
                        return response()->json(['code'=>1,'msg'=>'Successfully Updated!']);
                    }else{
                        //not complete
                        $lv->status = 0;
                        $query = $lv->save();
                        return response()->json(['code'=>2,'msg'=>'Assinged task are not completed Request Rejected!']);
                    }

                }else{
                    //pending
                    $lv->status = $request->status;

                    $avb = $emp_data->avb_leave;
                    Employee::find($emp_id)->update([
                        'avb_leave'=>$avb-1
                    ]);
                    $query = $lv->save();
                    return response()->json(['code'=>1,'msg'=>'Successfully Updated!']);
                }
            }else{
                $lv->status = $request->status;
                $avb = $emp_data->avb_leave;
                Employee::find($emp_id)->update([
                    'avb_leave'=>$avb-1
                ]);
                $query = $lv->save();
                return response()->json(['code'=>1,'msg'=>'Successfully Updated!']);
            }




        }
    }

    public function delete(Request $request)
    {
        $leave_id = $request->l_id;
        $query = Leave::find($leave_id)->delete();

        if($query){
            return response()->json(['code'=>1 ,'msg'=>'Leave request has been deleted from Database']);
        }else{
            return response()->json(['code'=>1,'msg'=>'Something went wrong']);
        }
    }

}
