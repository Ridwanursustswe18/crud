<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended('hello');
            } else {
                return 'These credentials do not match our records.';
            }
        } catch (Exception $e) {
            $e->getMessage();
        }
    }
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->intended('/');
        } catch (Exception $e) {
            $e->getMessage();
        }

    }
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    public function register(Request $request)
    {

        try {

            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
                'profile_picture'=>'nullable'
                
            ]);
          
            if ($request->hasFile('profile_picture')) {
                $profile_picture = $request->file('profile_picture');
                $path = $profile_picture->store('profilePictures', 'public');
                
                $validatedData['profile_picture'] = $path;
            }
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'profile_picture'=>$validatedData['profile_picture'] ?? null,

            ]);
            //dd(1);
            

            Auth::login($user);
         
            return redirect()->intended('hello');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}