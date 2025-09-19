<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    public function employees(){
        $employees = Employe::all();

        foreach ($employees as $employee) {
            $employee->profile_image = Storage::url($employee->profile_image);
        }

        return response()->json($employees);
    }

    public function edit($id){

        $employee = Employe::find($id);
        $employee->profile_image = Storage::url($employee->profile_image);
        return response()->json($employee);
    }

    public function delete($id)
    {
        $employee = Employe::findOrFail($id);

        // Delete profile image from storage if exists
        if (Storage::disk('public')->exists($employee->profile_image)) {
            Storage::disk('public')->delete($employee->profile_image);
        }

        // Delete employee record
        $employee->delete();

        return response()->json([
            'message' => 'Employee and profile image deleted successfully'
        ]);
    }

    public function update(Request $request,$id)
    {

        $employee = Employe::findOrFail($id);

        $rules = [
            'emp_code' => 'required|numeric|unique:employes,emp_code,'.$id,
            'name' => 'required|string',
            'email' => 'required|email|unique:employes,email,'.$id,
            'mobile_number' => 'required|string',
            'address' => 'required|string',
            'gender' => 'required|in:Male,Female',
            'type' => 'required|in:fresher,experience',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png',
        ];

        if ($request->type === 'experience') {
            $rules = array_merge($rules, [
                'c_name' => 'required|string',
                'designation' => 'required|string',
                'j_date' => 'required|date',
                'e_date' => 'required|date|after_or_equal:j_date',
            ]);
        }

        $request->validate($rules);


        $employee->update($request->except('profile_image'));


        if ($request->hasFile('profile_image')) {

            if ($employee->profile_image) {
                if (Storage::disk('public')->exists($employee->profile_image)) {
                    Storage::disk('public')->delete($employee->profile_image);
                } elseif(file_exists(public_path($employee->profile_image))) {
                    unlink(public_path($employee->profile_image));
                }
            }

            $path = $request->file('profile_image')->store('profile_images', 'public');
            $employee->profile_image = $path;
            $employee->save();
        }

        if ($request->type === 'fresher') {
            $employee->c_name = null;
            $employee->designation = null;
            $employee->j_date = null;
            $employee->e_date = null;
            $employee->save();
        }

        return response()->json(['message' => 'Employee updated successfully']);
    }

    public function view($id)
    {
        $employee = Employe::findOrFail($id);
        $employee->profile_image = Storage::url($employee->profile_image);
        return response()->json($employee);
    }
}