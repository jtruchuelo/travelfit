<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use App\User;
use Validator;
use Auth;

class AuthController extends Controller
{
    private $apiToken;

    public function __construct()
    {
        // Unique Token
        $this->apiToken = uniqid(base64_encode(Str::random(60)));
    }

    /**
     *
     * LOGIN USER
     *
     */
    public function login (Request $request) // Acceso por POST
    {
        // Validations
        $rules = [
            'email'=>'required|email|string',
            'password'=>'required|string|min:6',
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
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                // Update Token
                $postArray = ['api_token' => $this->apiToken];
                $user = Auth::user();
                if ($user->update($postArray)) {
                    $respuesta = Array (
                        'code' => 202,
                        'status' => 'success',
                        // 'id_user' => $user->id,
                        'userToken' => $this->apiToken,
                        'user' => new UserResource($user),
                    );
                }
            } else {
                $respuesta = Array (
                    'code' => 401,
                    'status' => 'error',
                    'message' => 'Unauthorized, check your credentials',
                );
            }
        }

        return response()->json($respuesta, $respuesta['code']);
    }


    /**
     *
     * REGISTER NEW USER
     *
     */
    public function register (Request $request) // Acceso por POST
    {

        // Validations
        $rules = [
            'name' => 'required|string|max:25',
            'lastname' => 'required|string|max:50',
            'username' => 'required|alpha_dash|string|max:20|unique:users',
            'email' => 'required|email|string|max:50|unique:users',
            'password' => 'required|alpha_dash|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // Validation failed
            $respuesta = Array (
                'code' => 400 ,
                'status' => 'error',
                'message' => $validator->messages(),
            );
        } else {

            $user = User::create([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password, ['rounds' => 4]),
                'api_token' => $this->apiToken,
            ]);

            if($user) {
                $respuesta = Array (
                    'code' => 201,
                    'status' => 'success',
                    'name' => $request->name,
                    'email' => $request->email,
                    'userToken' => $this->apiToken,
                );
            } else {
                $respuesta = Array (
                    'code' => 400 ,
                    'status' => 'error',
                    'message' => 'Registration failed, please try again.',
                );
            }
        }

        return response()->json($respuesta, $respuesta['code']);
    }


    /**
     *
     * LOGOUT USER
     *
     */
    public function logout (Request $request)
    {
        $token = $request->header('Authorization');
        $user = User::where('api_token',$token)->first();
        if($user) {
            $postArray = ['api_token' => null];
            $logout = User::where('id',$user->id)->update($postArray);
            if($logout) {
                $respuesta = Array (
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'User Logged Out',
                );
            }
        } else {
            $respuesta = Array (
                'code' => 400 ,
                'status' => 'error',
                'message' => 'User not found',
            );
        }

        return response()->json($respuesta, $respuesta['code']);
    }
}
