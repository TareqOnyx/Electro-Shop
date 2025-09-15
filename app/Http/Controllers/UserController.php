<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;

class User_Controller extends Controller
{
    public function Redirect(){
        $user = Auth::user()->user_type;
        if($user == 0){
            return view('index');
        }
        else{
            return view('dashboard');
        }
    }
}
