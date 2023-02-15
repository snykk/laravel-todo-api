<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TodoController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route Endpoints
Route::get("/", function () {
    return response()->json([
        "message" => "welcome to an amazing api",
    ], 200);
});





Route::group(["prefix" => "v1", "namespace" => "App\Http\Controllers\Api\V1"], function () {
    Route::group(["prefix" => "auth", "namespace" => "App\Http\Controllers\Api\V1\AuthController"], function () {
        Route::post("login", [AuthController::class, "login"]);
        Route::post("register", [AuthController::class, "register"]);

        Route::get('/me', [AuthController::class, "me"])->middleware('auth:sanctum');
    });

    Route::middleware("auth:sanctum")->group(function () {
        Route::apiResource("todos", TodoController::class);
    });
});
