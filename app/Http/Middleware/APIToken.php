<?php

namespace App\Http\Middleware;
use App\User;
use Validator;
use Closure;

class APIToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if($request->headers->has('Authorization')){

        $login = User::where('api_token', $request->header('Authorization'))->exists();
        $user = User::where('api_token', $request->header('Authorization'))->first();

        if (!$login){
            return response()->json([
                'status' => 'error',
                'message' => 'Not authorized API Key',
            ], 400);
        } else {
            return $next($request->merge(['user_id' => $user->id])); //->merge(['key' => 'value'])  o  $request->request->add(['author' => $authorName]);
        }
      }
      return response()->json([
        'message' => 'Not a valid API request.',
      ]);
    }
}
