<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function Redirect(){
        $user = Auth::user()->role;
        if($user == 0){
            return view('index');
        }
        elseif ($user == 1) {
            return view('dashboard');
        }
            
        else {
            return view('auth.register');
        }
    }
}
