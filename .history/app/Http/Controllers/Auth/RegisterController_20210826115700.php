<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(){
        
        //
        $postData = $request->all();
        //encrypt password
        $postData['password'] = bcrypt($postData['password']);
        $code = generateVerCode();
        $postData['email_code'] = $code;
        $postData['user_hash'] = generateUserHash();
        $postData['status'] = 1;
    }
}
