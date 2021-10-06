<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:5', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email'],
            'photo' => ['nullable','image'],
            'password' => ['min:6', 'required', 'confirmed'],
            'role' => ['required'],

        ]);
        //
        if ($validator->fails()) {
            $error = $validator->messages()->first();
            return $this->response(false, $error, 400);
        }
        //
        try{
            $postData = $request->all();
            //encrypt password
            $postData['password'] = Hash::make($postData['password']);
            //Save inside DB
            $user = new User();
            //
            $user->name = $postData['name'];
            /** @var User $user */
            //Generating bearer token
            $token = $user->createToken('authToken')->accessToken;
            //
            return response([
                'success' => true,
                'message' => 'success',
                'token' => $token,
                'user' => $user
            ]);
        }catch(Exception $exception){
            return $this->response(false, $exception->getMessage(), 400);
        }

    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required']

        ]);
        //
        if ($validator->fails()) {
            $error = $validator->messages()->first();
            return $this->response(false, $error, 400);
        }
        //
        try{
            $postData = $request->all();
            if(Auth::attempt(['email' => $postData['email'], 'password' => $postData['password']])){
                $user = Auth::user();
                //
                /** @var User $user */
                $token = $user->createToken('authToken')->accessToken;
                //
                return response([
                    'success' => true,
                    'message' => 'success',
                    'token' => $token,
                    'user' => $user
                ]);
            }

        }catch(Exception $exception){
            return $this->response(false, $exception->getMessage(), 400);
        }

    }
}
