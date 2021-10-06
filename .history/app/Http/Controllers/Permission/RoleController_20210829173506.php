<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Exception;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function allRoles(){
        try{
            $Roles = Role::get();
            return $this->response(true, $Roles, 200);
        }catch(Exception $exception){
            return $this->response(false, $exception->getMessage(), 400);
        }

    }

    public function addRole(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'unique:Roles,name']

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
            $Role = Role::create($postData);
            //Saving permissions if exists
            if($request->has($postData))
            return $this->response(true, $Role, 200);
        }catch(Exception $exception){
            return $this->response(false, $exception->getMessage(), 400);
        }

    }

    public function updateRole(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'unique:Roles,name']

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
            $Role = Role::where('id', $id)->update($postData);
            //
            return $this->response(true, "Role updated", 200);
        }catch(Exception $exception){
            return $this->response(false, $exception->getMessage(), 400);
        }

    }

    public function deleteRole(Request $request, $id){
        try{
            $postData = $request->all();
            //Save inside DB
            $Role = Role::where('id', $id)->delete();
            //
            return $this->response(true, "Role deleted", 200);
        }catch(Exception $exception){
            return $this->response(false, $exception->getMessage(), 400);
        }

    }
}
