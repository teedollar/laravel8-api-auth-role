<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:5', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email',
            'photo' => 'nullable|string',
            'password' => 'min:6|required|conirmed',

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
