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
        return redirect()->route('home');
    }
}
