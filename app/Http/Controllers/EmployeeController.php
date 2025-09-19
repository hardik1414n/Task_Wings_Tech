<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function create(Request $request){

        if($request->type == 'experience')
        {
            $validator = Validator::make($request->all(),[
                'address'=>['required'],
                'gender'=>['required'],
                'profile_image'=>['required','file','mimes:png,jpg,jpeg'],
                'name'=>['required','string'],
                'email'=>['required','email','unique:employes,email'],
                'emp_code'=>['required','numeric','unique:employes,emp_code'],
                'mobile_number'=> ['required','numeric'],
                'type'=>['required'],
                'c_name'=>['required','string'],
                'designation'=>['required'],
                'j_date'=>['required'],
                'e_date'=>['required']
            ]);
        }else{
            $validator = Validator::make($request->all(),[
                'address'=>['required'],
                'gender'=>['required'],
                'profile_image'=>['required','file','mimes:png,jpg,jpeg'],
                'name'=>['required','string'],
                'email'=>['required','email','unique:employes,email'],
                'emp_code'=>['required','numeric','unique:employes,emp_code'],
                'mobile_number'=> ['required','numeric'],
                'type'=>['required'],
                'c_name'=>['nullable'],
                'designation'=>['nullable'],
                'j_date'=>['nullable'],
                'e_date'=>['nullable']
            ]);
        }

        if($validator->fails())
        {
            return response()->json([
            'errors' => $validator->errors()
            ]);
        }

        $validated = $validator->validated();

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('employees', 'public');
            $validated['profile_image'] = $path;
        }

        $employee = Employe::create($validated);

        return response()->json([
            'status'=>true,
            'message'=>'Employee Inserted Successfully'
        ]);
    }
}
