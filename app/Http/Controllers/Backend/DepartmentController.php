<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use DataTables;
use Carbon\Carbon;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('department.index');
    }

    public function create()
    {
        return view('department.create');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'name'=>'required',
        ]);

        if(!$validator->passes()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
            $dept = new Department();
            $dept->name = $request->name;
            $query = $dept->save();

            if(!$query){
                return response()->json(['code'=>0,'msg'=>'something wen wrong!']);
            }else{
                return response()->json(['code'=>1,'msg'=>'Successfully Added!']);
            }
        }
    }

    public function show()
    {
        $dept= Department::all();
        return Datatables::of($dept)
                            ->addIndexColumn()
                            ->editColumn('created_at', function ($dept) {
                                return $dept->created_at ? with(new Carbon($dept->created_at))->format('m/d/Y') : '';
                              })
                            ->addColumn('actions',function($row){
                                    return '<div class="btn-group">
                                                 <button class="btn btn-primary" data-id="'.$row['id'].'" id="editDepartment">Update</button>
                                                 <button class="btn btn-danger" data-id="'.$row['id'].'" id="deleteDepartment">Delete</button>
                                            </div>';
                                 })
                            ->rawColumns(['actions'])
                            ->make(true);
    }

    public function getDepartment(Request $request)
    {
        $dep_id = $request->dep_id;
        $deptDetails = Department::find($dep_id);
        return response()->json(['details'=>$deptDetails]);
    }

    public function update(Request $request)
    {
        $dept_id = $request->d_id;

        $validator = \Validator::make($request->all(),[
            'name'=>'required',
        ]);

        if(!$validator->passes()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
            $dept = Department::find($dept_id);
            $dept->name = $request->name;
            $query = $dept->save();

            if(!$query){
                return response()->json(['code'=>0,'msg'=>'something wen wrong!']);
            }else{
                return response()->json(['code'=>1,'msg'=>'Successfully Updated!']);
            }
        }
    }

    public function delete(Request $request)
    {
        $dept_id = $request->d_id;
        $query = Department::find($dept_id)->delete();

        if($query){
            return response()->json(['code'=>1 ,'msg'=>'Section has been deleted from Database']);
        }else{
            return response()->json(['code'=>1,'msg'=>'Something went wrong']);
        }
    }
}
