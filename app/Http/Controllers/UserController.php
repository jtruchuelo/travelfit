<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Resources\UserResource;
use Validator;

class UserController extends Controller
{

    /*
    public function __construct() {
        $this->middleware('auth.basic', ['only' => ['store', 'update']])
    }
    */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        if ($request->user_id !== $user->id) {
            $respuesta = Array (
                'code' => 403,
                'status' => 'error',
                'message' => 'You can only view your user.'
            );
        } else {
            $respuesta = Array (
                'code' => 200,
                'status' => 'success',
                'user' => new UserResource($user)
            );
        }

        return response()->json($respuesta, $respuesta['code']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:25',
            'lastname' => 'required|string|max:50',
            'username' => 'required|string|max:20|unique:users',
            'email' => 'required|email|string|max:50|unique:users',
            'password' => 'required|alpha_dash|min:6' // Mirar esto para que no lo actualice si no esta, puesto que una vez cifrado.. no se envia
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator) {
            $request['password'] = Hash::make($request->password, ['rounds' => 4]);
            $user->update($request->only(['name', 'lastname', 'username', 'email', 'password']));
            $respuesta = Array (
                'code' => 200,
                'status' => 'success',
            );

        } else {
            $respuesta = Array (
                'code' => 304,
                'status' => 'error',
                'message' => 'User not modified.',
            );
        }

        return response()->json($respuesta, $respuesta['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    // public function destroy(User $user)
    // {
    //     //
    // }
}
