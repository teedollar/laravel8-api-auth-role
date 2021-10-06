<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:5|max:50',
            'email' => 'required|email|unique:users,email',
            'phot' => 'nullable|string',
            'password' => 'min:6|required_with:confirm_password|same:new_password_confirmation',

        ]);
        //
        if ($validator->fails()) {
            $fieldsWithErrorMessagesArray = $validator->messages()->first();
            return response()->json(['message' => $fieldsWithErrorMessagesArray], 422);
        }
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
