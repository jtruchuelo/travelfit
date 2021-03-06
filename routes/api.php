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

// Sygic
// Nuevo itinerario
Route::post('new_itinerary', 'SygicAPIController@new');

// Rutas protegidas
Route::middleware('APIToken')->group(function () {
    // Logout
    Route::post('/logout','AuthController@logout');
    // Destinos

    // Guardar itinerarios de usuario registrado
    Route::apiResource('itineraries','ItineraryController', ['only' => ['store', 'update', 'destroy']]);
    // POIS

    // Actualizar y mostrar usuarios
    Route::apiResource('users', 'UserController', ['only' => ['update', 'show']]);
    // Itinerarios de un usuario
    Route::post('itineraries/{user}','ItineraryController@indexUser');
    Route::post('itineraries/{user}/{itinerary}','ItineraryController@showUser');
});
