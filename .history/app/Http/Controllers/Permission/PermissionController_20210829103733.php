<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
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

        }catch(Exception $exception){
            return $this->response(false, $exception->getMessage(), 400);
        }
        
    }
}
