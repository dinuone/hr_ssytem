<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user_id = auth()->user()->id;
        $userData = User::find($user_id);
        $empData = Employee::where('user_id',$user_id)->first();

        return view('Profile.index',compact('userData','empData'));
    }

    public function update(Request $request)
    {
        $user_id =auth()->user()->id;

        if(auth()->user()->is_admin == 1){
            $validator = \Validator::make($request->all(),[
                'first_name'=>['required', 'string', 'max:255'],
                'last_name'=>['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
            ]);
        }

        if(auth()->user()->is_admin == 0){
            $validator = \Validator::make($request->all(),[
                'first_name'=>['required', 'string', 'max:255'],
                'last_name'=>['required', 'string', 'max:255'],
                'username'=>['required', 'string', 'max:255'],
                'address'=>['required', 'string', 'max:255'],
                'birthdate'=>['required'],
                'email' => ['required', 'string', 'email', 'max:255'],
            ]);
        }



        if(!$validator->passes()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else {

            if(auth()->user()->is_admin == 1){

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

            }else{
                $empData = Employee::where('user_id',$user_id)->first();

                $employee = Employee::find($empData->id);
                $employee->username = $request->username;
                $employee->address = $request->address;
                $employee->birth_date =$request->birthdate;
                $employee->save();

                $usr = User::find($user_id);
                $usr->first_name =$request->first_name;
                $usr->last_name = $request->last_name;
                $usr->email = $request->email;
                if($request->password){
                    $usr->password = Hash::make($request->password);
                }else{
                    $usr->password =  $usr->password;
                }

                $query = $usr->save();

                if(!$query){
                    return response()->json(['code'=>0,'msg'=>'something wen wrong!']);
                }else{
                    return response()->json(['code'=>1,'msg'=>'Successfully Updated!']);
                }
            }

        }
    }
}
