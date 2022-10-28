<?php

use App\Http\Controllers\ApiController;
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
Route::post('/check-mail', [ ApiController::class , 'checkMail' ] );
Route::post('/register', [ ApiController::class , 'register' ] ); 
Route::post('/login' ,  [ ApiController::class , 'login' ] ) ;
Route::post('/children/login' , [ ApiController::class , 'childrenLogin' ] );

// protected route that allows only authnticated users to make request
Route::middleware('auth:sanctum') -> group(function () {       
    Route::post('/logout', [ ApiController::class , 'logout' ] ); 
});

 
