<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;

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

Route::post('/signup', [UserController::class, "signup"]);
Route::post('/login', [UserController::class, "login"]);
Route::middleware('auth:sanctum')->put('/update', [UserController::class, "updateInformation"]);



Route::middleware('auth:sanctum')->get('/videos', [VideoController::class, "getAllVideos"]);
Route::middleware('auth:sanctum')->get('/myvideos', [VideoController::class, "getMyVideos"]);
Route::middleware('auth:sanctum')->get('/videos/{id}', [VideoController::class, "getVideoById"]);
Route::middleware('auth:sanctum')->post('/videos', [VideoController::class, "createVideo"]);
Route::middleware('auth:sanctum')->put('/videos/{id}', [VideoController::class, "updateVideo"]);
Route::middleware('auth:sanctum')->delete('/videos/{id}', [VideoController::class, "deleteVideo"]);