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
// Contacto
Route::post('/contact','ContactController@contact');
// Itinerarios públicos
Route::get('itineraries','ItineraryController@index');
Route::get('itineraries/{itinerary}','ItineraryController@show');

// Crear nuevo itinerario
Route::post('newItinerary', 'NewItineraryController@create');

// Rutas protegidas
Route::middleware('APIToken')->group(function () {
    // Logout
    Route::post('/logout','AuthController@logout');
    // Acceso Destinos
    Route::apiResource('destinations','DestinationController');
    // Acceso Itinerarios
    Route::apiResource('itineraries','ItineraryController', ['only' => ['store', 'update', 'destroy']]);
    //Route::apiResource('itineraries.destinations','ItineraryController');
    // Acceso POIS
    Route::apiResource('pois','PoiController', ['parameters' => [
        'pois' => 'poi',
    ]]);
    Route::apiResource('users', 'UserController', ['only' => ['update', 'show']]);

});

// FourSquare
Route::post('/venues', 'ApiFoursquareController@getVenues');


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
