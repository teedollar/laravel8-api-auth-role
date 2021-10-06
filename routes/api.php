<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Permission\RoleController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login'])->name('login');

//Route::get('test', [TestController::class, 'index']);

Route::group(["middleware" => "auth:api"], function () {
    //Permissions
    Route::group(["prefix" => "permission"], function () {
        Route::post('add', [PermissionController::class, 'addPermission']);
        Route::get('list', [PermissionController::class, 'allPermissions']);
        Route::post('update/{id}', [PermissionController::class, 'updatePermission']);
        Route::post('delete/{id}', [PermissionController::class, 'deletePermission']);
    });

    //Roles
    Route::group(["prefix" => "role"], function () {
        Route::post('add', [RoleController::class, 'addRole']);
        Route::get('list', [RoleController::class, 'allRoles']);
        Route::post('update/{id}', [RoleController::class, 'updateRole']);
        Route::post('delete/{id}', [RoleController::class, 'deleteRole']);
    });

    //User
    Route::group(["prefix" => "user"], function () {
        Route::post('add', [UserController::class, 'addUser']);
        Route::get('list', [UserController::class, 'listUsers']);
        Route::post('update/{id}', [UserController::class, 'updateUser']);
        Route::post('delete/{id}', [UserController::class, 'deleteUser']);
    });
});
