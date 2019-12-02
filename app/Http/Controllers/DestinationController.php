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
            'name' => 'required|string|max:60',
            'idApi' => 'required|alpha_num|max:50',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'itinerary_id' => 'required|integer|exists:itineraries,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $respuesta = Array (
                'code' => 401,
                'status' => 'error',
                'message' => $validator->messages(),
            );
        } else {
            $poi = Destination::create([
                'name' => $request->name,
                'idApi' => $request->idApi,
                'startDate' => $request->startDate,
                'endDate' => $request->endDate,
                'itinerary_id' => $request->itinerary_id,
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
