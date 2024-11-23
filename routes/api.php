<?php

use App\Http\Controllers\Api\Abilities\AbilitiesController;
use App\Http\Controllers\Api\Activity\ActivityController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\Backup\BackupController;
use App\Http\Controllers\Api\Roles\RolesController;
use App\Http\Controllers\Api\Users\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login',[AuthController::class,'login'])->name('login');
Route::post('/forgot-password',[PasswordResetController::class,'email'])->name('password.forgot');
Route::get('/password/find/{token}',[PasswordResetController::class,'find'])->name('password.find');
Route::post('/password/reset', [PasswordResetController::class,'reset'])->name('password.reset');


Route::middleware('auth:sanctum')->group( function () {

    Route::get('/user',function (Request $request){
       return  $request->user();
    });

    Route::post('/logout',[AuthController::class,'logout'])->name('logout'); // ruta para cerrar sesion

    Route::get('abilities',[AbilitiesController::class,'index'])->name('abilities.index');  // obtener todas las habilidades

    Route::apiResource('roles',RolesController::class); // rutas rest de roles

    Route::apiResource('users',UsersController::class); // rutas rest de usuarios

    Route::post('users/active/{user}',[UsersController::class,'blockUser'])->name('users.active'); // rutas rest de usuarios

    Route::post('users/remove-token/{user}',[UsersController::class,'removeToken'])->name('users.remove-token'); // rutas rest de usuarios

    Route::get('activity',[ActivityController::class,'index'])->name('activity.index');  // listar log de actividades

    Route::get('backup',[BackupController::class,'index'])->name('backup');  // listar log de actividades
});
