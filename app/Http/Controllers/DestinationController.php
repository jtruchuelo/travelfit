<?php

namespace App\Http\Controllers;

use App\Destination;
use App\Http\Resources\DestinationResource;
use Illuminate\Http\Request;
use Validator;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DestinationResource::collection(Destination::all())->sortBy('startDate');
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
            'destinations' => 'required|array',
            'destinations.name.*' => 'required|string|max:60',
            'destinations.idApi.*' => 'required|max:50',
            'destinations.startDate.*' => 'required|date',
            'destinations.endDate.*' => 'required|date',
            'destinations.itinerary_id.*' => 'required|integer|exists:itineraries,id',
            'destinations.location.*' => 'required',
            'destinations.pois.*' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $respuesta = Array (
                'code' => 401,
                'status' => 'error',
                'message' => $validator->messages(),
            );
        } else {
            $destination = Destination::create([
                'name' => $request->destinations['name'],
                'idApi' => $request->destinations['idApi'],
                'startDate' => $request->destinations['startDate'],
                'endDate' => $request->destinations['endDate'],
                'itinerary_id' => $request['new_itinerary_id'],
                'location' => json_encode($request->destinations['location']),
                'photo' => $request->destinations['photo'],
            ]);

            if($destination) {
                $data['new_destination_id'] = $destination->id;
                $request->merge($data);
                $respuestaPoi = app('App\Http\Controllers\PoiController')->store($request);
                if ($respuestaPoi->original['status'] == 'success') {
                    $respuesta = Array (
                        'code' => 201,
                        'status' => 'success',
                    );
                } else {
                    $respuesta = Array (
                        'code' => 401,
                        'status' => 'failed',
                        'message' => $respuestaPoi->original['message'],
                    );
                }
            } else {
                $respuesta = Array (
                    'code' => 401,
                    'status' => 'error',
                    'message' => 'Destination not created.',
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
    public function show(Destination $destination)
    {
        $respuesta = Array (
            'code' => 200,
            'status' => 'success',
            'destination' => new DestinationResource($destination)
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
    public function update(Request $request, Destination $destination)
    {
        $rules = [
            'name' => 'required|string|max:60',
            'idApi' => 'required|alpha_num|max:50',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'itinerary_id' => 'required|integer|exists:itineraries,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator) {
            $destination->update($request->only(['startDate', 'endDate']));
            $respuesta = Array (
                'code' => 200,
                'status' => 'success',
            );

        } else {
            $respuesta = Array (
                'code' => 304,
                'status' => 'error',
                'message' => 'Destination not modified.',
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
    public function destroy(Destination $destination)
    {
        if ($destination->delete()) {
            $respuesta = Array (
                'code' => 200,
                'status' => 'success',
            );
        } else {
            $respuesta = Array (
                'code' => 304,
                'status' => 'error',
                'message' => 'Destination not deleted.',
            );
        }

        return response()->json($respuesta, $respuesta['code']);
    }
}
