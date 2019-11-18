<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Recibir datos
        $json = $request->input('json', null);
        // $params = json_decode($json); // Objeto
        $params_array = json_decode($json, true); // Array

        // if (!empty($params) && !empty($params_array)){
        if (!empty($params_array)){

            // Limpiar datos array
            $params_array = array_map('trim', $params_array);

            // Validar datos
            $validate = \Validator::make($params_array,[
                'name' => 'required|alpha|string|max:25',
                'lastname' => 'required|alpha|string|max:50',
                'username' => 'required|alpha|string|max:20|unique:users',
                'email' => 'required|email|string|max:50|unique:users',
                'password' => 'required|min:6',
            ]);

            if ($validate->fails()) {
                // Validación incorrecta
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'User not created',
                    'error' => $validate->errors()
                );
            } else {
                // Validación correcta

                // Cifrar password
                // $pwd = password_hash($params->password, PASSWORD_BCRYPT, ['cost' => 4]);
                // $pwd = Hash::make($params->password, [
                //     'rounds' => 4
                // ]);

                $pwd = Hash::make($params_array['password'], [
                    'rounds' => 4
                ]);

                // Comprobar si existe (Lo hago en la validación)

                // Crear usuario
                $user = User::create([
                    'name' => $params_array['name'],
                    'lastname' => $params_array['lastname'],
                    'username' => $params_array['username'],
                    'email' => $params_array['email'],
                    'password' => $pwd,
                ]);
                /*
                $user = new User();
                $user->name = $params_array['name'];
                $user->lastname = $params_array['lastname'];
                $user->username = $params_array['username'];
                $user->email = $params_array['email'];
                $user->password = $pwd;

                // Guardar usuario
                $user->save();
                */

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'User created',
                    'user' => $user
                );
            }

            // Retorno
        } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Wrong data',
            );
        }

        // return response()->json($validator->errors()->toJson(), 400);
        return response()->json($data, $data['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
