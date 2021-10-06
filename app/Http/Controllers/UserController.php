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
        //$this->middleware('auth:api');
    }

    public function listUsers(){
        try{
            $users = User::get();
            //if($users->count() > 0){
                return $this->response(true, $users, 200);
            //}
        }catch(Exception $exception){
            return $this->response(false, $exception->getMessage(), 400);
        }
    }

    public function addUser(Request $request){
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

    // Update user
    public function updateUser(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:5', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email'],
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
            //Save inside DB
            $user = User::where('id', $id)->first();
            //Assigning role
            $user->assignRole($postData['role']);
            //Assigning permission if exists
            if($request->has('permissions')){
                $user->givePermissionTo($postData['permissions']);
            }
            //
            $user->update($postData);

            return $this->response(true, "User updated", 200);
        }catch(Exception $exception){
            return $this->response(false, $exception->getMessage(), 400);
        }

    }

    // Delete a user and its roles and permissions
    public function deleteUser(Request $request, $id){
        try{
            $postData = $request->all();
            //Save inside DB
            $user = User::where('id', $id)->delete();
            //
            return $this->response(true, "User deleted", 200);
        }catch(Exception $exception){
            return $this->response(false, $exception->getMessage(), 400);
        }

    }
}
