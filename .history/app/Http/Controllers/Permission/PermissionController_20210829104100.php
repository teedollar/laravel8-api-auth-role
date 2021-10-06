<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function allPermissions(){
        try{
            $permissions = Permission::get();
            return $this->response(false, $permissions, 200);
        }catch(Exception $exception){
            return $this->response(false, $exception->getMessage(), 400);
        }

    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:5', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email'],
            'photo' => ['nullable','image'],
            'password' => ['min:6', 'required', 'confirmed'],

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
            $user = User::create($postData);
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
