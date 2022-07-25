<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Job;
use DataTables;
use Carbon\Carbon;
use Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\DepartmentChangeMail;

class EmployeeController extends Controller
{

    public function index()
    {
        $employees = Employee::with('user')->get();
        return view('employee.index',compact('employees'));
    }

    public function create()
    {
        $departments  = Department::all();
        $jobs = Job::all();
        return view('employee.create',compact('departments','jobs'));
    }

    public function getJob(Request $request)
    {
         $department_id = $request->dep_id;
         $details = Department::where('id',$department_id)->with('jobs')->first();
        return response()->json(['details'=>$details]);
    }

    public function show()
    {
        if(request()->ajax()) {
            $model = Employee::with('user');
            return DataTables::eloquent($model)
            ->addColumn('first_name',function(Employee $emp){
                return $emp->user->first_name;
            })
            ->addColumn('last_name',function(Employee $emp){
                return $emp->user->last_name;
            })
            ->addColumn('email',function(Employee $emp){
                return $emp->user->email;
            })
            ->addColumn('username',function(Employee $emp){
                return $emp->username;
            })

            ->addColumn('address',function(Employee $emp){
                return $emp->address;
            })

                ->addColumn('nic',function(Employee $emp){
                    return $emp->nic;
                })

            ->addColumn('date_hired',function(Employee $emp){
                return $emp->date_hired;
            })

            ->addColumn('birth_date',function(Employee $emp){
                return $emp->birth_date;
            })

            ->addColumn('job',function(Employee $emp){
                return $emp->job->name;
            })

            ->addColumn('department',function(Employee $emp){
                return $emp->department->name;
            })

            ->editColumn('created_at', function ($emp) {
                return $emp->created_at ? with(new Carbon($emp->created_at))->format('m/d/Y') : '';
                })

            ->addColumn('actions',function($row){
                    return '<div class="btn-group">
                                    <button class="btn btn-primary" data-id="'.$row['id'].'" id="editEmp">Update</button>
                                    <button class="btn btn-danger" data-id="'.$row['id'].'" id="deleteEmp">Delete</button>
                            </div>';
                    })
            ->rawColumns(['actions'])
            ->addIndexColumn()
            ->make(true);
        }
    }


    public function store(Request $request)
    {

        $validator = \Validator::make($request->all(),[
            'first_name'=>['required', 'string', 'max:255'],
            'last_name'=>['required', 'string', 'max:255'],
            'username'=>['required', 'string', 'max:255'],
            'address'=>['required', 'string', 'max:255'],
            'department'=>['required'],
            'job'=>['required'],
            'birthdate'=>['required'],
            'date_hired'=>['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'nic'=>['required']
        ]);

        if(!$validator->passes()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{

            $empdata = Employee::all();

            foreach ($empdata as $data){
                if($data->nic == $request->nic){
                    return response()->json(['code'=>2,'msg'=>'NIC already exists!']);
                }
            }
            $user = User::create([
                'first_name'=>$request['first_name'],
                'last_name'=>$request['last_name'],
                'email'=>$request['email'],
                'password'=>Hash::make($request['password'])
            ]);

            $employee = new Employee();
            $employee->user_id = $user->id;
            $employee->username = $request->username;
            $employee->address = $request->address;
            $employee->department_id = $request->department;
            $employee->job_id = $request->job;
            $employee->nic = $request->nic;
            $employee->birth_date =$request->birthdate;
            $employee->date_hired = $request->date_hired;
            $query = $employee->save();

            if(!$query){
                return response()->json(['code'=>0,'msg'=>'something wen wrong!']);
            }else{
                return response()->json(['code'=>1,'msg'=>'Successfully Added!']);
            }
        }
    }

    public function edit(Request $request)
    {
        $emp_id = $request->emp_id;
        $jobDetails = Employee::where('id',$emp_id)->with('user','department','job')->first();
        $depts = Department::all();
        return response()->json(['details'=>$jobDetails,'depts'=>$depts]);
    }

    public function update(Request $request)
    {
        $emp_id = $request->e_id;
        $user_id = $request->user_id;


        $validator = \Validator::make($request->all(),[
            'first_name'=>['required', 'string', 'max:255'],
            'last_name'=>['required', 'string', 'max:255'],
            'username'=>['required', 'string', 'max:255'],
            'address'=>['required', 'string', 'max:255'],
            'department'=>['required'],
            'nic'=>['required'],
            'job'=>['required'],
            'birthdate'=>['required'],
            'date_hired'=>['required'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);


        if(!$validator->passes()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{


            $employee = Employee::find($emp_id);
            $data = Department::find($employee->department_id);

            if($employee->department_id != $request->department){
                //new department

                $employee->username = $request->username;
                $employee->address = $request->address;
                $employee->department_id = $request->department;
                $employee->job_id = $request->job;
                $employee->nic = $request->nic;
                $employee->birth_date =$request->birthdate;
                $employee->date_hired = $request->date_hired;
                $employee->save();

                $user = User::find($user_id);
                $user->first_name =$request->first_name;
                $user->last_name = $request->last_name;
                $user->email = $request->email;

                $date = Carbon::today()->format('Y-m-d');

                $this->sendMail($user->email,$data->name,$user->first_name,$date);

                if($request->password){
                    $user->password = Hash::make($request->password);
                }else{
                    $user->password = $user->password;
                }

                $query = $user->save();

                if(!$query){
                    return response()->json(['code'=>0,'msg'=>'something wen wrong!']);
                }else{
                    return response()->json(['code'=>1,'msg'=>'Successfully Updated!']);
                }
            }else{
                $employee->username = $request->username;
                $employee->address = $request->address;
                $employee->department_id = $request->department;
                $employee->job_id = $request->job;
                $employee->nic = $request->nic;
                $employee->birth_date =$request->birthdate;
                $employee->date_hired = $request->date_hired;
                $employee->save();

                $user = User::find($user_id);
                $user->first_name =$request->first_name;
                $user->last_name = $request->last_name;
                $user->email = $request->email;

                if($request->password){
                    $user->password = Hash::make($request->password);
                }else{
                    $user->password = $user->password;
                }

                $query = $user->save();

                if(!$query){
                    return response()->json(['code'=>0,'msg'=>'something wen wrong!']);
                }else{
                    return response()->json(['code'=>1,'msg'=>'Successfully Updated!']);
                }
            }

        }
    }

    public function sendMail($email,$department,$first_name,$data)
    {
        Mail::to($email)->send(new DepartmentChangeMail($first_name,$department,$data));
    }

    public function delete(Request $request)
    {
        $emp_id = $request->e_id;
        $data = Employee::where('user_id',$emp_id)->first();
        $user_id = $data->user_id;
        $employee_id = $data->id;


        $query = Employee::find($employee_id)->delete();
        User::find($user_id)->delete();
        if($query){
            return response()->json(['code'=>1 ,'msg'=>'Employee has been deleted from Database']);
        }else{
            return response()->json(['code'=>1,'msg'=>'Something went wrong']);
        }
    }







}
