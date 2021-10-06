<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function addUser(R){
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
            $user->email = $postData['email'];
            //$user->photo = $postData['photo'];
            $user->password = $postData['password'];
            //Assigning role
            $user->assignRole($postData['role']);
            //Assigning permission if exists
            if($request->has('permissions')){
                $user->givePermissionTo($postData['permissions']);
            }
            //
            $user->save();
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
}
