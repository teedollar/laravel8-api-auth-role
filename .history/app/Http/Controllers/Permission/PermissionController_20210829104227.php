<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

    public function addPermission(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string']

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
            $permission = Permission::create($postData);
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
