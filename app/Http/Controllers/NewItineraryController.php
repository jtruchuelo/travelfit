<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDiazDumont\Foursquare\Client;

class NewItineraryController extends Controller
{

    public function create(Request $request)
    {
        // Objecto con los ajustes del nuevo itinerario.
        $itinerary = (object) $request->newItinerary;
        // PARA OBTENER LAS CATEGORIAS
        $preferences = $this->preferencesFormat($request->preferences);

        var_dump($itinerary);
        // Categorias para la query a Foursquare

        // var_dump($itinerary['destinations']);
        die();

        // lugar
        // numero de días


        $foursquare = new Client(env('FOURSQUARE_CLIENT_ID'),env('FOURSQUARE_CLIENT_SECRET'));


        $respuesta = Array (
            'code' => 200,
            'status' => 'success'
        );
        return response()->json($respuesta, $respuesta['code']);
    }

    public function preferencesFormat(Array $preferences) {
        $cadena= [];
        $resultado = '';

        if($preferences['arte'] == true) {
            array_push($cadena, \Config::get('foursquareCategories.ARTE'));
        }
        if($preferences['entretenimiento'] == true) {
            array_push($cadena, \Config::get('foursquareCategories.ENTRETENIMIENTO'));
        }
        if($preferences['experiencias'] == true) {
            array_push($cadena, \Config::get('foursquareCategories.EXPERIENCIAS'));
        }
        if($preferences['shopping'] == true) {
            array_push($cadena, \Config::get('foursquareCategories.SHOPPING'));
        }
        if($preferences['eventos'] == true) {
            array_push($cadena, \Config::get('foursquareCategories.EVENTOS'));
        }
        if($preferences['gastronomia'] == true) {
            array_push($cadena, \Config::get('foursquareCategories.GASTRONOMIA'));
        }
        if($preferences['aire'] == true) {
            array_push($cadena, \Config::get('foursquareCategories.AIRE'));
        }

        for ($i = 0; $i < count($cadena); $i++) {
            $resultado .= implode(",", $cadena[$i]);
        }

        return $resultado;
    }
}


