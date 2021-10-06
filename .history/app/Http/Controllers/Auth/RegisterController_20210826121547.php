<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:5', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email'],
            'photo' => ['nullable','string'],
            'password' => ['min:6', 'required|confirmed'],

        ]);
        //
        if ($validator->fails()) {
            $error = $validator->messages()->first();
            return $this->response(false, $error, 301);
        }
        //
        $postData = $request->all();
        //encrypt password
        $postData['password'] = Hash::make($postData['password']);
        //Save inside DB
        $user = User::create($postData);
        //Generating bearer token
        

    }
}
