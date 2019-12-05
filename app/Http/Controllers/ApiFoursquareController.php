<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDiazDumont\Foursquare\Client;
use Validator;
use Illuminate\Support\Facades\Cache;
use DateInterval;

class ApiFoursquareController extends Controller
{

    public function getVenues(Request $request) {

        $rules = [
            'near' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $respuesta = Array (
                'code' => 401,
                'status' => 'error',
                'message' => $validator->messages(),
            );
        } else {

            $venues = Cache::remember($request->near,new DateInterval("P2W"), function() use ($request){
                $foursquare = new Client(env('FOURSQUARE_CLIENT_ID'),env('FOURSQUARE_CLIENT_SECRET'));
                $venues = $foursquare->venues()->search([
                    'near' => $request->near,
                    'intent' => 'checkin',
                    'locale' => 'es',
                ]);

                return $venues;

            });

            if($venues) {
                $respuesta = Array (
                    'code' => 201,
                    'status' => 'success',
                    'venues' => $venues['venues'],
                );
            } else {
                $respuesta = Array (
                    'code' => 401,
                    'status' => 'error',
                );
            }
        }

        // return $venues;
        return response()->json($respuesta, $respuesta['code']);
    }
}
