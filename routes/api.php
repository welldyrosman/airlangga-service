<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\OurServController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TestimoniController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\transController;

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

Route::post('/registerclient', [AuthController::class, 'registerclient']);
Route::post('/loginclient', [AuthController::class, 'loginclient']);

Route::get('/airlanggadata', [ClientController::class, 'getall']);

Route::get('/gettripbyid/{id}', [ClientController::class, 'gettripbyid']);


Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::group([
    'middleware' => ['jwt.verify:userclients','cors:userclients'],
], function ($router) {
    Route::get('/trip', [TripController::class, 'getall']);
    Route::post('/book',[transController::class,'book']);
});

Route::group([
    'middleware' => ['jwt.verify:users','cors:users'],
], function ($router) {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);

    Route::post('/tripdates/{id}', [TripController::class, 'submitdates']);

    Route::post('/trip', [TripController::class, 'create']);
    Route::post('/tripimage/{id}', [TripController::class, 'addimages']);
    Route::delete('/tripimage/{id}', [TripController::class, 'deleteimg']);



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

    Route::post('/testimoni', [TestimoniController::class, 'create']);
    Route::get('/testimoni', [TestimoniController::class, 'getall']);
    Route::post('/testimoni/{id}', [TestimoniController::class, 'update']);
    Route::delete('/testimoni/{id}', [TestimoniController::class, 'delete']);

    Route::post('/gallery', [GalleryController::class, 'create']);
    Route::get('/gallery', [GalleryController::class, 'getall']);
    Route::post('/gallery/{id}', [GalleryController::class, 'update']);
    Route::delete('/gallery/{id}', [GalleryController::class, 'delete']);
});


