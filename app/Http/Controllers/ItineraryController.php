<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItineraryResource;
use App\Http\Resources\ItineraryResourceCollection;
use App\Itinerary;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class ItineraryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new ItineraryResourceCollection(Itinerary::where('isPublic', true)->paginate());
        // return new ItineraryResourceCollection(Itinerary::paginate()->where('isPublic', true));
        // return new ItineraryResourceCollection(Itinerary::all()->where('isPublic', true));
    }

    public function indexUser(Request $request, User $user)
    {
        if($request->user_id == $user->id) {
            return new ItineraryResourceCollection(Itinerary::where('user_id', '=', $user->id)->paginate());
        } else {
            $respuesta = Array (
                'code' => 403,
                'status' => 'error',
                'message' => 'Not authorized.',
            );
        }

        return response()->json($respuesta, $respuesta['code']);
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
            'createdDate' => 'required|date',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'isPublic' => 'required|boolean',
            'user_id' => 'required|integer|exists:users,id',
            'user_name' => 'required|string',
            'destinations' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $respuesta = Array (
                'code' => 401,
                'status' => 'error',
                'message' => $validator->messages(),
            );
        } else {
            $itinerary = Itinerary::create([
                'name' => $request->name,
                'createdDate' => Carbon::now(),
                'startDate' => $request->startDate,
                'endDate' => $request->endDate,
                'isPublic' => $request->isPublic,
                'user_id' => $request->user_id,
            ]);

            if($itinerary) {
                $data['new_itinerary_id'] = $itinerary->id;
                $request->merge($data);
                $respuestaDestino = app('App\Http\Controllers\DestinationController')->store($request);
                if ($respuestaDestino->original['status'] == 'success') {
                    $respuesta = Array (
                        'code' => 201,
                        'status' => 'success',
                    );
                } else {
                    $respuesta = Array (
                        'code' => 401,
                        'status' => 'failed',
                        'message' => $respuestaDestino->original['message'],
                    );
                }
            } else {
                $respuesta = Array (
                    'code' => 401,
                    'status' => 'error',
                    'message' => 'Itinerary not created.',
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
    public function show(Itinerary $itinerary)
    {
        if ($itinerary->isPublic){
            $respuesta = Array (
                'code' => 200,
                'status' => 'success',
                'itinerary' => new ItineraryResource($itinerary)
            );
        } else {
            $respuesta = Array (
                'code' => 401,
                'status' => 'error',
                'message' => 'Itinerary not public'
            );
        }

        return response()->json($respuesta, $respuesta['code']);
    }

    public function showUser(Request $request, User $user, Itinerary $itinerary)
    {
        if ($request->user_id == $itinerary->user_id && $itinerary->user_id == $user->id ){
            $respuesta = Array (
                'code' => 200,
                'status' => 'success',
                'itinerary' => new ItineraryResource($itinerary)
            );
        } else {
            $respuesta = Array (
                'code' => 401,
                'status' => 'error',
                'message' => 'Itinerary not public'
            );
        }

        return response()->json($respuesta, $respuesta['code']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Itinerary $itinerary)
    {
        if ($request->user_id !== $itinerary->user_id) {
            $respuesta = Array (
                'code' => 403,
                'status' => 'error',
                'message' => 'You can only delete your own itineraries.'
            );
        } else {
            $rules = [
                'name' => 'required|string|max:60',
                'isPublic' => 'required|boolean',
                'user_id' => 'required|integer|exists:users,id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator) {
                $itinerary->update($request->only(['name', 'isPublic']));
                $respuesta = Array (
                    'code' => 200,
                    'status' => 'success',
                );

            } else {
                $respuesta = Array (
                    'code' => 304,
                    'status' => 'error',
                    'message' => 'Itinerary not modified.',
                );
            }
        }

        return response()->json($respuesta, $respuesta['code']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Itinerary $itinerary)
    {

        if ($request->user_id !== $itinerary->user_id) {
            $respuesta = Array (
                'code' => 403,
                'status' => 'error',
                'message' => 'You can only delete your own itineraries.'
            );
        } else {
            if ($itinerary->delete()) {
                $respuesta = Array (
                    'code' => 200,
                    'status' => 'success',
                );
            } else {
                $respuesta = Array (
                    'code' => 304,
                    'status' => 'error',
                    'message' => 'Itinerary not deleted.',
                );
            }
         }

        return response()->json($respuesta, $respuesta['code']);
    }
}
