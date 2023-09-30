<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function login()  {

        if(auth()->user()){
            return to_route('home');
        }

        if(request()->isMethod('post')){
            $credentials = request()->validate([
              'email' => ['required', 'email:rfc,dns'],
              'password' => ['required'],
          ]);

         
  
          if (Auth::attempt($credentials)) {
              request()->session()->regenerate();
              return redirect()->intended('home');
          }
  
          return back()->withErrors([
              'email' => 'The provided credentials do not match our records.',
          ])->onlyInput('email');
        }

        return Inertia::render('Auth/Login');
    }



    function signUp()  {

        if(request()->isMethod('post')){
            $credentials = request()->validate([
              'email' => ['required', 'email:rfc,dns', 'unique:users,email'],
              'name' => ['required','min:3'],
              'password' => ['required','min:6'],
              'confirm_password' => ['required','same:password'],
          ]);

          $user = new User();
          $user->name = request()->name;
          $user->email = request()->email;
          $user->password = request()->password;
          $user->save();
          $credentials = ['email'=>$user->email, 'password'=>request()->password];
          if (Auth::attempt($credentials)) {
            request()->session()->regenerate();
            return redirect()->intended('home');
          }
  
          return to_route('login')->with('success','Your registration successfully completed');
        }

        return Inertia::render('Auth/Signup');
    }


    public function logout()
    {
       Auth::logout();
       return to_route('login');
    }

}
