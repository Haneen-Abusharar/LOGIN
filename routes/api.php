<?php
//header('Access-Control-Allow-Origin: *');
//header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
//header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/create', [\App\Http\Controllers\UserController::class,'create']);
Route::post('/register', [\App\Http\Controllers\UserController::class,'register']);
Route::post('/login', [\App\Http\Controllers\UserController::class,'login']);
Route::post('/check',[\App\Http\Controllers\UserController::class,'checkEmail']);
Route::post('/updateprofile',[\App\Http\Controllers\UserController::class,'profileUpdate']);
Route::get('/profile',[\App\Http\Controllers\UserController::class,'profile']);
Route::post('/post',[\App\Http\Controllers\UserController::class,'createPost']);

