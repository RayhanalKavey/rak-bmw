<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{

    public function userRegistration(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required',
                'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            ]);

            $data = [
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                // 'password' => $validated['password'],
            ];
            if ($request->hasFile('profile_pic')) {
                $image = $request->file('profile_pic');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $filePath = 'profilePic/' . $fileName;
                $image->move(public_path('profilePic'), $fileName);
                $data['profile_pic'] = $filePath;
                // $data['profile_pic'] = $request->file('profile_pic')?->store('profiles', 'public');
            }
            User::create($data);

            $data = ['message' => 'Registration successful', 'status' => true, 'error' => ''];
            return redirect('/login')->with($data);
        } catch (Exception $e) {

            $data = ['message' => 'Something went wrong', 'status' => false, 'error' => ''];
            return redirect('/registration')->with($data);
        }
    }
    public function userLogin(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            $user = User::where('email', $request->input('email'))->select('id', 'password')->first();
            if ($user !== null && Hash::check($request->input('password'), $user->password)) {
            }
            $email = $request->input('email');
            $user_id = $user->id;
            $request->session()->put('email', $email);
            $request->session()->put('user_id', $user_id);
            $data = ['message' => 'Login successful', 'status' => true, 'error' => ''];
            return redirect('/test')->with($data);
        } catch (Exception $e) {
            $data = ['message' => 'Login failed', 'status' => false, 'error' => ''];
            return redirect('/login')->with($data);
        }

    }
    public function userLogout(Request $request)
    {

        $request->session()->forget('email');
        $request->session()->forget('user_id');
        $request->session()->forget('last_activity');

        // $request->session()->flush();// Completely clear

        return redirect('/login')->with([
            'message' => 'User logged out successfully',
            'status' => true,
            'error' => ''
        ]);
    }
    /* Page */

    public function loginPage(Request $request)
    {
        return Inertia::render('LoginPage');
    }

    public function registrationPage(Request $request)
    {
        return Inertia::render('RegistrationPage');
    }
}
