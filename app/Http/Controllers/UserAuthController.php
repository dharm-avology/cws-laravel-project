<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;

class UserAuthController extends Controller
{
    //
    public function register()
    {
        return view('register');
    }

    public function registerPost(Request $request)
    {
        // Server-side validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email',
            'address' => 'required',
            'dob' => 'required|date|before_or_equal:-18 years',
            'gender' => 'required',
            'resume' => 'required|mimes:pdf,docx|max:2048',
            'photo' => 'required|image|mimes:jpg,png|max:2048',
        ]);
        // Handle Validation errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle file uploads
        $resumeName = time() . '_' . $request->file('resume')->getClientOriginalName();
        $request->file('resume')->move(public_path('uploads/resumes'), $resumeName);

        $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
        $request->file('photo')->move(public_path('uploads/photos'), $photoName);

        // Save the user's detials into users table
        $user = new User();  
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->address = $request->input('address');
        $user->dob = $request->input('dob');
        $user->gender = $request->input('gender');    
        $user->resume = $resumeName;
        $user->photo = $photoName;     

        $user->save();

        return back()->with('message', 'Application data saved successfully!');


    }

    //This function is used to show all the user from users table
    /**
     * @param| request parametor
     * @return| users object
    */    
    public function usersList()
    {
        $users = User::latest()->get();
        //check if user table return records or not
        if(!empty($users))
        {
            return view('list-user', compact('users'));
        }
        return back();
    }

    public function userEdit($id)
    {
        $user = User::find($id);
        //check if user table return records or not
        if($user)
        {
            return view('edit-user', compact('user'));
        }
        return back()->with('message', 'User not exist!');
    }

    public function updateUser(Request $request)
    {        
         // Server-side validation
         $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',            
            'address' => 'required',
            'dob' => 'required|date|before_or_equal:-18 years',
            'gender' => 'required',
            'resume' => 'nullable|mimes:pdf,docx|max:2048',
            'photo' => 'nullable|image|mimes:jpg,png|max:2048',
        ]);

        // Handle Validation errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // find user into the database
        $user = User::find(request('user_id'));

        // Handle file uploads
        if($request->file('resume'))
        {
            $resumeName = time() . '_' . $request->file('resume')->getClientOriginalName();
            $request->file('resume')->move(public_path('uploads/resumes'), $resumeName);        
            $user->resume = $resumeName;
            $user->save();
        }
        if($request->file('photo'))
        {            
            $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('uploads/photos'), $photoName);        
            $user->photo = $photoName;
            $user->save();
        }       
        
        // Save the user's detials into users table        
        $user->first_name = request('first_name');
        $user->last_name = request('last_name');
        $user->phone = request('phone');
        $user->address = request('address');
        $user->dob = request('dob');
        $user->gender = request('gender');
        $user->save();

        return back()->with('message', 'User updated successfully!');
    }

    public function userDestroy($id)
    {
        $user = User::find($id);
        if($user){
            return back()->with('message', 'Record deleted successfully!');
        }
        return back()->with('message', 'Record not found');

    }

    public function grtUserDetail(Request $request)
    {
        $userId = $request->user_id;
        $user = User::find($userId);
        // You can return a Blade view with the user details
        if($user)
        {
            $html = "
                <p><strong>User ID:</strong>".$user->id."</p>
                <p><strong>First Name:</strong>".$user->first_name."</p>
                <p><strong>Last Name:</strong>".$user->last_name."</p>
                <p><strong>Email:</strong>".$user->email."</p>
                <p><strong>Phone:</strong>".$user->phone."</p>
                <p><strong>DOB:</strong>".$user->dob."</p>
                <p><strong>Gender:</strong>".$user->gender."</p>
                <p><strong>Address:</strong>".$user->address."</p>           
            ";
            return response()->json(['success' => true, 'data' => $html], 200);
        }
        return response()->json(['success' => false, 'mesage' => 'User not found!'], 200);


    }
}
