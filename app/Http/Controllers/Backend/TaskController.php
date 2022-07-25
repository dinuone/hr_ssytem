<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Task;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index()
    {
        return view('task.index');
    }

    public function create()
    {
        $departments = Department::all();
        return view('task.create',compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_name'=>'required',
            'department'=>'required|exists:departments,id',
            'task_deadline'=>'required'
        ]);

        Task::create([
            'task_name'=>$request['task_name'],
            'department_id'=>$request['department'],
            'task_desc'=>$request['task_desc'],
            'deadline'=>$request['task_deadline']
        ]);

        return redirect()->route('task.index')->with('success','New Task Created!');
    }

   
    public function show()
    {
        if(request()->ajax()) {
            $model = Task::with('department');
            return DataTables::eloquent($model)
            ->addColumn('department',function(Task $task){
                return $task->department->name;
            })

            ->addColumn('due',function(Task $task){
                $date = Carbon::Today()->format('Y-m-d');
             
                if($task->deadline < $date ){

                    if($task->is_complete == 1){
                        $due = '<span class="badge badge-success">Completed</span>';
                    }else{
                        $due = '<span class="badge badge-danger">Due Task</span>';
                    }
                  
                }else{
                    $due = '<span class="badge badge-primary">Pending</span>';
                }
                return $due;

            })

            ->addColumn('is_complete',function(Task $task){
    

                if($task->is_complete == 1){
                    $due = '<span class="badge badge-success">Completed</span>';
                }else{
                    $due = '<span class="badge badge-danger">Not Completed</span>';
                }

                return $due;

            })

            ->editColumn('created_at', function ($task) {
                return $task->created_at ? with(new Carbon($task->created_at))->format('m/d/Y') : '';
                })

            ->addColumn('actions',function($row){
                    return '<div class="btn-group">
                                    <button class="btn btn-primary" data-id="'.$row['id'].'" id="editTask">Update</button>
                                    <button class="btn btn-danger" data-id="'.$row['id'].'" id="deleteTask">Delete</button>
                            </div>';
                    })
            ->rawColumns(['actions'])
            ->addIndexColumn()
            ->escapeColumns([])
            ->make(true);
        }
   }

   //get task data
   public function getTask(Request $request)
   {
        $task_id = $request->task_id;
        $taskDetails = Task::find($task_id);
        $depts = Department::all();
        return response()->json(['details'=>$taskDetails,'depts'=>$depts]);
   }

   public function updateTask(Request $request)
   {
       $task_id = $request->t_id;

        $validator = \Validator::make($request->all(),[
            'task_name'=>'required',
            'department'=>'required',
            'task_deadline'=>'required'
        ]);


        if(!$validator->passes()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
            $task = Task::find($task_id);
            $task->task_name = $request->task_name;
            $task->department_id = $request->department;
            $task->task_desc = $request->task_desc;
            $task->deadline = $request->task_deadline;
            $query = $task->save();

            if(!$query){
                return response()->json(['code'=>0,'msg'=>'something wen wrong!']);
            }else{
                return response()->json(['code'=>1,'msg'=>'Successfully Updated!']);
            }
        }
   }


   public function delete(Request $request)
   {
       $task_id = $request->t_id;
       $query = Task::find($task_id)->delete();
   
       if($query){
           return response()->json(['code'=>1 ,'msg'=>'Task has been deleted from Database']);
       }else{
           return response()->json(['code'=>1,'msg'=>'Something went wrong']);
       }
   }
}


