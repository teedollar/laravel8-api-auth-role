<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Permission\PermissionController;
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
    Route::post('add-permission', [PermissionController::class, 'addPermission']);
    Route::get('all-permissions', [PermissionController::class, 'allPermissions']);
    Route::post('update-permission/{id}', [PermissionController::class, 'updatePermission']);
    Route::post('delete-permission/{id}', [PermissionController::class, 'deletePermission']);

    //Roles
    Route::post('add-permission', [PermissionController::class, 'addPermission']);
    Route::get('all-permissions', [PermissionController::class, 'allPermissions']);
    Route::post('update-permission/{id}', [PermissionController::class, 'updatePermission']);
    Route::post('delete-permission/{id}', [PermissionController::class, 'deletePermission']);
});
