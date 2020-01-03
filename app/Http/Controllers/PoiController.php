<?php

namespace App\Http\Controllers;

use App\Http\Resources\PoiResource;
use App\Itinerary;
use App\Poi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class PoiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PoiResource::collection(Poi::all())->sortBy('startDate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validations
        $rules = [
            'name' => 'required|string|max:60',
            'idApi' => 'required|alpha_num|max:50',
            'startDate' => 'required|date',
            // 'endDate' => 'required|date',
            'destination_id' => 'required|integer|exists:destinations,id',
            'location' => 'required',
            'photo' => 'required',
            'duration' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // Validation failed
            $respuesta = Array (
                'code' => 401,
                'status' => 'error',
                'message' => $validator->messages(),
            );
        } else {
            $poi = Poi::create([
                'name' => $request->name,
                'idApi' => $request->idApi,
                'startDate' => $request->startDate,
                // 'endDate' => $request->endDate,
                'destination_id' => $request->destination_id,
                'location' => $request->location,
                'photo' => $request->photo,
                'duration' => $request->duration,
            ]);

            if($poi) {
                $respuesta = Array (
                    'code' => 201,
                    'status' => 'success',
                );
            } else {
                $respuesta = Array (
                    'code' => 401,
                    'status' => 'error',
                    'message' => 'POI not created.',
                );
            }
        }

        return response()->json($respuesta, $respuesta['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Poi $poi)
    {
        $respuesta = Array (
            'code' => 200,
            'status' => 'success',
            'poi' => new PoiResource($poi)
        );

        return response()->json($respuesta, $respuesta['code']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Poi $poi)
    {
        $rules = [
            'name' => 'required|string|max:60',
            'idApi' => 'required|alpha_num|max:50',
            'startDate' => 'required|date',
            // 'endDate' => 'required|date',
            'destination_id' => 'required|integer|exists:destinations,id',
            'location' => 'required',
            'photo' => 'required',
            'duration' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator) {
            $poi->update($request->only(['startDate', 'duration']));
            $respuesta = Array (
                'code' => 200,
                'status' => 'success',
            );

        } else {
            $respuesta = Array (
                'code' => 304,
                'status' => 'error',
                'message' => 'POI not modified.',
            );
        }

        return response()->json($respuesta, $respuesta['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Poi $poi)
    {
        // ¿COMO HAGO PARA AUTENTICAR QUE SEA EL USUARIO DUEÑO?
        /* SELECT `user_id` FROM `itineraries` WHERE `id` = (SELECT `itinerary_id` FROM `destinations` WHERE `id` = 5)
        $id = $poi->destination_id;
        $checks = Itinerary::select('user_id')
                            ->where('id', '=', function ($id) {
                                DB::table('destinations')->select('itinerary_id')
                                    ->where('id', '=', $id)->get();
                            })->get()->user_id;0
        */
        if ($poi->delete()) {
            $respuesta = Array (
                'code' => 200,
                'status' => 'success',
            );
        } else {
            $respuesta = Array (
                'code' => 304,
                'status' => 'error',
                'message' => 'POI not deleted.',
            );
        }

        return response()->json($respuesta, $respuesta['code']);
    }
}
