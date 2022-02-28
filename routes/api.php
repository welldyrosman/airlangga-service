<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\OurServController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TripController;

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




Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::group([
    'middleware' => ['jwt.verify','cors'],
], function ($router) {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);

    Route::post('/tripdates/{id}', [TripController::class, 'submitdates']);

    Route::post('/trip', [TripController::class, 'create']);
    Route::post('/tripimage/{id}', [TripController::class, 'addimages']);
    Route::delete('/tripimage/{id}', [TripController::class, 'deleteimg']);

    Route::get('/trip', [TripController::class, 'getall']);
    Route::put('/trip/{id}', [TripController::class, 'update']);
    Route::delete('/trip/{id}', [TripController::class, 'delete']);

    Route::put('/tripenable/{id}', [TripController::class, 'enable']);
    Route::put('/tripdisable/{id}', [TripController::class, 'disabled']);

    Route::post('/ourservices', [OurServController::class, 'create']);
    Route::put('/ourservices/{id}', [OurServController::class, 'update']);
    Route::get('/ourservices', [OurServController::class, 'getall']);
    Route::delete('/ourservices/{id}', [OurServController::class, 'delete']);

    Route::put('/about', [CommonController::class, 'updateabout']);
    Route::get('/about', [CommonController::class, 'getabout']);

    Route::post('/team', [TeamController::class, 'create']);
    Route::get('/team', [TeamController::class, 'getall']);
    Route::post('/team/{id}', [TeamController::class, 'update']);
    Route::delete('/team/{id}', [TeamController::class, 'delete']);

});

