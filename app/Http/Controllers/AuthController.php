<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function login(Request $request){
        $request->validate([
            'email'=>'email|required|string',
            'password'=>'min:8|required|string',
        ]);
        if(!auth()->attempt($request->only('email','password'),$request->remember)){
            return back()->with('status','Credenciales incorrectas');
        }
        return redirect()->route('home');
    }
    public function logout(){
        auth()->logout();
        return redirect()->route('auth');
    }
}
