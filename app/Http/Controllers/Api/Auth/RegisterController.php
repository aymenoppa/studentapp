<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    // default function
    public function register(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|unique:users,email',
            'type'=>'required',
            'major'=>'required',
            'level'=>'required',
            'date_of_birthday'=>'required',
            'password'=>'required|min:6|confirmed',
        ]);

        dd($request->all());
    }
}
