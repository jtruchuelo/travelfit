<?php

use Illuminate\Support\Facades\Route;
/* use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\User;
 */

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

// Login
Route::post('/login','AuthController@login');
// Register
Route::post('/register','AuthController@register');

Route::middleware('APIToken')->group(function () {
    // Logout
    Route::post('/logout','AuthController@logout');
    // Acceso Destinos
    Route::apiResource('destinations','DestinationController');
    // Acceso Itinerarios
    Route::apiResource('itineraries','ItineraryController');
    //Route::apiResource('itineraries.destinations','ItineraryController');
    // Acceso POIS
    Route::apiResource('pois','PoiController', ['parameters' => [
        'pois' => 'poi',
    ]]);
    Route::apiResource('users', 'UserController', ['only' => ['update', 'show']]);

});

// Prueba
Route::post('/prueba', 'ApiFoursquareController@getVenues');

/*

// Route::apiResource('users','UserController');

// Route::apiResource('users','UserController', ['only' => ['index', 'show']]);
// Route::apiResource('users','UserController', ['except' => ['index', 'show']]);


// Route::post('/register', 'UserController@store');
// Route::post('/login', 'UserController@login');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

*/
