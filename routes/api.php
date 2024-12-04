<?php

use App\Http\Controllers\IncomeExpanseController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post("user/register", [UserController::class, "register"]);
Route::post("user/login", [UserController::class, "login"]);

Route::group(['middleware'=>'auth:sanctum'],function(){
    Route::get("auth/user", [UserController::class, "authUser"]);
    Route::post("create/type", [TypeController::class, "store"]);
    Route::get("get_all/type", [TypeController::class, "getAll"]);
    Route::put("change_active/type/{id}", [TypeController::class, "changeActive"]);
    Route::put("user/update/{id}", [UserController::class, "update"]);
    Route::get("type/show", [TypeController::class,"index"]);
    Route::post("income_expanses/create", [IncomeExpanseController::class,"store"]);
    Route::get("income_expanses/get", [IncomeExpanseController::class,"index"]);
    Route::put("income_expanses/update/{id}", [IncomeExpanseController::class,"update"]);

} );



